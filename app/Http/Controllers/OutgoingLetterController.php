<?php
// app/Http/Controllers/OutgoingLetterController.php

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use App\Models\LetterAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterController extends Controller
{
    public function index(Request $request)
    {
        $query = OutgoingLetter::with(['createdBy', 'approvedBy', 'attachments'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('recipient', 'like', "%{$search}%");
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

        return Inertia::render('OutgoingLetters/Index', [
            'letters' => $letters,
            'filters' => $request->only(['search', 'status', 'priority'])
        ]);
    }

    public function create()
    {
        return Inertia::render('OutgoingLetters/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|unique:outgoing_letters',
            'recipient' => 'required|string',
            'recipient_address' => 'required|string',
            'subject' => 'required|string',
            'content' => 'required|string',
            'letter_date' => 'required|date',
            'type' => 'required|in:official,personal,confidential',
            'priority' => 'required|in:high,medium,low',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $letter = OutgoingLetter::create([
            ...$validated,
            'status' => 'draft',
            'created_by' => Auth::id()
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/outgoing');
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
            'status' => 'draft',
            'description' => 'Surat dibuat',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('outgoing-letters.index')
            ->with('success', 'Surat keluar berhasil dibuat.');
    }

    public function show(string $id)
    {
        $letter = OutgoingLetter::with([
            'createdBy',
            'approvedBy',
            'attachments.uploadedBy',
            'tracking.user'
        ])->findOrFail($id);

        return Inertia::render('OutgoingLetters/Show', [
            'letter' => $letter
        ]);
    }

    public function edit(string $id)
    {
        $letter = OutgoingLetter::with(['attachments'])->findOrFail($id);

        if ($letter->status === 'sent') {
            return redirect()->route('outgoing-letters.show', $id)
                ->with('error', 'Surat yang sudah dikirim tidak dapat diedit.');
        }

        return Inertia::render('OutgoingLetters/Edit', [
            'letter' => $letter
        ]);
    }

    public function update(Request $request, string $id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status === 'sent') {
            return redirect()->route('outgoing-letters.show', $id)
                ->with('error', 'Surat yang sudah dikirim tidak dapat diubah.');
        }

        $validated = $request->validate([
            'reference_number' => 'required|unique:outgoing_letters,reference_number,' . $id,
            'recipient' => 'required|string',
            'recipient_address' => 'required|string',
            'subject' => 'required|string',
            'content' => 'required|string',
            'letter_date' => 'required|date',
            'type' => 'required|in:official,personal,confidential',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:draft,review,approved,sent',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        // If status changing to approved, set approved_by
        if ($validated['status'] === 'approved' && $letter->status !== 'approved') {
            $validated['approved_by'] = Auth::id();
        }

        // If status changing to sent, set sent_at
        if ($validated['status'] === 'sent' && $letter->status !== 'sent') {
            $validated['sent_at'] = now();
        }

        $letter->update($validated);

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/outgoing');
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

        return redirect()->route('outgoing-letters.show', $letter->id)
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status === 'sent') {
            return redirect()->route('outgoing-letters.show', $id)
                ->with('error', 'Surat yang sudah dikirim tidak dapat dihapus.');
        }

        // Delete attachments from storage
        foreach ($letter->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        $letter->delete();

        return redirect()->route('outgoing-letters.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function downloadAttachment(string $id)
    {
        $attachment = LetterAttachment::findOrFail($id);
        
        return Storage::download(
            $attachment->file_path,
            $attachment->original_filename
        );
    }

    public function approve(string $id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status !== 'review') {
            return redirect()->route('outgoing-letters.show', $id)
                ->with('error', 'Hanya surat dengan status review yang dapat disetujui.');
        }

        $letter->update([
            'status' => 'approved',
            'approved_by' => Auth::id()
        ]);

        $letter->tracking()->create([
            'status' => 'approved',
            'description' => 'Surat disetujui',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('outgoing-letters.show', $id)
            ->with('success', 'Surat berhasil disetujui.');
    }

    public function send(string $id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status !== 'approved') {
            return redirect()->route('outgoing-letters.show', $id)
                ->with('error', 'Hanya surat yang sudah disetujui yang dapat dikirim.');
        }

        $letter->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $letter->tracking()->create([
            'status' => 'sent',
            'description' => 'Surat dikirim',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('outgoing-letters.show', $id)
            ->with('success', 'Status surat berhasil diubah menjadi terkirim.');
    }
}