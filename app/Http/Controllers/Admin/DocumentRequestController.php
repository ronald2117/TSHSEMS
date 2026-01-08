<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class DocumentRequestController extends Controller
{
    /**
     * Display all document requests
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = DocumentRequest::with(['student.user'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20);
        
        $stats = [
            'pending' => DocumentRequest::where('status', 'pending')->count(),
            'processing' => DocumentRequest::where('status', 'processing')->count(),
            'ready' => DocumentRequest::where('status', 'ready')->count(),
            'released' => DocumentRequest::where('status', 'released')->count(),
        ];

        return view('admin.registrar-admin.documents.index', compact('requests', 'stats', 'status'));
    }

    /**
     * Show document request details
     */
    public function show(DocumentRequest $documentRequest)
    {
        $documentRequest->load(['student.user', 'student.section', 'processedBy']);
        
        return view('admin.registrar-admin.documents.show', compact('documentRequest'));
    }

    /**
     * Update document request status
     */
    public function updateStatus(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,released,rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        $documentRequest->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'],
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // If status is ready, set ready date
        if ($validated['status'] === 'ready') {
            $documentRequest->update(['ready_date' => now()]);
        }

        // If status is released, set release date
        if ($validated['status'] === 'released') {
            $documentRequest->update(['release_date' => now()]);
        }

        return back()->with('success', 'Document request status updated successfully');
    }
}
