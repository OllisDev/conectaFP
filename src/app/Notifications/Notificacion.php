<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Notificacion extends Notification
{
    use Queueable;

    private $mensaje;
    private $datos;
    public function __construct($mensaje, $datos = [])
    {
        $this->mensaje = $mensaje;
        $this->datos = $datos;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => $this->mensaje,
            'datos' => $this->datos,
        ];
    }

}
