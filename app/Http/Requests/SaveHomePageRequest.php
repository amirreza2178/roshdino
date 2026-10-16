<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveHomePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $story = $this->all();

        foreach (['problem' => 'items', 'process' => 'steps', 'principle' => 'items'] as $section => $key) {
            $rows = $story[$section][$key] ?? [];
            $story[$section][$key] = array_values(array_filter(
                $rows,
                fn ($row) => is_array($row) && filled($row['title'] ?? null),
            ));
        }

        $questions = $story['faq']['items'] ?? [];
        $story['faq']['items'] = array_values(array_filter(
            $questions,
            fn ($row) => is_array($row) && filled($row['question'] ?? null),
        ));

        $this->replace($story);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $text = ['required', 'string', 'max:500'];
        $line = ['required', 'string', 'max:180'];

        return [
            'hero.eyebrow' => ['nullable', 'string', 'max:80'],
            'hero.title' => $line,
            'hero.accent' => ['nullable', 'string', 'max:120'],
            'hero.lead' => $text,
            'hero.note' => ['nullable', 'string', 'max:200'],
            'problem.eyebrow' => ['nullable', 'string', 'max:80'],
            'problem.title' => $line,
            'problem.accent' => ['nullable', 'string', 'max:120'],
            'problem.lead' => ['nullable', 'string', 'max:500'],
            'problem.items' => ['required', 'array', 'min:1', 'max:6'],
            'problem.items.*.title' => $line,
            'problem.items.*.body' => $text,
            'process.eyebrow' => ['nullable', 'string', 'max:80'],
            'process.title' => $line,
            'process.accent' => ['nullable', 'string', 'max:120'],
            'process.lead' => ['nullable', 'string', 'max:500'],
            'process.steps' => ['required', 'array', 'min:1', 'max:6'],
            'process.steps.*.title' => $line,
            'process.steps.*.body' => $text,
            'principle.eyebrow' => ['nullable', 'string', 'max:80'],
            'principle.title' => $line,
            'principle.accent' => ['nullable', 'string', 'max:120'],
            'principle.lead' => ['nullable', 'string', 'max:500'],
            'principle.items' => ['required', 'array', 'min:1', 'max:6'],
            'principle.items.*.title' => $line,
            'principle.items.*.body' => $text,
            'faq.eyebrow' => ['nullable', 'string', 'max:80'],
            'faq.title' => $line,
            'faq.accent' => ['nullable', 'string', 'max:120'],
            'faq.items' => ['required', 'array', 'min:1', 'max:6'],
            'faq.items.*.question' => $line,
            'faq.items.*.answer' => $text,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hero.title.required' => 'عنوان قهرمان را بنویس.',
            'hero.lead.required' => 'توضیح قهرمان را بنویس.',
            'problem.items.min' => 'حداقل یک مسئله بنویس.',
            'process.steps.min' => 'حداقل یک قدم فرآیند بنویس.',
            'principle.items.min' => 'حداقل یک اصل بنویس.',
            'faq.items.min' => 'حداقل یک سؤال بنویس.',
            'problem.items.*.title.required' => 'عنوان مسئله را بنویس.',
            'problem.items.*.body.required' => 'توضیح مسئله را بنویس.',
            'process.steps.*.title.required' => 'عنوان قدم را بنویس.',
            'process.steps.*.body.required' => 'توضیح قدم را بنویس.',
            'principle.items.*.title.required' => 'عنوان اصل را بنویس.',
            'principle.items.*.body.required' => 'توضیح اصل را بنویس.',
            'faq.items.*.question.required' => 'سؤال را بنویس.',
            'faq.items.*.answer.required' => 'جواب را بنویس.',
        ];
    }
}
