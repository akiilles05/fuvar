<?php

namespace App\Notifications;

use App\Models\Munka;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MunkaFailedNotification extends Notification
{

    public Munka $munka;

    /**
     * Create a new notification instance.
     */
    public function __construct(Munka $munka)
    {
        $this->munka = $munka;
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
        return (new MailMessage)
            ->subject('Munka sikertelen lett - #' . $this->munka->id)
            ->greeting('Figyelem!')
            ->line('Egy munka sikertelen státuszra változott.')
            ->line('**Munka részletei:**')
            ->line('Munka ID: ' . $this->munka->id)
            ->line('Kiindulási cím: ' . $this->munka->kiindulasi_cim)
            ->line('Érkezési cím: ' . $this->munka->erkezesi_cim)
            ->line('Címzett: ' . $this->munka->cimzett_neve)
            ->line('Fuvarozó: ' . ($this->munka->fuvarozo ? $this->munka->fuvarozo->nev : 'Nincs hozzárendelve'))
            ->action('Megtekintés', url('/admin/munkak'))
            ->line('Kérjük, vizsgálja meg a helyzetet!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'munka_id' => $this->munka->id,
            'message' => 'Munka sikertelen: #' . $this->munka->id,
        ];
    }
}
