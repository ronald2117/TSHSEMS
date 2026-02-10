<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::where('student_id', Auth::id())
            ->with('processedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.documents.index', compact('requests'));
    }

    public function create()
    {
        return view('student.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'copies' => 'required|integer|min:1|max:10',
            'purpose' => 'required|string|max:500',
        ]);

        $validated['student_id'] = Auth::id();
        $validated['status'] = 'Pending';

        // Set fees based on document type (could be made configurable)
        $fees = [
            'Form 137' => 50.00,
            'Form 138' => 30.00,
            'Certificate of Enrollment' => 20.00,
            'Certificate of Good Moral' => 20.00,
            'Transcript of Records' => 100.00,
        ];

        $validated['fee'] = ($fees[$validated['type']] ?? 50.00) * $validated['copies'];

        DocumentRequest::create($validated);

        return redirect()
            ->route('student.documents.index')
            ->with('success', 'Document request submitted successfully! Please wait for admin approval.');
    }

    public function show(DocumentRequest $document)
    {
        // Verify ownership
        if ($document->student_id !== Auth::id()) {
            abort(403);
        }

        return view('student.documents.show', compact('document'));
    }

    public function cancel(DocumentRequest $document)
    {
        // Verify ownership
        if ($document->student_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel pending requests
        if ($document->status !== 'Pending') {
            return redirect()
                ->back()
                ->with('error', 'Only pending requests can be cancelled.');
        }

        $document->update([
            'status' => 'Rejected',
            'rejection_reason' => 'Cancelled by student'
        ]);

        return redirect()
            ->route('student.documents.index')
            ->with('success', 'Document request cancelled.');
    }
}
