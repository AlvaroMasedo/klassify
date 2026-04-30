<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'open');

        if (!in_array($status, ['open', 'resolved', 'all'], true)) {
            $status = 'open';
        }

        $reports = Report::query()
            ->with([
                'reporter:id,name,surname,nickname',
                'resource:id,title',
                'comment',
            ])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'status' => $status,
        ]);
    }

    public function resolve(Report $report): RedirectResponse
    {
        $report->status = 'resolved';
        $report->save();

        return back()->with('success', 'Denuncia cerrada correctamente.');
    }
}