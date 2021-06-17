<?php

namespace App\Http\Controllers\Customer;

use App\Events\Chat\MessageCreated;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request, Message $message)
    {
        $attrs = $this->validate($request, [
            'content'    => 'required|string|max:255'
        ]);

        $message->forceFill($attrs);

        $message->sender()->associate(user());
        $message->topic()->associate(user());

        MessageCreated::dispatch(tap($message)->save());

        return $message->only(['id', 'content', 'sender']);
    }
}
