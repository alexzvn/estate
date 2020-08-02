<?php

namespace App\Http\Controllers\Manager\Activity;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Models\Log;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.customer.log.view');

        return view('dashboard.log.list', [
            'logs' => Log::with('user')->filterRequest($request)->latest()->paginate(40)
        ]);
    }
}
