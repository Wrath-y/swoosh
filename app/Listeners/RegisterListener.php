<?php

namespace App\Listeners;

use App\Events\RegisterEvent;

class RegisterListener
{
    public $is_coroutine;

    /**
     * Handle the event.
     *
     * @param  RegisterEvent  $event
     * @return void
     */
    public function handle(RegisterEvent $event)
    {
        print_r('excute listen handle');
    }
}
