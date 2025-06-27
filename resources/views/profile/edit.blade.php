@extends('layouts.app')

@section('title', 'تحديث الملف الشخصي')

@section('content')
<div class="py-12" x-data="{ activeTab: 'personal', showAvatarModal: false }">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Profile Header with Completion -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-lg shadow-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Avatar Section -->
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur flex items-center justify-center overflow-hidden">
                            @if($user->avatar)
                                @if(str_starts_with($user->avatar, 'http'))
                                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                            @else
                                <span class="text-2xl text-white font-bold">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <button @click="showAvatarModal = true" 
                                class="absolute -bottom-1 -left-1 bg-white rounded-full p-1.5 shadow-lg hover:bg-gray-100 transition">
                            <i class="fas fa-camera text-gray-600 text-xs"></i>
                        </button>
                    </div>
                    
                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-blue-100">{{ $user->email }}</p>
                    </div>
                </div>
                
                <!-- Profile Completion -->
                @php
                    $completion = 0;
                    $fields = [
                        'name' => !empty($user->name),
                        'email' => !empty($user->email),
                        'avatar' => !empty($user->avatar),
                        'bio' => !empty($user->bio),
                        'phone' => !empty($user->phone),
                        'birth_date' => !empty($user->birth_date),
                    ];
                    
                    if($user->user_type === 'teacher') {
                        $fields['school_name'] = !empty($user->school_name);
                        $fields['subjects_taught'] = !empty($user->subjects_taught);
                        $fields['experience_years'] = !empty($user->experience_years);
                    } elseif($user->user_type === 'student') {
                        $fields['grade_level'] = !empty($user->grade_level);
                        $fields['favorite_subject'] = !empty($user->favorite_subject);
                    }
                    
                    $completed = array_sum($fields);
                    $total = count($fields);
                    $completion = round(($completed / $total) * 100);
                @endphp
                
                <div class="text-left">
                    <div class="text-sm text-blue-100 mb-1">اكتمال الملف الشخصي</div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <div class="w-32 bg-white/30 rounded-full h-2">
                            <div class="bg-white rounded-full h-2 transition-all duration-500" 
                                 style="width: {{ $completion }}%"></div>
                        </div>
                        <span class="text-white font-bold">{{ $completion }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-gray-200 shadow-sm">
            <nav class="flex space-x-8 space-x-reverse px-6">
                <button @click="activeTab = 'personal'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'personal' }"
                        class="py-4 px-1 border-b-2 border-transparent font-medium text-sm hover:text-blue-600 hover:border-blue-300 transition">
                    <i class="fas fa-user ml-2"></i>المعلومات الشخصية
                </button>
                
                <button @click="activeTab = 'avatar'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'avatar' }"
                        class="py-4 px-1 border-b-2 border-transparent font-medium text-sm hover:text-blue-600 hover:border-blue-300 transition">
                    <i class="fas fa-image ml-2"></i>الصورة الشخصية
                </button>
                
                @if($user->user_type === 'teacher')
                <button @click="activeTab = 'professional'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'professional' }"
                        class="py-4 px-1 border-b-2 border-transparent font-medium text-sm hover:text-blue-600 hover:border-blue-300 transition">
                    <i class="fas fa-briefcase ml-2"></i>المعلومات المهنية
                </button>
                @endif
                
                @if($user->user_type === 'student')
                <button @click="activeTab = 'academic'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'academic' }"
                        class="py-4 px-1 border-b-2 border-transparent font-medium text-sm hover:text-blue-600 hover:border-blue-300 transition">
                    <i class="fas fa-graduation-cap ml-2"></i>المعلومات الأكاديمية
                </button>
                @endif
                
                <button @click="activeTab = 'security'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'security' }"
                        class="py-4 px-1 border-b-2 border-transparent font-medium text-sm hover:text-blue-600 hover:border-blue-300 transition">
                    <i class="fas fa-lock ml-2"></i>الأمان
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="bg-white shadow-xl rounded-b-lg">
            
            <!-- Personal Information Tab -->
            <div x-show="activeTab === 'personal'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">المعلومات الشخصية الأساسية</h3>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" value="الاسم الكامل" />
                            <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                {{ $user->name }}
                            </div>
                            <p class="mt-1 text-xs text-gray-500">لا يمكن تعديل الاسم</p>
                        </div>

                        <div>
                            <x-input-label for="email" value="البريد الإلكتروني" />
                            <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700 flex items-center justify-between">
                                <span>{{ $user->email }}</span>
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        <i class="fas fa-check-circle ml-1"></i>محقق
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">لا يمكن تعديل البريد الإلكتروني</p>
                            
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-red-600">
                                        بريدك الإلكتروني غير مُحقق.
                                        <button form="send-verification" 
                                            class="underline text-red-600 hover:text-red-800">
                                            إعادة إرسال رسالة التحقق
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="phone" value="رقم الهاتف" />
                            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" 
                                :value="old('phone', $user->phone)" placeholder="05xxxxxxxx" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="birth_date" value="تاريخ الميلاد" />
                            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" 
                                :value="old('birth_date', $user->birth_date)" />
                            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="bio" value="نبذة شخصية" />
                        <textarea id="bio" name="bio" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="اكتب نبذة مختصرة عن نفسك...">{{ old('bio', $user->bio) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">اكتب نبذة مختصرة تعبر عنك (اختياري)</p>
                        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            <i class="fas fa-save ml-2"></i>حفظ التغييرات
                        </x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition
                               x-init="setTimeout(() => show = false, 3000)"
                               class="text-sm text-green-600 flex items-center">
                                <i class="fas fa-check-circle ml-2"></i>تم حفظ التغييرات بنجاح
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Avatar Tab -->
            <div x-show="activeTab === 'avatar'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">تحديث الصورة الشخصية</h3>
                
                <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="flex flex-col items-center space-y-4">
                        <!-- Current Avatar Preview -->
                        <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                            @if($user->avatar)
                                @if(str_starts_with($user->avatar, 'http'))
                                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                            @else
                                <span class="text-4xl text-gray-400 font-bold">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Upload Input -->
                        <div class="w-full max-w-md">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                                اختيار صورة جديدة
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG أو GIF. الحد الأقصى 2MB</p>
                            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                        </div>
                    </div>

                    <div class="flex justify-center gap-4">
                        <x-primary-button>
                            <i class="fas fa-upload ml-2"></i>تحديث الصورة
                        </x-primary-button>
                        
                        @if($user->avatar)
                            <button type="submit" name="remove_avatar" value="1"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                <i class="fas fa-trash ml-2"></i>إزالة الصورة
                            </button>
                        @endif
                    </div>

                    @if (session('status') === 'avatar-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-center text-sm text-green-600 flex items-center justify-center">
                            <i class="fas fa-check-circle ml-2"></i>تم تحديث الصورة بنجاح
                        </p>
                    @endif
                </form>
            </div>

            <!-- Professional Information Tab (Teachers Only) -->
            @if($user->user_type === 'teacher')
            <div x-show="activeTab === 'professional'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">المعلومات المهنية</h3>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="school_name" value="اسم المدرسة" />
                            <x-text-input id="school_name" name="school_name" type="text" class="mt-1 block w-full" 
                                :value="old('school_name', $user->school_name)" />
                            <x-input-error class="mt-2" :messages="$errors->get('school_name')" />
                        </div>

                        <div>
                            <x-input-label for="experience_years" value="سنوات الخبرة" />
                            <select id="experience_years" name="experience_years" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">اختر سنوات الخبرة</option>
                                @for($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ $user->experience_years == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'سنة' : 'سنوات' }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('experience_years')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="subjects_taught" value="المواد التي تُدرسها" />
                        <x-text-input id="subjects_taught" name="subjects_taught" type="text" class="mt-1 block w-full" 
                            :value="old('subjects_taught', $user->subjects_taught)" 
                            placeholder="مثال: الرياضيات، العلوم، اللغة العربية" />
                        <p class="mt-1 text-sm text-gray-500">اكتب المواد مفصولة بفواصل</p>
                        <x-input-error class="mt-2" :messages="$errors->get('subjects_taught')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            <i class="fas fa-save ml-2"></i>حفظ المعلومات المهنية
                        </x-primary-button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Academic Information Tab (Students Only) -->
            @if($user->user_type === 'student')
            <div x-show="activeTab === 'academic'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">المعلومات الأكاديمية</h3>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="grade_level" value="المرحلة الدراسية" />
                            <select id="grade_level" name="grade_level" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">اختر المرحلة</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $user->grade_level == $i ? 'selected' : '' }}>
                                        الصف {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
                        </div>

                        <div>
                            <x-input-label for="favorite_subject" value="المادة المفضلة" />
                            <x-text-input id="favorite_subject" name="favorite_subject" type="text" class="mt-1 block w-full" 
                                :value="old('favorite_subject', $user->favorite_subject)" 
                                placeholder="مثال: الرياضيات" />
                            <x-input-error class="mt-2" :messages="$errors->get('favorite_subject')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            <i class="fas fa-save ml-2"></i>حفظ المعلومات الأكاديمية
                        </x-primary-button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" class="p-6 space-y-8">
                
                <!-- Update Password -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">تغيير كلمة المرور</h3>
                    @include('profile.partials.update-password-form')
                </div>

                <hr class="border-gray-200">

                <!-- Active Sessions -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">الجلسات النشطة</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <i class="fas fa-info-circle text-yellow-400 ml-3 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">معلومات الجلسة الحالية</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    أنت متصل حاليًا من هذا الجهاز. يمكنك تسجيل الخروج من جميع الأجهزة الأخرى للأمان.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="post" action="{{ route('profile.logout-other-devices') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-input-label for="logout_password" value="كلمة المرور للتأكيد" />
                            <x-text-input id="logout_password" name="password" type="password" class="mt-1 block w-full" 
                                placeholder="أدخل كلمة المرور الحالية" />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <x-primary-button>
                            <i class="fas fa-sign-out-alt ml-2"></i>تسجيل الخروج من الأجهزة الأخرى
                        </x-primary-button>

                        @if (session('status') === 'other-devices-logged-out')
                            <p x-data="{ show: true }" x-show="show" x-transition
                               x-init="setTimeout(() => show = false, 3000)"
                               class="text-sm text-green-600 flex items-center">
                                <i class="fas fa-check-circle ml-2"></i>تم تسجيل الخروج من جميع الأجهزة الأخرى
                            </p>
                        @endif
                    </form>
                </div>

                <hr class="border-gray-200">

                <!-- Delete Account -->
                <div>
                    <h3 class="text-lg font-medium text-red-900 mb-6">حذف الحساب</h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Verification Form -->
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
<form method="post" action="{{ route('verification.send') }}" id="send-verification">
    @csrf
</form>
@endif

@endsection