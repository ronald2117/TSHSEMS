<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Attendance Summary - {{ $classSchedule->subject->name }}</title>
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
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
        }
        .info-item {
            display: flex;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        .stats-section {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-label {
            font-size: 10px;
            margin-bottom: 2px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
        }
        .summary-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 9px;
        }
        .summary-table .student-name {
            text-align: left;
            font-size: 9px;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .summary-table .lrn-col {
            font-size: 8px;
        }
        .status-p { font-weight: bold; }
        .status-a { font-weight: bold; }
        .status-l { font-weight: bold; }
        .status-e { font-weight: bold; }
        .legend {
            margin-top: 10px;
            padding: 8px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            display: flex;
            gap: 15px;
            font-size: 10px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        .controls {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .controls input,
        .controls button {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
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
            background-color: #6b7280 !important;
        }
        .btn-secondary:hover {
            background-color: #4b5563 !important;
        }
        .totals-col {
            background-color: #f9fafb;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="controls no-print">
        <a href="{{ route('teacher.attendance.history', $classSchedule) }}" class="btn-secondary" style="display: inline-block; text-decoration: none; color: white; padding: 8px 16px; border-radius: 6px;">
            ← Back to History
        </a>
        <form method="GET" action="{{ route('teacher.attendance.monthly-summary', $classSchedule) }}" style="display: flex; gap: 10px; flex: 1;">
            <input type="month" name="month" value="{{ $month }}" class="cursor-pointer" onchange="this.form.submit()">
            <button type="button" onclick="window.print()" style="margin-left: auto;">🖨️ Print Summary</button>
        </form>
    </div>
    
    <div class="header">
        <h1>TAYSAN SENIOR HIGH SCHOOL</h1>
        <p>Evaluation Management System</p>
        <p><strong>MONTHLY ATTENDANCE SUMMARY</strong></p>
    </div>

    <div class="info-section">
        <div class="info-item">
            <span class="info-label">Subject:</span>
            <span>{{ $classSchedule->subject->name }} ({{ $classSchedule->subject->code }})</span>
        </div>
        <div class="info-item">
            <span class="info-label">Section:</span>
            <span>{{ $classSchedule->section->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Teacher:</span>
            <span>{{ auth()->user()->full_name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Month:</span>
            <span>{{ $monthName }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Students:</span>
            <span>{{ $students->count() }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Days:</span>
            <span>{{ $stats['total_days'] }}</span>
        </div>
    </div>

    <div class="stats-section">
        <div class="stat-item">
            <div class="stat-label">Total Records</div>
            <div class="stat-value">{{ $stats['total_records'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Present</div>
            <div class="stat-value status-p">{{ $stats['present'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Absent</div>
            <div class="stat-value status-a">{{ $stats['absent'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Late</div>
            <div class="stat-value status-l">{{ $stats['late'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Excused</div>
            <div class="stat-value status-e">{{ $stats['excused'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-value">{{ $stats['total_records'] > 0 ? round(($stats['present'] / $stats['total_records']) * 100, 1) : 0 }}%</div>
        </div>
    </div>

    <div class="legend">
        <div class="legend-item">
            <strong>Legend:</strong>
        </div>
        <div class="legend-item">
            <span class="status-p">P</span> = Present
        </div>
        <div class="legend-item">
            <span class="status-a">A</span> = Absent
        </div>
        <div class="legend-item">
            <span class="status-l">L</span> = Late
        </div>
        <div class="legend-item">
            <span class="status-e">E</span> = Excused
        </div>
    </div>

    <table class="summary-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No.</th>
                <th rowspan="2" style="width: 80px;">LRN</th>
                <th rowspan="2" style="width: 150px;">Student Name</th>
                @if($dates->isNotEmpty())
                    <th colspan="{{ $dates->count() }}">Daily Attendance</th>
                @endif
                <th colspan="5">Summary</th>
                <th rowspan="2" style="width: 50px;">Rate</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th style="width: 20px;">{{ $date->format('d') }}</th>
                @endforeach
                <th style="width: 30px;">P</th>
                <th style="width: 30px;">A</th>
                <th style="width: 30px;">L</th>
                <th style="width: 30px;">E</th>
                <th style="width: 30px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($studentAttendanceData as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="lrn-col">{{ $data['student']->studentProfile->lrn ?? 'N/A' }}</td>
                    <td class="student-name" title="{{ $data['student']->full_name }}">{{ $data['student']->full_name }}</td>
                    @foreach($dates as $date)
                        @php
                            $status = $data['records'][$date->format('Y-m-d')] ?? null;
                            $displayStatus = match($status) {
                                'Present' => 'P',
                                'Absent' => 'A',
                                'Late' => 'L',
                                'Excused' => 'E',
                                default => '-'
                            };
                            $statusClass = match($status) {
                                'Present' => 'status-p',
                                'Absent' => 'status-a',
                                'Late' => 'status-l',
                                'Excused' => 'status-e',
                                default => ''
                            };
                        @endphp
                        <td class="{{ $statusClass }}">{{ $displayStatus }}</td>
                    @endforeach
                    <td class="totals-col status-p">{{ $data['present'] }}</td>
                    <td class="totals-col status-a">{{ $data['absent'] }}</td>
                    <td class="totals-col status-l">{{ $data['late'] }}</td>
                    <td class="totals-col status-e">{{ $data['excused'] }}</td>
                    <td class="totals-col">{{ $data['total'] }}</td>
                    <td class="totals-col">{{ $data['attendance_rate'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 8 + $dates->count() }}" style="text-align: center; padding: 20px;">
                        No students enrolled in this class
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p style="font-size: 11px; margin: 0;">Teacher's Signature over Printed Name</p>
            <p style="font-size: 9px; margin-top: 15px;">Date: _________________</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p style="font-size: 11px; margin: 0;">Principal's Signature</p>
            <p style="font-size: 9px; margin-top: 15px;">Date: _________________</p>
        </div>
    </div>
</body>
</html>
