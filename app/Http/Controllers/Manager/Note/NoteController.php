<?php

namespace App\Http\Controllers\Manager\Note;

use App\Models\Note;
use App\Repository\Permission;
use App\Http\Controllers\Manager\Controller;
use App\Models\Audit;
use App\Models\User;

class NoteController extends Controller
{
    public function indexUser() // index note for user
    {
        $this->authorize('manager.note.user.view');

        $employee = Permission::findUsersHasPermission('manager.dashboard.access');

        $notes = Note::whereHas('notable')
            ->where('notable_type', User::class)
            ->where('content', '<>', '')
            ->with('notable')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate();

        return view('dashboard.note.user', [
            'notes' => $notes,
            'employee' => $employee
        ]);
    }
}
