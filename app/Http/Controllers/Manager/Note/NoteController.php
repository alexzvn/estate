<?php

namespace App\Http\Controllers\Manager\Note;

use App\Models\Note;
use App\Repository\Permission;
use App\Http\Controllers\Manager\Controller;
use App\Models\Audit;

class NoteController extends Controller
{
    public function indexUser() // index note for user
    {
        $this->authorize('manager.notes.user.view');

        $employee = Permission::findUsersHasPermission('manager.user.view');

        $notes = Audit::where('auditable_type', Note::class)
            ->filter(request())
            ->latest()
            ->with(['user', 'auditable.user'])
            ->paginate();

        return view('dashboard.note.user', [
            'notes' => $notes,
            'employee' => $employee
        ]);
    }
}
