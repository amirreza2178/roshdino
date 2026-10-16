<?php

namespace App\Services;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function __construct(private NewLeadNotice $notice) {}

    public function capture(StoreLeadRequest $request): ?Lead
    {
        if ($request->isSpam()) {
            return null;
        }

        $data = $request->validated();
        $intent = LeadIntent::from($data['intent']);

        $lead = DB::transaction(function () use ($request, $data, $intent): Lead {
            $lead = Lead::query()->create([
                'name' => $data['name'],
                'contact' => $this->normalizeContact($data['contact']),
                'service' => $data['service'] ?? LeadServiceInterest::Unsure->value,
                'intent' => $intent,
                'problem' => $data['problem'] ?? '',
                'status' => LeadStatus::New,
                'source' => 'website',
                'ip_address' => $request->ip(),
            ]);

            if ($intent === LeadIntent::Meeting) {
                $lead->meetings()->create([
                    'meets_at' => Carbon::parse($data['meets_at'], 'Asia/Tehran')->utc(),
                ]);
            }

            return $lead;
        });

        $this->notice->send($lead);

        return $lead;
    }

    private function normalizeContact(string $contact): string
    {
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return mb_strtolower($contact);
        }

        $digits = preg_replace('/\D+/', '', $contact) ?? '';

        if (str_starts_with($digits, '989') && strlen($digits) === 12) {
            return '0'.substr($digits, 2);
        }

        return $digits;
    }
}
