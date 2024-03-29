<?php

namespace App\Http\Controllers\Manager\Blacklist;

use App\Exports\BlacklistExport;
use Illuminate\Http\Request;
use App\Repository\Blacklist;
use App\Repository\Location\Province;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Blacklist\Phone\StorePhone;
use App\Http\Requests\Manager\Blacklist\Phone\UpdatePhone;
use Maatwebsite\Excel\Facades\Excel;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('blacklist.phone.view');

        $blacklist = Blacklist::latest()->with([
            'user', 'posts', 'adder'
        ])->filter($request);

        if ($request->user) {
            $blacklist->whereNotNull('user_id');
        }

        return view('dashboard.blacklist.index', [
            'blacklist' => $blacklist->paginate(40),
            'provinces' => Province::active()->get()
        ]);
    }

    public function store(StorePhone $request, Blacklist $blacklist)
    {
        $phones = collect(explode(',', $request->phone));

        $phones = $phones->map(function (string $phone)
        {
            return trim($phone);
        });

        $phones->each(function ($phone) use ($blacklist, $request)
        {
            $blacklist = $blacklist->model();

            $blacklist->forceFill([
                'phone' => $phone,
                'user_id' => user()->id,
            ])->save();

            if ($request->note) {
                $blacklist->writeNote($request->note);
            }
        });

        return back()->with('success', "Đã chặn số $blacklist->phone");
    }

    public function exportExcel(Request $request)
    {
        $blacklists = Blacklist::latest()->filter($request);

        if ($request->user) {
            $blacklists->whereNotNull('user_id');
        }

        $blacklists = $blacklists->paginate(40)->reduce(function ($carry, $item) {
            return $carry->push($item);
        }, collect());

        $exporter = new BlacklistExport($blacklists);

        $exporter->increaseCount();

        return Excel::download($exporter, 'phone-' . now()->format('d-m-Y H-i') . '.xlsx' );
    }

    public function update(string $id, UpdatePhone $request)
    {
        $blacklist = Blacklist::findOrFail($id);

        if ($request->note) {
            $blacklist->writeNote($request->note);
        }

        return back()->with('success', 'Cập nhật thành công');
    }

    public function delete(string $id)
    {
        $this->authorize('blacklist.phone.delete');

        Blacklist::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa số này ra khỏi danh sách đen');
    }
}
