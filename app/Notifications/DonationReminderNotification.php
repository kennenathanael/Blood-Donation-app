<?php
// ============================================================
// app/Notifications/DonationReminderNotification.php
// ============================================================
namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Campaign $campaign) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🩸 Reminder: Blood Donation Campaign Tomorrow!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a friendly reminder about your upcoming blood donation.')
            ->line('**Campaign:** ' . $this->campaign->title)
            ->line('**Date:** ' . $this->campaign->campaign_date->format('l, F j, Y'))
            ->line('**Time:** ' . $this->campaign->campaign_date->format('H:i'))
            ->line('**Location:** ' . $this->campaign->location)
            ->line('**Address:** ' . ($this->campaign->address ?? 'See campaign details'))
            ->line('---')
            ->line('**What to bring:**')
            ->line('- Valid ID')
            ->line('- Drink plenty of water before donating')
            ->line('- Have a light meal before coming')
            ->action('View Campaign Details', url('/campaigns/' . $this->campaign->id))
            ->line('Thank you for your generous donation! 💙');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'donation_reminder',
            'campaign_id'   => $this->campaign->id,
            'campaign_title'=> $this->campaign->title,
            'campaign_date' => $this->campaign->campaign_date->toDateTimeString(),
            'message'       => 'Reminder: Your donation for "' . $this->campaign->title . '" is tomorrow!',
        ];
    }
}
