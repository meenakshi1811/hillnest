<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Services\EmailCampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmailCampaignController extends Controller
{
    public function __construct(private EmailCampaignService $campaigns)
    {
    }

    public function index(): View
    {
        $campaigns = EmailCampaign::query()->with('createdBy')->latest()->limit(100)->get();

        $stats = [
            'campaigns' => EmailCampaign::count(),
            'emails_sent' => (int) EmailCampaign::sum('sent_count'),
            'reachable' => $this->campaigns->reachableCustomers()->count(),
            'unsubscribed' => User::where('is_admin', false)->whereNotNull('marketing_unsubscribed_at')->count(),
        ];

        return view('admin.emails.index', compact('campaigns', 'stats'));
    }

    public function create(Request $request): View
    {
        $templates = EmailTemplate::query()->orderBy('name')->get(['id', 'name', 'subject']);

        $customers = User::query()
            ->where('is_admin', false)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'is_blocked', 'marketing_unsubscribed_at']);

        return view('admin.emails.create', [
            'templates' => $templates,
            'customers' => $customers,
            'reachableCount' => $this->campaigns->reachableCustomers()->count(),
            'selectedTemplateId' => (int) $request->query('template'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email_template_id' => ['required', 'integer', 'exists:email_templates,id'],
            'audience' => ['required', Rule::in(array_keys(EmailCampaign::AUDIENCES))],
            'user_ids' => ['required_if:audience,selected', 'array'],
            'user_ids.*' => ['integer'],
        ], [
            'email_template_id.required' => 'Choose a template to send.',
            'user_ids.required_if' => 'Select at least one customer.',
        ]);

        $campaign = $this->campaigns->createCampaign(
            EmailTemplate::findOrFail($data['email_template_id']),
            $data['audience'],
            array_map('intval', $data['user_ids'] ?? []),
            $request->user(),
        );

        if ($campaign->total_recipients === 0) {
            $campaign->delete();

            return response()->json([
                'message' => 'None of the chosen customers can receive emails (no email address, blocked, or unsubscribed).',
            ], 422);
        }

        return response()->json($this->progress($campaign) + [
            'send_url' => route('admin.emails.send', $campaign),
            'show_url' => route('admin.emails.show', $campaign),
        ]);
    }

    public function sendBatch(EmailCampaign $emailCampaign): JsonResponse
    {
        return response()->json($this->progress($this->campaigns->sendNextBatch($emailCampaign)));
    }

    public function show(EmailCampaign $emailCampaign): View
    {
        $recipients = $emailCampaign->recipients()
            ->orderByRaw("CASE status WHEN 'failed' THEN 0 WHEN 'pending' THEN 1 ELSE 2 END")
            ->orderBy('id')
            ->paginate(50);

        return view('admin.emails.show', [
            'campaign' => $emailCampaign->load('createdBy'),
            'recipients' => $recipients,
        ]);
    }

    /** @return array<string, int|bool> */
    private function progress(EmailCampaign $campaign): array
    {
        return [
            'total' => $campaign->total_recipients,
            'sent' => $campaign->sent_count,
            'failed' => $campaign->failed_count,
            'pending' => $campaign->pendingCount(),
            'complete' => $campaign->isComplete(),
        ];
    }
}
