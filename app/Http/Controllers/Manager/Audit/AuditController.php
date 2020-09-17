<?php

namespace App\Http\Controllers\Manager\Audit;

use App\Models\Audit;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Repository\Permission;
use App\Repository\User;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.audit.view');

        $user = Permission::findUsersHasPermission('manager.dashboard.access')
            ->map(function ($user)
            {
                return $user->id;
            });

        $user = User::filter($request)->whereIn('_id', $user->toArray())->get();

        $user = $user->map(function ($user)
        {
            return $user->id;
        });

        $audit = Audit::with(['user', 'auditable'])
            ->whereIn('user_id', $user->toArray())
            ->latest();

        return view('dashboard.audit.list', [
            'audits' => $audit->paginate(30)
        ]);
    }
}
