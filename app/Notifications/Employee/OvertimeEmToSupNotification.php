<?php

namespace App\Notifications\Employee;

use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OvertimeEmToSupNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $employee = Employee::where('user_id', $this->data['user_id'])->first();
        return (new MailMessage)
                    ->from('hris@no_reply', 'HRIS')
                    ->subject('Overtime Request')
                    ->greeting('Overtime Request')
                    ->line('Employee: ' . $employee->first_name . ' ' . $employee->last_name)
                    ->line('Date: ' . $this->data['date'])
                    ->line('From: ' . $this->data['from'])
                    ->line('To: ' . $this->data['to'])
                    ->line('Reason: ' . $this->data['reason']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
