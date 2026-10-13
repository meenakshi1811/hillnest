<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $formUrl = route('contact').'#contact-form';

        // Honeypot: real visitors never see or fill this field.
        if (filled($request->input('website'))) {
            return redirect()->to($formUrl)->with('contact_sent', true);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'order_number' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        if ($validator->fails()) {
            throw (new ValidationException($validator))->redirectTo($formUrl);
        }

        $contactMessage = ContactMessage::create($validator->validated() + [
            'user_id' => $request->user()?->id,
        ]);

        try {
            Mail::to(config('hillnest.contact_form.to'))
                ->cc(config('hillnest.contact_form.cc'))
                ->send(new ContactMessageMail($contactMessage));
        } catch (\Throwable $e) {
            // The message is saved and visible in the admin portal even if the email fails.
            Log::error('Contact message email failed', [
                'contact_message_id' => $contactMessage->id,
                'message' => $e->getMessage(),
            ]);
        }

        return redirect()->to($formUrl)->with('contact_sent', true);
    }
}
