<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Message;

class MessagePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Message $message)
    {
        return $message->user_id === $user->id;
    }
}
