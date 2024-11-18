<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    private $task;
    private $action;

    use Queueable;

    /**
     * Crée une nouvelle instance de notification.
     */
    public function __construct($task, $action)
    {
        $this->task = $task;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Nous envoyons la notification uniquement à la base de données
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,               // ID de la tâche
            'task_title' => $this->task->title,         // Titre de la tâche
            'action' => $this->action, 
            'performed_at' => now(),                    // Date et heure de l'action
        ];
    }
}
