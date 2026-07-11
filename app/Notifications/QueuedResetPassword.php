<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Password-reset notification sent through the queue so the SMTP round-trip
 * no longer blocks the HTTP request. Extends the framework notification, so the
 * reset URL customization registered in AppServiceProvider still applies.
 */
class QueuedResetPassword extends BaseResetPassword implements ShouldQueue
{
    use Queueable;
}
