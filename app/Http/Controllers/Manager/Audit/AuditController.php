<?php

namespace App\Http\Controllers\Manager\Audit;

use App\Models\Audit;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Repository\Permission;
use App\Repository\User;
use Illuminate\Support\Carbon;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.audit.view');

        $getId = function ($model) { return $model->id; };

        $user = Permission::findUsersHasPermission('manager.dashboard.access');

        $user = User::filter($request)->whereIn('_id', $user->modelKeys())->get();

        $audit = Audit::with(['user', 'auditable'])
            ->whereIn('user_id', $user->modelKeys())
            ->filter($request)
            ->latest();

        return view('dashboard.audit.list', [
            'audits' => $audit->paginate(30),
            'users'  => $user
        ]);
    }
}
