<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\EmailTemplate;
use App\Services\EmailCampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    /** Name used in previews and test emails in place of a real customer's name. */
    public const SAMPLE_NAME = 'Priya Sharma';

    public function __construct(private EmailCampaignService $campaigns)
    {
    }

    public function index(): View
    {
        $templates = EmailTemplate::query()
            ->with('coupon')
            ->withCount('campaigns')
            ->latest('updated_at')
            ->get();

        return view('admin.email-templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.email-templates.form', [
            'template' => null,
            'coupons' => $this->couponOptions(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        EmailTemplate::create($this->validated($request));

        return response()->json([
            'success' => true,
            'message' => 'Template saved.',
            'redirect' => route('admin.email-templates.index'),
        ]);
    }

    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('admin.email-templates.form', [
            'template' => $emailTemplate,
            'coupons' => $this->couponOptions($emailTemplate->coupon_id),
        ]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate): JsonResponse
    {
        $emailTemplate->update($this->validated($request));

        return response()->json([
            'success' => true,
            'message' => 'Template updated.',
            'redirect' => route('admin.email-templates.index'),
        ]);
    }

    public function destroy(EmailTemplate $emailTemplate): JsonResponse
    {
        $emailTemplate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted.',
        ]);
    }

    /** Renders the email for the preview window, from a saved template or the unsaved form. */
    public function preview(Request $request): JsonResponse
    {
        return response()->json([
            'html' => $this->campaigns->previewHtml($this->contentFromRequest($request), self::SAMPLE_NAME),
        ]);
    }

    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ], [
            'test_email.required' => 'Enter an email address to send the test to.',
        ]);

        $content = $this->contentFromRequest($request);

        try {
            $this->campaigns->sendTest($content, $request->input('test_email'), self::SAMPLE_NAME);
        } catch (\Throwable $e) {
            Log::error('Test campaign email failed', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'The test email could not be sent. Please check the mail settings and try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test email sent to '.$request->input('test_email').'.',
        ]);
    }

    /** @return array<string, mixed> */
    private function contentFromRequest(Request $request): array
    {
        if ($request->filled('template_id')) {
            $request->validate(['template_id' => ['integer', 'exists:email_templates,id']]);

            return EmailTemplate::findOrFail($request->input('template_id'))->toContent();
        }

        $data = $request->validate($this->contentRules());

        return [
            'subject' => $data['subject'],
            'preheader' => $data['preheader'] ?? null,
            'heading' => $data['heading'],
            'body' => $data['body'],
            'coupon_id' => $data['coupon_id'] ?? null,
            'button_label' => $data['button_label'] ?? null,
            'button_url' => $data['button_url'] ?? null,
        ];
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:100']] + $this->contentRules(), [
            'button_url.regex' => 'The button link must start with / (a page on this site) or https://.',
        ]);
    }

    /** @return array<string, list<string>> */
    private function contentRules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:200'],
            'preheader' => ['nullable', 'string', 'max:200'],
            'heading' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:5000'],
            'coupon_id' => ['nullable', 'integer', 'exists:coupons,id'],
            'button_label' => ['nullable', 'string', 'max:60', 'required_with:button_url'],
            'button_url' => ['nullable', 'string', 'max:500', 'required_with:button_label', 'regex:/^(\/|https?:\/\/)/'],
        ];
    }

    /**
     * Coupons that can go in an email: all-customer coupons that still work,
     * plus the template's current coupon so editing never drops it silently.
     */
    private function couponOptions(?int $currentCouponId = null)
    {
        return Coupon::query()
            ->where(function ($query) use ($currentCouponId) {
                $query->where(function ($inner) {
                    $inner->where('for_all', true)
                        ->where(fn ($expiry) => $expiry->whereNull('expires_at')->orWhere('expires_at', '>', now()));
                });

                if ($currentCouponId) {
                    $query->orWhere('id', $currentCouponId);
                }
            })
            ->latest('id')
            ->get();
    }
}
