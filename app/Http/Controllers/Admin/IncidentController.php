<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'open');

        if (!in_array($status, ['open', 'resolved', 'all'], true)) {
            $status = 'open';
        }

        $incidents = Incident::query()
            ->with([
                'user:id,name,surname,nickname,role',
            ])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.incidents.index', [
            'incidents' => $incidents,
            'status' => $status,
        ]);
    }

    public function resolve(Incident $incident): RedirectResponse
    {
        $incident->status = 'resolved';
        $incident->save();

        return back()->with('success', 'Incidencia cerrada correctamente.');
    }
}