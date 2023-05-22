<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NotifyUser extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $actionUrl, $message, $subject, $passwordText;

     public function __construct($actionUrl, $message, $subject, $passwordText)
    {
        //
        $this->actionUrl = $actionUrl;
        $this->message = $message;
        $this->subject = $subject;
        $this->passwordText = $passwordText;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->subject)
                    ->line($this->message)
                    ->line($this->passwordText)
                    ->action("Proceed",$this->actionUrl)
                    ->line('Thank you for using our application!');
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

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'subject'=>$this->subject,
            'message' => $this->message,
            'passwordText' => $this->passwordText,
            'action' => url($this->actionUrl),
            
            ]);
    }
}
