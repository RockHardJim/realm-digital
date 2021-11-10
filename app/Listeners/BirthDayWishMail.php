<?php

namespace App\Listeners;

use App\Jobs\SendBirthDayWishMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BirthDayWishMail
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        dispatch(new SendBirthDayWishMail($event->data));
    }
}
