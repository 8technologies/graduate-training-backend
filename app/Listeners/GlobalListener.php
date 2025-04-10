<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeNotification;
use App\Events\GlobalEvent;

class GlobalListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GlobalEvent $event): void
    {
        Notification::route('mail', [
            $event->email => 'GradTrak',
        ])->notify(new WelcomeNotification($event->message, $event->subject));
    }
}
