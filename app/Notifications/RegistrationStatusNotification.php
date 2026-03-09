<?php
// ============================================================
// app/Notifications/RegistrationStatusNotification.php
// ============================================================
namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign,
        public string $status   // 'accepted' or 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isAccepted = $this->status === 'accepted';

        $mail = (new MailMessage)
            ->subject($isAccepted
                ? '🎉 You\'ve been accepted for: ' . $this->campaign->title
                : 'Update on your registration: ' . $this->campaign->title
            )
            ->greeting('Hello ' . $notifiable->name . '!');

        if ($isAccepted) {
            $mail->line('Great news! Your registration for the blood donation campaign has been **accepted**.')
                 ->line('**Campaign:** ' . $this->campaign->title)
                 ->line('**Date:** ' . $this->campaign->campaign_date->format('l, F j, Y \a\t H:i'))
                 ->line('**Location:** ' . $this->campaign->location)
                 ->line('Please arrive 15 minutes before the scheduled time.')
                 ->action('View Campaign', url('/campaigns/' . $this->campaign->id))
                 ->line('Thank you for saving lives! 🩸');
        } else {
            $mail->line('Unfortunately, your registration for the following campaign could not be accepted at this time.')
                 ->line('**Campaign:** ' . $this->campaign->title)
                 ->line('This may be due to capacity limits or eligibility criteria.')
                 ->action('View Other Campaigns', url('/campaigns'))
                 ->line('We hope to see you in a future campaign!');
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'registration_status',
            'status'        => $this->status,
            'campaign_id'   => $this->campaign->id,
            'campaign_title'=> $this->campaign->title,
            'message'       => $this->status === 'accepted'
                ? 'Your registration for "' . $this->campaign->title . '" has been accepted!'
                : 'Your registration for "' . $this->campaign->title . '" was not accepted.',
        ];
    }
}

// ============================================================
// app/Notifications/DonationReminderNotification.php
// ============================================================
// namespace App\Notifications;
// ...
// (Shown below as separate section)
