<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Email que se envía la notificación de restablecimiento de contraseña.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablecer contraseña de Klassify')
            ->greeting('¡Hola!')
            ->line('Has solicitado restablecer tu contraseña. Haz clic en el botón de abajo para continuar.')
            ->action('Restablecer contraseña', $url)
            ->line('Este enlace de restablecimiento caducará en 60 minutos.')
            ->line('Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.')
            ->salutation('Saludos,'."\n".'El equipo de Klassify');
    }
}
