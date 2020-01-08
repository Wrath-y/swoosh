<?php

namespace App\Events;

use App\Models\User;
use App\Listeners\RegisterListener;
use App\Listeners\Register1Listener;

class RegisterEvent
{
    public $listeners = [
        RegisterListener::class
    ];

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
