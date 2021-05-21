<?php

namespace App\Http\Controllers\Manager\Message;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Models\Message;
use App\Models\User;

class MessageApiController extends Controller
{
    public function store(Request $request, Message $message)
    {
        $attrs = $this->validate($request, [
            'content'    => 'required',
            'topic_type' => 'required|string',
            'topic_id'   => 'required|exists:'. $request->topic_type .',id'
        ]);

        $message->forceFill($attrs);

        $message->sender()->associate(user());

        return tap($message)->save();
    }
}
