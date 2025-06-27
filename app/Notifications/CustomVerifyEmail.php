<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('تفعيل حسابك في منصة جُذور - خطوة واحدة فقط! 🚀')
            ->greeting('مرحباً ' . $notifiable->name . '! 👋')
            ->line('**شكراً لانضمامك إلى منصة جُذور** - منصة التعليم الذكي الرائدة في المنطقة!')
            ->line('🎉 **أنت على بُعد خطوة واحدة من بدء رحلتك التعليمية المميزة**')
            ->line('لإكمال تسجيلك والبدء في استخدام جميع ميزات المنصة، يرجى تفعيل حسابك بالنقر على الزر أدناه:')
            ->action('✨ تفعيل حسابي الآن', $verificationUrl)
            ->line('---')
            ->line('**🌟 ما ينتظرك في منصة جُذور:**')
            ->line('🤖 **إنشاء اختبارات ذكية** باستخدام أحدث تقنيات الذكاء الاصطناعي')
            ->line('📊 **تحليل شامل للنتائج** وفقاً لنموذج الجُذور الأربعة الفريد')
            ->line('🌐 **واجهة سهلة الاستخدام** مصممة خصيصاً للمعلمين العرب')
            ->line('📱 **إمكانية الوصول من أي مكان** عبر الهاتف أو الحاسوب')
            ->line('🎯 **نتائج فورية ومفصلة** تساعد في تطوير العملية التعليمية')
            ->line('---')
            ->line('**⚠️ مهم:** هذا الرابط صالح لمدة **60 دقيقة فقط** من وقت إرسال هذه الرسالة.')
            ->line('إذا واجهت أي مشكلة في النقر على الزر، يمكنك نسخ الرابط التالي ولصقه في متصفحك:')
            ->line('`' . $verificationUrl . '`')
            ->line('---')
            ->line('إذا لم تقم بإنشاء حساب في منصة جُذور، يرجى **تجاهل هذه الرسالة** تماماً.')
            ->line('**🤝 هل تحتاج مساعدة؟** تواصل معنا عبر البريد الإلكتروني: support@iseraj.com')
            ->salutation("**مع أطيب التحيات،**\n**فريق منصة جُذور** 🌱\n*التعليم الذكي في خدمة المستقبل*");
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}