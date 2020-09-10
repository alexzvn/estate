<?php

namespace App\Http\Controllers\Manager\Report;

use App\Repository\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.report.view');

        return view('dashboard.report.list', [
            'reports' => Report::with(['user', 'post'])
                ->filter($request)->latest()->paginate(40)
        ]);
    }

    public function delete(string $id, Report $report)
    {
        $this->authorize('manager.post.report.delete');

        $report->findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa thông tin báo môi giới này');
    }
}
