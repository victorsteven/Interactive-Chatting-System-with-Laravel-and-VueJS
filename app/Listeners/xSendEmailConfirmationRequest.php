<?php

namespace App\Listeners;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailConfirmationRequest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //the user is from the Registered event class

        \Mail::to($event->user)->send(new PleaseConfirmYourEmail($event->user));
    }
}
