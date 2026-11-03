<?php

namespace App\Services;

use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailCampaignService
{
    /**
     * Small batches keep each request well under shared-hosting time limits;
     * the admin page keeps requesting batches until the campaign is done.
     */
    public const BATCH_SIZE = 5;

    /** Customers who can receive offer emails: active, with an email address, not unsubscribed. */
    public function reachableCustomers(): Builder
    {
        return User::query()
            ->where('is_admin', false)
            ->where('is_blocked', false)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->whereNull('marketing_unsubscribed_at');
    }

    /**
     * @param  list<int>  $userIds  Used when $audience is 'selected'.
     */
    public function createCampaign(EmailTemplate $template, string $audience, array $userIds, ?User $admin): EmailCampaign
    {
        $customers = $this->reachableCustomers()
            ->when($audience === 'selected', fn (Builder $query) => $query->whereIn('id', $userIds))
            ->orderBy('id')
            ->get(['id', 'name', 'email']);

        return DB::transaction(function () use ($template, $audience, $customers, $admin) {
            $campaign = EmailCampaign::create([
                'email_template_id' => $template->id,
                'template_name' => $template->name,
                'subject' => $template->subject,
                'content' => $template->toContent(),
                'audience' => $audience,
                'total_recipients' => $customers->count(),
                'created_by' => $admin?->id,
                'completed_at' => $customers->isEmpty() ? now() : null,
            ]);

            $now = now();

            foreach ($customers->chunk(500) as $chunk) {
                $campaign->recipients()->insert($chunk->map(fn (User $customer) => [
                    'email_campaign_id' => $campaign->id,
                    'user_id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all());
            }

            return $campaign;
        });
    }

    public function sendNextBatch(EmailCampaign $campaign): EmailCampaign
    {
        $recipients = $campaign->recipients()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit(self::BATCH_SIZE)
            ->get();

        foreach ($recipients as $recipient) {
            try {
                $unsubscribeUrl = $recipient->user_id ? $this->unsubscribeUrl($recipient->user_id) : null;

                Mail::to($recipient->email, $recipient->name)
                    ->send(new CampaignMail($campaign->content, $recipient->name, $unsubscribeUrl));

                $recipient->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
            } catch (\Throwable $e) {
                $recipient->update(['status' => 'failed', 'error' => mb_substr($e->getMessage(), 0, 500)]);

                Log::warning('Campaign email failed', [
                    'email_campaign_id' => $campaign->id,
                    'recipient_id' => $recipient->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $counts = $campaign->recipients()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $campaign->update([
            'sent_count' => (int) ($counts['sent'] ?? 0),
            'failed_count' => (int) ($counts['failed'] ?? 0),
            'completed_at' => ($counts['pending'] ?? 0) > 0 ? null : ($campaign->completed_at ?? now()),
        ]);

        return $campaign->fresh();
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public function sendTest(array $content, string $email, string $sampleName): void
    {
        Mail::to($email)->send(new CampaignMail($content, $sampleName, null, true));
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public function previewHtml(array $content, string $sampleName): string
    {
        return (new CampaignMail($content, $sampleName, '#unsubscribe-preview'))->render();
    }

    public function unsubscribeUrl(int $userId): string
    {
        return URL::signedRoute('marketing.unsubscribe', ['user' => $userId]);
    }
}
