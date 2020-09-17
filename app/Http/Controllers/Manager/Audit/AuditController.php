<?php

namespace App\Http\Controllers\Manager\Audit;

use App\Models\Audit;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;

class AuditController extends Controller
{
    public function index()
    {
        $this->authorize('manager.audit.view');

        return view('dashboard.audit.list', [
            'audits' => Audit::with(['user', 'auditable'])->latest()->paginate(30)
        ]);
    }
}
