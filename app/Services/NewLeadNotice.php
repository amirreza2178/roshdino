<?php

namespace App\Services;

use App\Mail\NewLeadArrived;
use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NewLeadNotice
{
    public function send(Lead $lead): void
    {
        $message = new NewLeadArrived($lead);

        $this->sendEmail($lead, $message);
        $this->sendTelegram($lead, $message);
    }

    private function sendEmail(Lead $lead, NewLeadArrived $message): void
    {
        $email = config('roshdino.contact.email');

        if (! filled($email)) {
            return;
        }

        try {
            Mail::to($email)->send($message);
        } catch (Throwable) {
            Log::warning('خبر ایمیلی سرنخ تازه فرستاده نشد.', ['lead_id' => $lead->id]);
        }
    }

    private function sendTelegram(Lead $lead, NewLeadArrived $message): void
    {
        $token = config('roshdino.contact.telegram_bot_token');
        $chatId = $this->chatId();

        if (! filled($token) || $chatId === null) {
            return;
        }

        try {
            Http::timeout(5)
                ->post('https://api.telegram.org/bot'.$token.'/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $message->body(),
                ])
                ->throw();
        } catch (Throwable) {
            Log::warning('خبر تلگرام سرنخ تازه فرستاده نشد.', ['lead_id' => $lead->id]);
        }
    }

    private function chatId(): ?string
    {
        $chatId = trim((string) config('roshdino.contact.telegram_chat_id'));

        if ($chatId !== '') {
            return $chatId;
        }

        $username = ltrim(trim((string) config('roshdino.contact.telegram')), '@');

        if ($username === '') {
            return null;
        }

        if (preg_match('/^\d+$/', $username) === 1) {
            return $username;
        }

        return '@'.$username;
    }
}
