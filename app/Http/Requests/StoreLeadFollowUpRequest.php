<?php

namespace App\Http\Requests;

use App\Enums\FollowUpChannel;
use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreLeadFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('note')) {
            $this->merge([
                'note' => trim((string) $this->input('note')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'followed_up_at' => ['required', 'date'],
            'channel' => ['required', Rule::enum(FollowUpChannel::class)],
            'note' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'followed_up_at.required' => 'زمان پیگیری را وارد کن.',
            'followed_up_at.date' => 'زمان پیگیری درست نیست.',
            'channel.required' => 'کانال را انتخاب کن.',
            'channel.enum' => 'این کانال برای پیگیری نیست.',
            'note.required' => 'یادداشت پیگیری لازم است.',
            'note.max' => 'یادداشت پیگیری را کوتاه‌تر بنویس.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'follow-up-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
