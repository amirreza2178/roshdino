<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadArrived extends Mailable
{
    use Queueable, SerializesModels;

    private ?string $cachedBody = null;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address((string) config('mail.from.address'), 'رشدینو'),
            subject: 'سرنخ تازه: '.$this->lead->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.new-lead-arrived',
            with: ['body' => $this->body()],
        );
    }

    public function body(): string
    {
        return $this->cachedBody ??= $this->compose();
    }

    private function compose(): string
    {
        $lines = [
            'سرنخ تازه در رشدینو',
            'نام: '.$this->lead->name,
            'اقدام: '.$this->lead->intent->label(),
            'خدمت: '.$this->lead->service->label(),
            'راه تماس: '.$this->lead->contact,
        ];

        if ($this->lead->problem !== '') {
            $lines[] = 'مسئله: '.$this->lead->problem;
        }

        $meetsAt = $this->lead->meetings()->first()?->meets_at;

        if ($meetsAt !== null) {
            $lines[] = 'زمان جلسه: '.$meetsAt->timezone('Asia/Tehran')->format('Y-m-d H:i');
        }

        $lines[] = 'وضعیت: '.$this->lead->status->label();
        $lines[] = 'فهرست: '.route('leads.index');

        return implode("\n", $lines);
    }
}
