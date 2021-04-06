<?php

namespace App\Http\Controllers\Manager\Whitelist;

use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Whitelist\Phone\StorePhone;
use App\Http\Requests\Manager\whitelist\Phone\UpdatePhone;
use App\Models\Whitelist;
use Illuminate\Http\Request;

class WhitelistController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('whitelist.phone.view');

        return view('dashboard.whitelist.index', [
            'whitelist' => Whitelist::latest()->with('user')->filter($request)->paginate(40)
        ]);
    }

    public function store(StorePhone $request, Whitelist $whitelist)
    {
        $phones = collect(explode(',', $request->phone));

        $phones = $phones->map(function (string $phone)
        {
            return trim($phone);
        });

        $phones->each(function ($phone) use ($whitelist, $request)
        {
            $whitelist = $whitelist->model();

            $whitelist->forceFill([
                'phone' => $phone,
                'user_id' => user()->id,
            ])->save();

            if ($request->note) {
                $whitelist->writeNote($request->note);
            }
        });

        return back()->with('success', "Đã chặn số $whitelist->phone");
    }

    public function update(string $id, UpdatePhone $request)
    {
        $whitelist = Whitelist::findOrFail($id);

        if ($request->note) {
            $whitelist->writeNote($request->note);
        }

        return back()->with('success', 'Cập nhật thành công');
    }

    public function delete(string $id)
    {
        $this->authorize('whitelist.phone.delete');

        Whitelist::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa số này ra khỏi danh sách trắng');
    }
}
