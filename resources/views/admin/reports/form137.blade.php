<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 137 - Permanent Record - {{ $student->user->full_name ?? 'Student' }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 10px; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 11px;
            max-width: 900px;
            margin: 0 auto;
        }
        .controls {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            align-items: center;
        }
        .controls button,
        .controls a {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .controls button {
            background-color: #22c55e;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }
        .controls button:hover {
            background-color: #16a34a;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .document {
            background-color: white;
            padding: 40px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        @media print {
            .document {
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .info-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .info-section p {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
        }
        .school-year-section {
            margin-bottom: 30px;
        }
        .school-year-title {
            background-color: #f3f4f6;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }
        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }
        table th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        table td.subject-name {
            text-align: left;
        }
        table tfoot td {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .text-red {
            color: #dc2626;
        }
        .text-green {
            color: #16a34a;
        }
        .certification {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #000;
        }
        .certification p {
            text-align: center;
            margin-bottom: 30px;
            font-size: 11px;
        }
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 40px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 0 40px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
        }
        .date-issued {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #6b7280;
        }
        .no-grades {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="controls no-print">
        <a href="{{ route('admin.reports.students') }}" class="btn-secondary">
            ← Back to Student List
        </a>
        <button type="button" onclick="window.print()">
            🖨️ Print Form 137
        </button>
    </div>

    <div class="document">
        <!-- Header -->
        <div class="header">
            <p>Republic of the Philippines</p>
            <p>Department of Education</p>
            <p><strong>TAYSAN SENIOR HIGH SCHOOL</strong></p>
            <p>Taysan, Batangas</p>
            <h1>SENIOR HIGH SCHOOL PERMANENT RECORD</h1>
            <p>(Form 137-SHS)</p>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <div>
                <p><span class="info-label">LRN:</span> {{ $student->lrn }}</p>
                <p><span class="info-label">Name:</span> {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}</p>
                <p><span class="info-label">Sex:</span> {{ $student->gender ?? 'N/A' }}</p>
                <p><span class="info-label">Date of Birth:</span> {{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p><span class="info-label">Track:</span> {{ $student->strand->track->description ?? 'N/A' }}</p>
                <p><span class="info-label">Strand:</span> {{ $student->strand->name ?? 'N/A' }}</p>
                <p><span class="info-label">Current Section:</span> {{ $student->currentSection->name ?? 'N/A' }}</p>
                <p><span class="info-label">Grade Level:</span> {{ $student->grade_level ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Academic Records by School Year -->
        @forelse($grades as $schoolYearId => $schoolYearGrades)
            @php
                $schoolYear = \App\Models\SchoolYear::find($schoolYearId);
                $gradesBySubject = $schoolYearGrades->groupBy('class_schedule_id');
            @endphp
            
            <div class="school-year-section">
                <div class="school-year-title">
                    School Year: {{ $schoolYear->name ?? 'N/A' }}
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: left;">Subject</th>
                            <th style="width: 60px;">Q1</th>
                            <th style="width: 60px;">Q2</th>
                            <th style="width: 60px;">Q3</th>
                            <th style="width: 60px;">Q4</th>
                            <th style="width: 60px;">Final</th>
                            <th style="width: 80px;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalFinal = 0; $subjectCount = 0; @endphp
                        @foreach($gradesBySubject as $scheduleId => $subjectGrades)
                            @php
                                $subject = $subjectGrades->first()->classSchedule->subject ?? null;
                                $q1 = $subjectGrades->where('quarter', 1)->first();
                                $q2 = $subjectGrades->where('quarter', 2)->first();
                                $q3 = $subjectGrades->where('quarter', 3)->first();
                                $q4 = $subjectGrades->where('quarter', 4)->first();
                                
                                $quarters = collect([$q1, $q2, $q3, $q4])->filter()->pluck('final_grade');
                                $finalGrade = $quarters->count() > 0 ? $quarters->avg() : null;
                                
                                if ($finalGrade) {
                                    $totalFinal += $finalGrade;
                                    $subjectCount++;
                                }
                            @endphp
                            <tr>
                                <td class="subject-name">{{ $subject->name ?? 'Subject' }}</td>
                                <td>{{ $q1 ? number_format($q1->final_grade, 0) : '-' }}</td>
                                <td>{{ $q2 ? number_format($q2->final_grade, 0) : '-' }}</td>
                                <td>{{ $q3 ? number_format($q3->final_grade, 0) : '-' }}</td>
                                <td>{{ $q4 ? number_format($q4->final_grade, 0) : '-' }}</td>
                                <td><strong>{{ $finalGrade ? number_format($finalGrade, 0) : '-' }}</strong></td>
                                <td>
                                    {{ $finalGrade ? ($finalGrade >= 75 ? 'PASSED' : 'FAILED') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">General Weighted Average:</td>
                            <td>
                                {{ $subjectCount > 0 ? number_format($totalFinal / $subjectCount, 2) : '-' }}
                            </td>
                            <td>
                                @if($subjectCount > 0)
                                    @php $gwa = $totalFinal / $subjectCount; @endphp
                                    {{ $gwa >= 75 ? 'PASSED' : 'FAILED' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <div class="no-grades">
                <p>No approved grades found for this student.</p>
            </div>
        @endforelse

        <!-- Certification -->
        <div class="certification">
            <p>
                I CERTIFY that this is a true record of <strong>{{ $student->user->full_name ?? 'the student' }}</strong> 
                and that he/she is eligible for admission to Grade ______.
            </p>
            
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line">Class Adviser</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">School Principal</div>
                </div>
            </div>
            
            <p class="date-issued">
                Date Issued: {{ now()->format('F d, Y') }}
            </p>
        </div>
    </div>
</body>
</html>
