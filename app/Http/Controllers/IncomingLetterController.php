<?php
// app/Http/Controllers/IncomingLetterController.php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\LetterAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index(Request $request)
    {
        $query = IncomingLetter::with(['receivedBy', 'processedBy', 'attachments'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('sender', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->latest();

        $letters = $query->paginate(10)
            ->appends($request->query());

        return Inertia::render('IncomingLetters/Index', [
            'letters' => $letters,
            'filters' => $request->only(['search', 'status', 'priority'])
        ]);
    }

    public function create()
    {
        return Inertia::render('IncomingLetters/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|unique:incoming_letters',
            'origin' => 'required|string',
            'sender' => 'required|string',
            'recipient' => 'required|string',
            'subject' => 'required|string',
            'description' => 'nullable|string',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'type' => 'required|in:official,personal,confidential',
            'priority' => 'required|in:high,medium,low',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $letter = IncomingLetter::create([
            ...$validated,
            'status' => 'received',
            'received_by' => Auth::id()
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/incoming');
                $letter->attachments()->create([
                    'filename' => basename($path),
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id()
                ]);
            }
        }

        // Create initial tracking record
        $letter->tracking()->create([
            'status' => 'received',
            'description' => 'Surat diterima',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('incoming-letters.index')
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $letter = IncomingLetter::with([
            'receivedBy',
            'processedBy',
            'attachments.uploadedBy',
            'tracking.user'
        ])->findOrFail($id);

        return Inertia::render('IncomingLetters/Show', [
            'letter' => $letter
        ]);
    }

    public function edit(string $id)
    {
        $letter = IncomingLetter::with(['attachments'])->findOrFail($id);

        return Inertia::render('IncomingLetters/Edit', [
            'letter' => $letter
        ]);
    }

    public function update(Request $request, string $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        $validated = $request->validate([
            'reference_number' => 'required|unique:incoming_letters,reference_number,' . $id,
            'origin' => 'required|string',
            'sender' => 'required|string',
            'recipient' => 'required|string',
            'subject' => 'required|string',
            'description' => 'nullable|string',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'type' => 'required|in:official,personal,confidential',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:received,processed,completed',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $letter->update($validated);

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/incoming');
                $letter->attachments()->create([
                    'filename' => basename($path),
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id()
                ]);
            }
        }

        // Create tracking record for status change if needed
        if ($letter->wasChanged('status')) {
            $letter->tracking()->create([
                'status' => $letter->status,
                'description' => 'Status surat diperbarui ke ' . $letter->status,
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->route('incoming-letters.show', $letter->id)
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        // Delete attachments from storage
        foreach ($letter->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        $letter->delete();

        return redirect()->route('incoming-letters.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function downloadAttachment(string $id)
    {
        $attachment = LetterAttachment::findOrFail($id);
        
        return Storage::download(
            $attachment->file_path,
            $attachment->original_filename
        );
    }
}