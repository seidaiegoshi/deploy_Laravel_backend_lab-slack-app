<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelPolicy
{
    use HandlesAuthorization;


    public function show(User $user, Channel $channel)
    {
        return $channel->users->contains('id', $user->id);
    }
}
