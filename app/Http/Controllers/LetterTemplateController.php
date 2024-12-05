<?php
// app/Http/Controllers/LetterTemplateController.php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LetterTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterTemplate::with(['createdBy', 'updatedBy'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->has('active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('active'));
            })
            ->latest();

        $templates = $query->paginate(10)
            ->appends($request->query());

        return Inertia::render('LetterTemplates/Index', [
            'templates' => $templates,
            'filters' => $request->only(['search', 'type', 'active'])
        ]);
    }

    public function create()
    {
        return Inertia::render('LetterTemplates/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:letter_templates',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string',
            'description' => 'nullable|string',
            'type' => 'required|in:incoming,outgoing',
            'is_active' => 'boolean'
        ]);

        $template = LetterTemplate::create([
            ...$validated,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('letter-templates.index')
            ->with('success', 'Template surat berhasil dibuat.');
    }

    public function show(string $id)
    {
        $template = LetterTemplate::with(['createdBy', 'updatedBy'])->findOrFail($id);

        return Inertia::render('LetterTemplates/Show', [
            'template' => $template
        ]);
    }

    public function edit(string $id)
    {
        $template = LetterTemplate::findOrFail($id);

        return Inertia::render('LetterTemplates/Edit', [
            'template' => $template
        ]);
    }

    public function update(Request $request, string $id)
    {
        $template = LetterTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:letter_templates,code,' . $id,
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*' => 'string',
            'description' => 'nullable|string',
            'type' => 'required|in:incoming,outgoing',
            'is_active' => 'boolean'
        ]);

        $template->update([
            ...$validated,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('letter-templates.show', $template->id)
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $template = LetterTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('letter-templates.index')
            ->with('success', 'Template surat berhasil dihapus.');
    }

    public function preview(Request $request, string $id)
    {
        $template = LetterTemplate::findOrFail($id);
        $variables = $request->input('variables', []);
        
        $content = $template->content;
        foreach ($variables as $key => $value) {
            $content = str_replace("{{" . $key . "}}", $value, $content);
        }

        return response()->json(['content' => $content]);
    }
}