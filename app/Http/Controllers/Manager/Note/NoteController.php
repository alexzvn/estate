<?php

namespace App\Http\Controllers\Manager\Note;

use App\Models\Note;
use App\Repository\Permission;
use App\Http\Controllers\Manager\Controller;

class NoteController extends Controller
{
    public function indexUser(Note $note) // index note for user
    {
        $this->authorize('manager.notes.user.view');

        $employee = Permission::findUsersHasPermission('manager.user.view');

        $note = $note
            ->whereHas('user')
            ->whereHas('adder')
            ->with(['user', 'adder'])
            ->latest()
            ->paginate();

        return view('dashboard.note.user', [
            'notes' => $note,
            'employee' => $employee
        ]);
    }
}
