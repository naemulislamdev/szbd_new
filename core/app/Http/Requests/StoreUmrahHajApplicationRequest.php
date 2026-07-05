<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmrahHajApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public form — সবাই সাবমিট করতে পারবে
    }


    public function rules(): array
    {
        return [
            // ১. ব্যক্তিগত তথ্য
            'full_name'                     => ['required', 'string', 'max:150'],
            'mobile_number'                 => ['required', 'string', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'whatsapp_number'               => ['nullable', 'string', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'email'                         => ['nullable', 'email', 'max:255'],
            'address'                       => ['required', 'string', 'max:500'],
            'occupation'                    => ['required', 'string', 'max:100'],

            // ২. আবেদনকারীর তথ্য
            'age'                           => ['required', 'integer', 'min:1', 'max:120'],
            'gender'                        => ['required', 'in:পুরুষ,মহিলা'],
            'marital_status'                => ['required', 'in:অবিবাহিত,বিবাহিত'],
            'has_done_umrah_or_haj_before'  => ['nullable', 'string'],

            // ৩. পাসপোর্ট তথ্য
            'has_valid_passport'            => ['required', 'boolean'],
            'passport_validity_6_months'    => ['nullable', 'boolean', 'required_if:has_valid_passport,true'],
            'passport_number'               => ['nullable', 'string', 'max:50'],
            'passport_expiry_date'          => ['nullable', 'date', 'after:today'],

            'can_self_finance'              => ['required', 'boolean'],

            // ৫. সম্মতি
            'terms_accepted'                => ['required', 'accepted'],
            'selection_decision_accepted'   => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'                    => 'পূর্ণ নাম অবশ্যই দিতে হবে।',
            'full_name.max'                         => 'নাম সর্বোচ্চ ১৫০ অক্ষরের হতে হবে।',
            'mobile_number.required'                => 'মোবাইল নম্বর অবশ্যই দিতে হবে।',
            'mobile_number.regex'                   => 'সঠিক বাংলাদেশি মোবাইল নম্বর দিন।',
            'whatsapp_number.regex'                 => 'সঠিক WhatsApp নম্বর দিন।',
            'email.email'                           => 'সঠিক ইমেইল ঠিকানা দিন।',
            'address.required'                      => 'সম্পূর্ণ ঠিকানা অবশ্যই দিতে হবে।',
            'age.required'                          => 'বয়স অবশ্যই দিতে হবে।',
            'age.min'                               => 'বয়স সঠিক নয়।',
            'age.max'                               => 'বয়স সর্বোচ্চ ১২০ হতে পারে।',
            'gender.required'                       => 'লিঙ্গ নির্বাচন করুন।',
            'gender.in'                             => 'লিঙ্গ সঠিক নয়।',
            'marital_status.required'               => 'বৈবাহিক অবস্থা নির্বাচন করুন।',
            'marital_status.in'                     => 'বৈবাহিক অবস্থা সঠিক নয়।',
            'has_done_umrah_or_haj_before.required' => 'পূর্বে উমরাহ/হজ করেছেন কিনা জানান।',
            'has_valid_passport.required'           => 'পাসপোর্টের তথ্য দিন।',
            'passport_validity_6_months.required_if' => 'পাসপোর্টের মেয়াদ সংক্রান্ত তথ্য দিন।',
            'passport_expiry_date.after'            => 'পাসপোর্টের মেয়াদ শেষের তারিখ ভবিষ্যতের হতে হবে।',


            'can_self_finance.required'             => 'খরচ বহনের তথ্য দিন।',
            'terms_accepted.required'               => 'শর্ত ও নীতি মেনে নিতে হবে।',
            'terms_accepted.accepted'               => 'শর্ত ও নীতি মেনে নিতে হবে।',
            'selection_decision_accepted.required'  => 'নির্বাচন প্রক্রিয়ার সিদ্ধান্ত মেনে নিতে হবে।',
            'selection_decision_accepted.accepted'  => 'নির্বাচন প্রক্রিয়ার সিদ্ধান্ত মেনে নিতে হবে।',
            'occupation.required'                    => 'পেশা অবশ্যই দিতে হবে।',
            'occupation.max'                         => 'পেশা সর্বোচ্চ ১০০ অক্ষরের হতে হবে।',
        ];
    }

    /**
     * Prepare data before validation.
     * passport_validity_6_months শুধু তখনই দরকার যখন has_valid_passport = true
     */
    protected function prepareForValidation(): void
    {
        // Convert "true"/"false" strings to booleans (useful for JSON API requests)
        $booleanFields = [
            'has_done_umrah_or_haj_before',
            'has_valid_passport',
            'passport_validity_6_months',
            'can_self_finance',
            'terms_accepted',
            'selection_decision_accepted',
        ];

        foreach ($booleanFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => filter_var($this->input($field), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                        ?? $this->input($field),
                ]);
            }
        }

        // passport_validity_6_months null করো যদি পাসপোর্ট না থাকে
        if (! $this->boolean('has_valid_passport')) {
            $this->merge(['passport_validity_6_months' => null]);
        }
    }
}
