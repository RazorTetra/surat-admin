<?php
// app/Http/Controllers/PendingLetterController.php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

class PendingLetterController extends Controller
{
    public function index(Request $request)
    {
        // Get pending incoming letters
        $incomingLetters = IncomingLetter::with(['receivedBy', 'attachments'])
            ->where('status', '!=', 'completed')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('sender', 'like', "%{$search}%");
                });
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            });

        // Get pending outgoing letters
        $outgoingLetters = OutgoingLetter::with(['createdBy', 'attachments'])
            ->whereIn('status', ['draft', 'review'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('recipient', 'like', "%{$search}%");
                });
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            });

        // Combine and paginate results
        $pendingLetters = $incomingLetters->union($outgoingLetters)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('PendingLetters/Index', [
            'letters' => $pendingLetters,
            'filters' => $request->only(['search', 'priority'])
        ]);
    }

    public function show(Request $request, string $id)
    {
        $type = $request->query('type');
        
        if (!in_array($type, ['incoming', 'outgoing'])) {
            abort(404);
        }

        $letter = $type === 'incoming'
            ? IncomingLetter::with(['receivedBy', 'processedBy', 'attachments', 'tracking.user'])->findOrFail($id)
            : OutgoingLetter::with(['createdBy', 'approvedBy', 'attachments', 'tracking.user'])->findOrFail($id);

        return Inertia::render('PendingLetters/Show', [
            'letter' => $letter,
            'type' => $type
        ]);
    }

    public function update(Request $request, string $id)
    {
        $type = $request->input('type');
        
        if (!in_array($type, ['incoming', 'outgoing'])) {
            abort(404);
        }

        if ($type === 'incoming') {
            return app(IncomingLetterController::class)->update($request, $id);
        } else {
            return app(OutgoingLetterController::class)->update($request, $id);
        }
    }

    public function statistics()
    {
        $stats = [
            'total_pending' => IncomingLetter::where('status', '!=', 'completed')->count() +
                             OutgoingLetter::whereIn('status', ['draft', 'review'])->count(),
            'high_priority' => IncomingLetter::where('status', '!=', 'completed')
                                ->where('priority', 'high')->count() +
                             OutgoingLetter::whereIn('status', ['draft', 'review'])
                                ->where('priority', 'high')->count(),
            'today_pending' => IncomingLetter::where('status', '!=', 'completed')
                                ->whereDate('created_at', today())->count() +
                             OutgoingLetter::whereIn('status', ['draft', 'review'])
                                ->whereDate('created_at', today())->count(),
        ];

        return response()->json($stats);
    }
}