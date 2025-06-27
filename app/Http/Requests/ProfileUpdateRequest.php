<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $userId = $user ? $user->id : null;
        $userType = $user ? $user->user_type : null;

        $rules = [
            // Core fields (read-only in our new form, but keeping for backward compatibility)
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId)
            ],

            // Personal information fields
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]*$/'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];

        // Add teacher-specific validation rules
        if ($userType === 'teacher') {
            $rules['school_name'] = ['nullable', 'string', 'max:255'];
            $rules['subjects_taught'] = ['nullable', 'string', 'max:500'];
            $rules['experience_years'] = ['nullable', 'integer', 'min:0', 'max:50'];
        }

        // Add student-specific validation rules
        if ($userType === 'student') {
            $rules['grade_level'] = ['nullable', 'integer', 'min:1', 'max:12'];
            $rules['favorite_subject'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صحيح.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.regex' => 'رقم الهاتف غير صحيح. يجب أن يحتوي على أرقام فقط.',
            'phone.max' => 'رقم الهاتف يجب أن يكون أقل من 20 رقم.',
            'birth_date.date' => 'تاريخ الميلاد غير صحيح.',
            'birth_date.before' => 'تاريخ الميلاد يجب أن يكون في الماضي.',
            'bio.max' => 'النبذة الشخصية يجب أن تكون أقل من 1000 حرف.',
            'school_name.max' => 'اسم المدرسة يجب أن يكون أقل من 255 حرف.',
            'subjects_taught.max' => 'المواد المُدرسة يجب أن تكون أقل من 500 حرف.',
            'experience_years.integer' => 'سنوات الخبرة يجب أن تكون رقم صحيح.',
            'experience_years.min' => 'سنوات الخبرة يجب أن تكون 0 أو أكثر.',
            'experience_years.max' => 'سنوات الخبرة يجب أن تكون أقل من 50 سنة.',
            'grade_level.integer' => 'المرحلة الدراسية يجب أن تكون رقم صحيح.',
            'grade_level.min' => 'المرحلة الدراسية يجب أن تكون من 1 إلى 12.',
            'grade_level.max' => 'المرحلة الدراسية يجب أن تكون من 1 إلى 12.',
            'favorite_subject.max' => 'المادة المفضلة يجب أن تكون أقل من 255 حرف.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'birth_date' => 'تاريخ الميلاد',
            'bio' => 'النبذة الشخصية',
            'school_name' => 'اسم المدرسة',
            'subjects_taught' => 'المواد المُدرسة',
            'experience_years' => 'سنوات الخبرة',
            'grade_level' => 'المرحلة الدراسية',
            'favorite_subject' => 'المادة المفضلة',
        ];
    }
}