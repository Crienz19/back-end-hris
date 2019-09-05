<?php

namespace App\Notifications\Employee;

use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TripEmToHrNotification extends Notification
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
                    ->from('hris@no_reply', 'HRIS Notification')
                    ->subject('Official Business Trip Request')
                    ->greeting('Official Business Trip Request')
                    ->line('Employee: ' . $employee->first_name . ' ' . $employee->last_name)
                    ->line('Date From: ' . $this->data['date_from'])
                    ->line('Date To: ' . $this->data['date_to'])
                    ->line('Time In: ' . $this->data['time_in'])
                    ->line('Time Out: ' . $this->data['time_out'])
                    ->line('Destination From: ' . $this->data['destination_from'])
                    ->line('Destination To: ' . $this->data['destination_from'])
                    ->line('Purpose: ' . $this->data['purpose']);


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
