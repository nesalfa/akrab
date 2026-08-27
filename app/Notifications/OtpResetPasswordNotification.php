<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OtpResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $otp,
        public readonly int $expiryMinutes,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode OTP Reset Kata Sandi - AKRAB')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk mereset kata sandi akun AKRAB kamu.')
            ->line('Masukkan kode berikut di halaman reset kata sandi:')
            ->line(new HtmlString(
                '<div style="font-size: 32px; font-weight: 700; letter-spacing: 8px; text-align: center; padding: 16px; background-color: #FFF0F5; border-radius: 12px; color: #6A4C93;">'
                . e($this->otp) .
                '</div>'
            ))
            ->line("Kode ini berlaku selama {$this->expiryMinutes} menit.")
            ->line('Kalau kamu tidak meminta reset kata sandi, abaikan email ini — akunmu tetap aman.');
    }
}
