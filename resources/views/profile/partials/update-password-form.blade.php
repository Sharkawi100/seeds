<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            <i class="fas fa-lock ml-2"></i>
            {{ __('تحديث كلمة المرور') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('تأكد من استخدام كلمة مرور طويلة وعشوائية للحفاظ على أمان حسابك.') }}
        </p>
    </header>

    @if(auth()->user()->auth_provider !== 'email')
        <!-- Social Login Users Notice -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        تسجيل الدخول عبر {{ auth()->user()->auth_provider == 'google' ? 'جوجل' : 'فيسبوك' }}
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            لقد قمت بالتسجيل في منصة جُذور باستخدام حساب 
                            @if(auth()->user()->auth_provider == 'google')
                                <span class="font-semibold">Google</span>
                            @else
                                <span class="font-semibold">Facebook</span>
                            @endif
                            الخاص بك. لذلك، لا تحتاج إلى كلمة مرور منفصلة لهذا الحساب.
                        </p>
                        <p class="mt-2">
                            يتم إدارة أمان حسابك من خلال مزود الخدمة الخاص بك. إذا كنت تريد تحديث إعدادات الأمان، يرجى زيارة:
                        </p>
                        <ul class="mt-2 list-disc list-inside">
                            @if(auth()->user()->auth_provider == 'google')
                                <li>
                                    <a href="https://myaccount.google.com/security" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                        إعدادات أمان Google
                                        <i class="fas fa-external-link-alt text-xs mr-1"></i>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="https://www.facebook.com/settings?tab=security" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                        إعدادات أمان Facebook
                                        <i class="fas fa-external-link-alt text-xs mr-1"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Option to Set Password -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-800 mb-2">
                <i class="fas fa-info-circle ml-2"></i>
                هل تريد إضافة كلمة مرور؟
            </h4>
            <p class="text-sm text-blue-700 mb-3">
                يمكنك إضافة كلمة مرور لحسابك إذا كنت تريد خيار تسجيل الدخول بالبريد الإلكتروني أيضاً.
            </p>
            <button type="button" 
                    x-data=""
                    @click="$dispatch('open-modal', 'set-password-modal')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus-circle ml-2"></i>
                إضافة كلمة مرور
            </button>
        </div>

        <!-- Set Password Modal -->
        <x-modal name="set-password-modal" :show="false" focusable>
            <form method="post" action="{{ route('password.update') }}" class="p-6">
                @csrf
                @method('put')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('إضافة كلمة مرور لحسابك') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('بإضافة كلمة مرور، ستتمكن من تسجيل الدخول باستخدام البريد الإلكتروني وكلمة المرور بالإضافة إلى ') . (auth()->user()->auth_provider == 'google' ? 'Google' : 'Facebook') . '.' }}
                </p>

                <!-- New Password -->
                <div class="mt-6">
                    <x-input-label for="new_password" :value="__('كلمة المرور الجديدة')" />
                    <x-text-input id="new_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="new_password_confirmation" :value="__('تأكيد كلمة المرور')" />
                    <x-text-input id="new_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('إلغاء') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('إضافة كلمة المرور') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

    @else
        <!-- Regular Email/Password Users Form -->
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <!-- Password Strength Indicator -->
            <div x-data="passwordStrengthChecker()" class="space-y-6">
                <!-- Current Password -->
                <div>
                    <x-input-label for="update_password_current_password" :value="__('كلمة المرور الحالية')" />
                    <div class="relative">
                        <x-text-input id="update_password_current_password" 
                                    name="current_password" 
                                    type="password" 
                                    class="mt-1 block w-full pl-10" 
                                    autocomplete="current-password" />
                        <button type="button" 
                                @click="togglePassword('update_password_current_password')"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="update_password_current_password_icon"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div>
                    <x-input-label for="update_password_password" :value="__('كلمة المرور الجديدة')" />
                    <div class="relative">
                        <x-text-input id="update_password_password" 
                                    name="password" 
                                    type="password" 
                                    class="mt-1 block w-full pl-10" 
                                    autocomplete="new-password"
                                    @input="checkStrength($event.target.value)" />
                        <button type="button" 
                                @click="togglePassword('update_password_password')"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="update_password_password_icon"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Bar -->
                    <div class="mt-2" x-show="password.length > 0">
                        <div class="flex gap-1 mb-1">
                            <div class="h-2 flex-1 rounded transition-all duration-300"
                                 :class="strength >= 1 ? strengthColors[strength] : 'bg-gray-300'"></div>
                            <div class="h-2 flex-1 rounded transition-all duration-300"
                                 :class="strength >= 2 ? strengthColors[strength] : 'bg-gray-300'"></div>
                            <div class="h-2 flex-1 rounded transition-all duration-300"
                                 :class="strength >= 3 ? strengthColors[strength] : 'bg-gray-300'"></div>
                            <div class="h-2 flex-1 rounded transition-all duration-300"
                                 :class="strength >= 4 ? strengthColors[strength] : 'bg-gray-300'"></div>
                        </div>
                        <p class="text-sm" :class="strengthTextColors[strength]" x-text="strengthText"></p>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="mt-2 text-sm text-gray-600" x-show="password.length > 0">
                        <p class="font-semibold mb-1">متطلبات كلمة المرور:</p>
                        <ul class="space-y-1">
                            <li :class="requirements.length ? 'text-green-600' : 'text-gray-400'">
                                <i :class="requirements.length ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                8 أحرف على الأقل
                            </li>
                            <li :class="requirements.uppercase ? 'text-green-600' : 'text-gray-400'">
                                <i :class="requirements.uppercase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                حرف كبير واحد على الأقل
                            </li>
                            <li :class="requirements.lowercase ? 'text-green-600' : 'text-gray-400'">
                                <i :class="requirements.lowercase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                حرف صغير واحد على الأقل
                            </li>
                            <li :class="requirements.number ? 'text-green-600' : 'text-gray-400'">
                                <i :class="requirements.number ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                رقم واحد على الأقل
                            </li>
                            <li :class="requirements.special ? 'text-green-600' : 'text-gray-400'">
                                <i :class="requirements.special ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                رمز خاص واحد على الأقل (!@#$%^&*)
                            </li>
                        </ul>
                    </div>
                    
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('تأكيد كلمة المرور')" />
                    <div class="relative">
                        <x-text-input id="update_password_password_confirmation" 
                                    name="password_confirmation" 
                                    type="password" 
                                    class="mt-1 block w-full pl-10" 
                                    autocomplete="new-password" />
                        <button type="button" 
                                @click="togglePassword('update_password_password_confirmation')"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="update_password_password_confirmation_icon"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <!-- Last Password Change Info -->
            @if(auth()->user()->password_changed_at)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle ml-2"></i>
                        آخر تغيير لكلمة المرور: 
                        <span class="font-semibold">{{ auth()->user()->password_changed_at->diffForHumans() }}</span>
                        ({{ auth()->user()->password_changed_at->format('Y-m-d H:i') }})
                    </p>
                </div>
            @endif

            <!-- Admin Force Password Change Notice -->
            @if(auth()->user()->force_password_change)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        <span class="font-semibold">تنبيه:</span> يجب عليك تغيير كلمة المرور الخاصة بك لأسباب أمنية.
                    </p>
                </div>
            @endif

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('حفظ التغييرات') }}</x-primary-button>

                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 flex items-center">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ __('تم حفظ كلمة المرور بنجاح.') }}
                    </p>
                @endif
            </div>
        </form>

        <!-- Additional Security Options -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-md font-medium text-gray-900 mb-4">
                <i class="fas fa-shield-alt ml-2"></i>
                خيارات الأمان الإضافية
            </h3>
            
            <div class="space-y-4">
                <!-- Logout Other Devices -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">تسجيل الخروج من الأجهزة الأخرى</p>
                        <p class="text-sm text-gray-500">قم بتسجيل الخروج من جميع الأجهزة الأخرى المتصلة بحسابك.</p>
                    </div>
                    <button type="button" 
                            x-data=""
                            @click="$dispatch('open-modal', 'logout-other-devices')"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
                    </button>
                </div>

                <!-- Two Factor Authentication (Coming Soon) -->
                <div class="flex items-center justify-between opacity-50">
                    <div>
                        <p class="text-sm font-medium text-gray-700">التحقق بخطوتين</p>
                        <p class="text-sm text-gray-500">أضف طبقة حماية إضافية لحسابك.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        قريباً
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Logout Other Devices Modal -->
    <x-modal name="logout-other-devices" :show="false" focusable>
        <form method="post" action="{{ route('profile.logout-other-devices') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('تسجيل الخروج من الأجهزة الأخرى') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('سيتم تسجيل خروجك من جميع الأجهزة الأخرى المتصلة بحسابك. ستحتاج إلى تسجيل الدخول مرة أخرى على تلك الأجهزة.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password_logout_devices" value="{{ __('كلمة المرور الحالية') }}" class="sr-only" />
                <x-text-input
                    id="password_logout_devices"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('كلمة المرور الحالية') }}"
                />
                <x-input-error :messages="$errors->logoutOtherDevices->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('إلغاء') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('تسجيل الخروج من الأجهزة الأخرى') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<script>
function passwordStrengthChecker() {
    return {
        password: '',
        strength: 0,
        strengthText: '',
        strengthColors: {
            0: 'bg-gray-300',
            1: 'bg-red-500',
            2: 'bg-orange-500',
            3: 'bg-yellow-500',
            4: 'bg-green-500'
        },
        strengthTextColors: {
            0: 'text-gray-500',
            1: 'text-red-600',
            2: 'text-orange-600',
            3: 'text-yellow-600',
            4: 'text-green-600'
        },
        requirements: {
            length: false,
            uppercase: false,
            lowercase: false,
            number: false,
            special: false
        },
        
        checkStrength(value) {
            this.password = value;
            let score = 0;
            
            // Check requirements
            this.requirements.length = value.length >= 8;
            this.requirements.uppercase = /[A-Z]/.test(value);
            this.requirements.lowercase = /[a-z]/.test(value);
            this.requirements.number = /[0-9]/.test(value);
            this.requirements.special = /[!@#$%^&*()\-_=+{};:,<.>]/.test(value);
            
            // Calculate score
            if (this.requirements.length) score++;
            if (this.requirements.uppercase && this.requirements.lowercase) score++;
            if (this.requirements.number) score++;
            if (this.requirements.special) score++;
            
            this.strength = score;
            
            // Set strength text
            const strengthTexts = [
                'أدخل كلمة مرور',
                'كلمة مرور ضعيفة',
                'كلمة مرور مقبولة',
                'كلمة مرور جيدة',
                'كلمة مرور قوية'
            ];
            
            this.strengthText = strengthTexts[score];
        },
        
        togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }
}
</script>