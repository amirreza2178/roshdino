<?php

namespace App\Http\Requests;

use App\Enums\ProposalStatus;
use App\Models\LeadProposal;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ConvertAcceptedProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $proposal = $this->route('proposal');

            if (! $proposal instanceof LeadProposal) {
                return;
            }

            if ($proposal->status !== ProposalStatus::Accepted) {
                $validator->errors()->add('proposal', 'فقط پیشنهاد پذیرفته‌شده به پروژه تبدیل می‌شود.');

                return;
            }

            if ($proposal->project()->exists()) {
                $validator->errors()->add('proposal', 'این پیشنهاد قبلاً به پروژه تبدیل شده است.');
            }
        });
    }

    protected function failedValidation(Validator $validator): void
    {
        $proposal = $this->route('proposal');

        throw (new ValidationException($validator))
            ->errorBag($proposal instanceof LeadProposal ? 'convert-'.$proposal->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
