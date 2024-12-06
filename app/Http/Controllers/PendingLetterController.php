<?php 
// app/Http/Controllers/PendingLetterController.php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PendingLetterController extends Controller
{
    public function index(Request $request)
    {
        $incomingColumns = ['id', 'reference_number', 'subject', 'priority', 'status', 'created_at', DB::raw("'incoming' as type")];
        $outgoingColumns = ['id', 'reference_number', 'subject', 'priority', 'status', 'created_at', DB::raw("'outgoing' as type")];
        
        $incomingLetters = IncomingLetter::select($incomingColumns)
            ->where('status', '!=', 'completed')
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($request->priority, function($query, $priority) {
                $query->where('priority', $priority);
            });

        $outgoingLetters = OutgoingLetter::select($outgoingColumns)
            ->whereIn('status', ['draft', 'review'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($request->priority, function($query, $priority) {
                $query->where('priority', $priority);
            });

        $letters = $incomingLetters->union($outgoingLetters)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('PendingLetters/Index', [
            'letters' => $letters,
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
                                ->whereDate('created_at', today())->count()
        ];

        return response()->json($stats);
    }
}