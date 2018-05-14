<?php

namespace App\Listeners;

use App\Events\RefundReviewedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundReviewedListener
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
     * @param  RefundReviewedEvent  $event
     * @return void
     */
    public function handle(RefundReviewedEvent $event)
    {
        //
    }
}
