<?php

namespace App\Notifications\Employee;

use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveEmToSupNotification extends Notification
{
    use Queueable;

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
                    ->greeting('Leave Requests')
                    ->subject('Leave Request')
                    ->from('hris@no_reply','HRIS Notification')
                    ->line('Employee: ' . $employee->first_name . ' ' . $employee->last_name)
                    ->line('Type: ' . $this->data['type'])
                    ->line('Pay: ' . $this->data['pay_type'])
                    ->line('From: ' . $this->data['from'])
                    ->line('To: ' . $this->data['to'])
                    ->line($this->data['time_from'] ? 'Time From: ' . $this->data['time_from'] : 'Not Applicable')
                    ->line($this->data['time_to'] ? 'Time To: ' . $this->data['time_to'] : 'Not Applicable')
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
