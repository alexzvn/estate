<?php

namespace App\Http\Controllers\Manager\Chat;

use App\Events\Chat\MessageCreated;
use App\Http\Controllers\Manager\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        return view('dashboard.chat.index', [
            'conversations' => Conversation::newest()->with(['sender', 'topic', 'message'])->limit(100)->get()
        ]);
    }

    public function messages(User $user)
    {
        return new ResourceCollection(
            Message::whereTopic($user)->latest()->limit(50)->get()
        );
    }

    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $message = new Message(['content' => $request->message]);

        $message->sender()->associate(user());
        $message->topic()->associate($user);

        MessageCreated::dispatch(tap($message)->save());

        return response('ok');
    }
}
