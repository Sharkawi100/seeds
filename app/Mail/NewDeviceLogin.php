<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDeviceLogin extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public UserLogin $login;

    public function __construct(User $user, UserLogin $login)
    {
        $this->user = $user;
        $this->login = $login;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تسجيل دخول من جهاز جديد - جُذور',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-device-login',
            with: [
                'userName' => $this->user->name,
                'loginTime' => $this->login->logged_in_at->format('Y-m-d H:i:s'),
                'ipAddress' => $this->login->ip_address,
                'location' => $this->login->location ?? 'غير محدد',
                'device' => $this->login->device_type,
                'browser' => $this->login->browser,
                'platform' => $this->login->platform,
            ]
        );
    }
}