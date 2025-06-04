@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Profile Information -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            معلومات الملف الشخصي
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            قم بتحديث معلومات ملفك الشخصي وعنوان البريد الإلكتروني.
                        </p>
                    </header>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="name" value="الاسم" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" value="البريد الإلكتروني" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-gray-800">
                                        بريدك الإلكتروني غير مُحقق.
                                        <button form="send-verification" 
                                            class="underline text-sm text-gray-600 hover:text-gray-900">
                                            اضغط هنا لإعادة إرسال رسالة التحقق
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if($user->user_type === 'teacher')
                        <div>
                            <x-input-label for="school_name" value="اسم المدرسة" />
                            <x-text-input id="school_name" name="school_name" type="text" class="mt-1 block w-full" 
                                :value="old('school_name', $user->school_name)" />
                            <x-input-error class="mt-2" :messages="$errors->get('school_name')" />
                        </div>
                        @endif

                        @if($user->user_type === 'student')
                        <div>
                            <x-input-label for="grade_level" value="المرحلة الدراسية" />
                            <select id="grade_level" name="grade_level" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">اختر المرحلة</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ $user->grade_level == $i ? 'selected' : '' }}>
                                        الصف {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
                        </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>حفظ</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-gray-600">تم الحفظ بنجاح.</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <!-- Update Password -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete User -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

<form method="post" action="{{ route('verification.send') }}" id="send-verification">
    @csrf
</form>
@endsection