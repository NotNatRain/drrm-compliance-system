<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class VerifyCodeNotification extends Notification
{
    use Queueable;

    public $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $drrmLogoPath = public_path('images/drrmis-logo-2.png');
        $depedLogoPath = public_path('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png');
        $drrmLogoCid = 'drrm-logo@drrm-compliance';
        $depedLogoCid = 'deped-logo@drrm-compliance';

        return (new MailMessage)
            ->subject('User verification — Password reset code')
            ->view('mail.user-verification-code-html', [
                'code' => (string) $this->code,
                'recipientEmail' => $notifiable->email,
                'verifyUrl' => route('password.verify-form', ['email' => $notifiable->email]),
                'drrmLogoCid' => $drrmLogoCid,
                'depedLogoCid' => $depedLogoCid,
            ])
            ->withSymfonyMessage(function (Email $message) use ($drrmLogoPath, $depedLogoPath, $drrmLogoCid, $depedLogoCid) {
                if (is_file($drrmLogoPath)) {
                    $part = DataPart::fromPath($drrmLogoPath, 'drrmis-logo-2.png')
                        ->asInline()
                        ->setContentId($drrmLogoCid);
                    $message->addPart($part);
                }

                if (is_file($depedLogoPath)) {
                    $part = DataPart::fromPath($depedLogoPath, 'deped-logo.png')
                        ->asInline()
                        ->setContentId($depedLogoCid);
                    $message->addPart($part);
                }
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
