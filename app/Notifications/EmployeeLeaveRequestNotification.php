<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeLeaveRequestNotification extends Notification
{
  use Queueable;

  protected $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function via($notifiable)
  {
    return ['mail'];
  }

  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('Leave Request')
      ->from($this->data['from'])
      ->line('Sample Leave Request Email Body Here!')
      ->line('Kind Regards!');
  }
}
