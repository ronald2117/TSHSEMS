<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\SchoolYear;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{
    public function index()
    {
        $periods = AcademicPeriod::with('schoolYear')
            ->orderBy('school_year_id', 'desc')
            ->paginate(15);

        return view('admin.academic-periods.index', compact('periods'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        return view('admin.academic-periods.create', compact('schoolYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Closed',
        ]);

        AcademicPeriod::create($validated);

        ActivityLog::log(
            'create',
            "Created academic period: {$validated['name']}"
        );

        return redirect()
            ->route('admin.academic-periods.index')
            ->with('success', 'Academic period created successfully!');
    }

    public function show(AcademicPeriod $academicPeriod)
    {
        $academicPeriod->load('schoolYear', 'sections');
        return view('admin.academic-periods.show', compact('academicPeriod'));
    }

    public function edit(AcademicPeriod $academicPeriod)
    {
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        return view('admin.academic-periods.edit', compact('academicPeriod', 'schoolYears'));
    }

    public function update(Request $request, AcademicPeriod $academicPeriod)
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Closed',
        ]);

        $academicPeriod->update($validated);

        ActivityLog::log(
            'update',
            "Updated academic period: {$validated['name']}"
        );

        return redirect()
            ->route('admin.academic-periods.index')
            ->with('success', 'Academic period updated successfully!');
    }

    public function destroy(AcademicPeriod $academicPeriod)
    {
        $name = $academicPeriod->name;
        
        $academicPeriod->delete();

        ActivityLog::log(
            'delete',
            "Deleted academic period: {$name}"
        );

        return redirect()
            ->route('admin.academic-periods.index')
            ->with('success', 'Academic period deleted successfully!');
    }

    public function toggleStatus(AcademicPeriod $academicPeriod)
    {
        $newStatus = $academicPeriod->status === 'Active' ? 'Closed' : 'Active';
        $academicPeriod->update(['status' => $newStatus]);

        ActivityLog::log(
            'update',
            "Changed academic period status to {$newStatus}: {$academicPeriod->name}"
        );

        return redirect()
            ->back()
            ->with('success', "Academic period status changed to {$newStatus}!");
    }
}
