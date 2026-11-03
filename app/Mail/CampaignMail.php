<?php

namespace App\Mail;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    protected ?Coupon $coupon;

    /**
     * @param  array<string, mixed>  $content  Template fields: subject, preheader, heading, body, coupon_id, button_label, button_url.
     */
    public function __construct(
        public array $content,
        public string $recipientName,
        public ?string $unsubscribeUrl = null,
        public bool $isTest = false,
    ) {
        $this->coupon = ! empty($content['coupon_id']) ? Coupon::find($content['coupon_id']) : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ($this->isTest ? '[Test] ' : '').$this->fill($this->content['subject'] ?? ''),
        );
    }

    public function headers(): Headers
    {
        if (! $this->unsubscribeUrl) {
            return new Headers;
        }

        return new Headers(text: [
            'List-Unsubscribe' => '<'.$this->unsubscribeUrl.'>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    public function content(): Content
    {
        $buttonUrl = $this->content['button_url'] ?? null;

        if ($buttonUrl && str_starts_with($buttonUrl, '/')) {
            $buttonUrl = url($buttonUrl);
        }

        return new Content(
            view: 'emails.campaign',
            with: [
                'title' => $this->fill($this->content['subject'] ?? ''),
                'preheader' => $this->fill($this->content['preheader'] ?? '') ?: null,
                'heading' => $this->fill($this->content['heading'] ?? ''),
                'bodyHtml' => $this->bodyHtml(),
                'coupon' => $this->coupon,
                'buttonLabel' => $this->content['button_label'] ?? null,
                'buttonUrl' => $buttonUrl,
                'unsubscribeUrl' => $this->unsubscribeUrl,
                'isTest' => $this->isTest,
            ],
        );
    }

    public function renderedSubject(): string
    {
        return $this->fill($this->content['subject'] ?? '');
    }

    protected function fill(string $text): string
    {
        $name = trim($this->recipientName) ?: 'there';

        return strtr($text, [
            '{first_name}' => Str::before($name, ' '),
            '{name}' => $name,
            '{coupon_code}' => $this->coupon?->code ?? '',
            '{discount}' => $this->coupon?->value_label ?? '',
        ]);
    }

    /**
     * The message supports simple formatting (**bold**, links, lists). Raw HTML is stripped.
     */
    protected function bodyHtml(): string
    {
        $html = Str::markdown($this->fill($this->content['body'] ?? ''), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // Email clients ignore stylesheets, so style the generated tags inline.
        return strtr($html, [
            '<p>' => '<p style="margin:0 0 16px;color:#5C4A34;font-size:16px;line-height:1.75;">',
            '<ul>' => '<ul style="margin:0 0 16px;padding-left:22px;color:#5C4A34;font-size:16px;line-height:1.75;">',
            '<ol>' => '<ol style="margin:0 0 16px;padding-left:22px;color:#5C4A34;font-size:16px;line-height:1.75;">',
            '<strong>' => '<strong style="color:#1E3B2F;">',
            '<a href=' => '<a style="color:#C9973A;font-weight:700;" href=',
        ]);
    }
}
