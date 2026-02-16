<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 138 - Report Card - {{ $student->user->full_name ?? 'Student' }}</title>
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
            justify-content: space-between;
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
        .btn-filter {
            background-color: #e5e7eb;
            color: #374151;
        }
        .btn-filter:hover {
            background-color: #d1d5db;
        }
        .filter-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .filter-form label {
            font-size: 12px;
            font-weight: 500;
            display: block;
            margin-bottom: 4px;
        }
        .filter-form select {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
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
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .header-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin-bottom: 10px;
        }
        .header-logos img {
            height: 60px;
            width: 60px;
            object-fit: contain;
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
            background-color: #f3f4f6;
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
        .descriptors {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            font-size: 10px;
            margin-bottom: 30px;
        }
        .descriptors h3 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .descriptors table {
            font-size: 10px;
        }
        .descriptors td {
            padding: 6px;
        }
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 40px 40px 0 40px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
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
        <form method="GET" action="{{ route('admin.reports.form138', $student->id) }}" class="filter-form">
            <div>
                <label>Quarter</label>
                <select name="quarter">
                    <option value="">All Quarters</option>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ request('quarter') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-filter">
                Filter
            </button>
        </form>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.reports.students') }}" class="btn-secondary">
                ← Back
            </a>
            <button type="button" onclick="window.print()">
                🖨️ Print Report Card
            </button>
        </div>
    </div>

    <div class="document">
        <!-- Header -->
        <div class="header">
            <div class="header-logos">
                <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd Logo" onerror="this.style.display='none'">
                <div>
                    <p>Republic of the Philippines</p>
                    <p>Department of Education</p>
                    <p><strong>TAYSAN SENIOR HIGH SCHOOL</strong></p>
                    <p>Taysan, Batangas</p>
                </div>
                <img src="{{ asset('images/school-logo.png') }}" alt="School Logo" onerror="this.style.display='none'">
            </div>
            <h1>REPORT CARD</h1>
            <p>(Form 138-SHS)</p>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <div>
                <p><span class="info-label">LRN:</span> {{ $student->lrn }}</p>
                <p><span class="info-label">Name:</span> {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}</p>
                <p><span class="info-label">Grade & Section:</span> Grade {{ $student->grade_level ?? '' }} - {{ $student->currentSection->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p><span class="info-label">Track:</span> {{ $student->strand->track->name ?? 'N/A' }}</p>
                <p><span class="info-label">Strand:</span> {{ $student->strand->name ?? 'N/A' }}</p>
                <p><span class="info-label">School Year:</span> {{ $student->currentSection->schoolYear->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Grades Table -->
        @php
            $gradesBySubject = $grades->groupBy('class_schedule_id');
        @endphp
        
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: left;">Learning Areas</th>
                    <th colspan="4">Quarterly Grades</th>
                    <th rowspan="2" style="width: 60px;">Final<br>Grade</th>
                    <th rowspan="2" style="width: 80px;">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 50px;">1</th>
                    <th style="width: 50px;">2</th>
                    <th style="width: 50px;">3</th>
                    <th style="width: 50px;">4</th>
                </tr>
            </thead>
            <tbody>
                @php $totalFinal = 0; $subjectCount = 0; @endphp
                
                @forelse($gradesBySubject as $scheduleId => $subjectGrades)
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
                        <td class="{{ $q1 && $q1->final_grade < 75 ? 'text-red' : '' }}">
                            {{ $q1 ? number_format($q1->final_grade, 0) : '' }}
                        </td>
                        <td class="{{ $q2 && $q2->final_grade < 75 ? 'text-red' : '' }}">
                            {{ $q2 ? number_format($q2->final_grade, 0) : '' }}
                        </td>
                        <td class="{{ $q3 && $q3->final_grade < 75 ? 'text-red' : '' }}">
                            {{ $q3 ? number_format($q3->final_grade, 0) : '' }}
                        </td>
                        <td class="{{ $q4 && $q4->final_grade < 75 ? 'text-red' : '' }}">
                            {{ $q4 ? number_format($q4->final_grade, 0) : '' }}
                        </td>
                        <td class="{{ $finalGrade && $finalGrade < 75 ? 'text-red' : '' }}"><strong>{{ $finalGrade ? number_format($finalGrade, 0) : '' }}</strong></td>
                        <td>
                            @if($finalGrade)
                                <span class="{{ $finalGrade >= 75 ? 'text-green' : 'text-red' }}">
                                    {{ $finalGrade >= 75 ? 'PASSED' : 'FAILED' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-grades">
                            No approved grades found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($subjectCount > 0)
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right;">
                        General Weighted Average
                    </td>
                    <td>
                        <strong>{{ number_format($totalFinal / $subjectCount, 2) }}</strong>
                    </td>
                    <td>
                        @php $gwa = $totalFinal / $subjectCount; @endphp
                        <span class="{{ $gwa >= 75 ? 'text-green' : 'text-red' }}">
                            <strong>{{ $gwa >= 75 ? 'PASSED' : 'FAILED' }}</strong>
                        </span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- Descriptors -->
        <div class="descriptors">
            <div>
                <h3>Descriptors</h3>
                <table>
                    <tr><td style="text-align: left;">Outstanding</td><td>90-100</td></tr>
                    <tr><td style="text-align: left;">Very Satisfactory</td><td>85-89</td></tr>
                    <tr><td style="text-align: left;">Satisfactory</td><td>80-84</td></tr>
                    <tr><td style="text-align: left;">Fairly Satisfactory</td><td>75-79</td></tr>
                    <tr><td style="text-align: left;">Did Not Meet Expectations</td><td>Below 75</td></tr>
                </table>
            </div>
            <div>
                <h3>Parent/Guardian's Signature</h3>
                <table>
                    <tr><td style="text-align: left; height: 30px;">1st Quarter:</td></tr>
                    <tr><td style="text-align: left; height: 30px;">2nd Quarter:</td></tr>
                    <tr><td style="text-align: left; height: 30px;">3rd Quarter:</td></tr>
                    <tr><td style="text-align: left; height: 30px;">4th Quarter:</td></tr>
                </table>
            </div>
        </div>

        <!-- Certification -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">Class Adviser</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">School Principal</div>
            </div>
        </div>
    </div>
</body>
</html>
