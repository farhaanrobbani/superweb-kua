<?php

namespace App\Support;

use App\Models\LetterType;
use Illuminate\Http\Request;

class SubmissionForm
{
    public static function fieldRules(LetterType $letterType): array
    {
        $rules = [];

        foreach ($letterType->fields ?? [] as $field) {
            if (! empty($field['internal'])) {
                continue;
            }

            $fieldRules = ['string', 'max:1000'];
            if (! empty($field['required'])) {
                $fieldRules[] = 'required';
            }

            $rules['data.' . $field['name']] = $fieldRules;
        }

        return $rules;
    }

    public static function safeData(LetterType $letterType, Request $request): array
    {
        $data = [];

        foreach ($letterType->fields ?? [] as $field) {
            if (! empty($field['internal'])) {
                continue;
            }

            $data[$field['name']] = $request->input('data.' . $field['name']);
        }

        return $data;
    }
}
