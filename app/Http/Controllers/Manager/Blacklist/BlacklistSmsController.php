<?php

namespace App\Http\Controllers\Manager\Blacklist;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Models\Blacklist;

class BlacklistSmsController extends Controller
{
    public function increase(Blacklist $blacklist)
    {
        $this->authorize('blacklist.phone.sms');

        $blacklist->sms_count += 1;

        $blacklist->sms_history ??= [];

        $blacklist->sms_history = [
            ...$blacklist->sms_history,
            (string) now()
        ];

        $blacklist->save();

        return back()->with('success', "Đã tăng lượt nhắn số $blacklist->phone");
    }

    public function fetch(string $id)
    {
        $phone = Blacklist::findOrFail($id);

        return response([
            'count' => $phone->sms_count ?? 0,
            'history' => $phone->sms_history ?? []
        ]);
    }
}
