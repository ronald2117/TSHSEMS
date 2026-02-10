<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Roster - {{ $classSchedule->subject->name }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .class-info {
            margin-bottom: 20px;
        }
        .class-info table {
            width: 100%;
            font-size: 12px;
        }
        .class-info td {
            padding: 5px;
        }
        .roster-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .roster-table th,
        .roster-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .roster-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 250px;
            margin-top: 40px;
        }
        .print-button {
            background-color: #22c55e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #16a34a;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Roster</button>
    
    <div class="header">
        <h1>TAYSAN SENIOR HIGH SCHOOL</h1>
        <p>Evaluation Management System</p>
        <p><strong>CLASS ROSTER</strong></p>
    </div>

    <div class="class-info">
        <table>
            <tr>
                <td><strong>Subject:</strong></td>
                <td>{{ $classSchedule->subject->name }} ({{ $classSchedule->subject->code }})</td>
                <td><strong>Section:</strong></td>
                <td>{{ $classSchedule->section->name }}</td>
            </tr>
            <tr>
                <td><strong>Strand:</strong></td>
                <td>{{ $classSchedule->section->strand->name }}</td>
                <td><strong>School Year:</strong></td>
                <td>{{ $classSchedule->schoolYear->year_start }}-{{ $classSchedule->schoolYear->year_end }}</td>
            </tr>
            <tr>
                <td><strong>Teacher:</strong></td>
                <td>{{ auth()->user()->full_name }}</td>
                <td><strong>Schedule:</strong></td>
                <td>{{ $classSchedule->schedule_time ?? 'TBA' }}</td>
            </tr>
            @if($classSchedule->room)
            <tr>
                <td><strong>Room:</strong></td>
                <td>{{ $classSchedule->room }}</td>
                <td><strong>Total Students:</strong></td>
                <td>{{ $students->count() }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="roster-table">
        <thead>
            <tr>
                <th style="width: 50px;">No.</th>
                <th style="width: 120px;">Student ID</th>
                <th>Student Name</th>
                <th style="width: 100px;">Sex</th>
                <th style="width: 200px;">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $enrollment)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $enrollment->student->student_id }}</td>
                    <td>{{ $enrollment->student->user->name }}</td>
                    <td>{{ $enrollment->student->sex ?? '' }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Teacher's Signature over Printed Name</p>
            <p style="font-size: 10px;">Date: _________________</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Class Adviser's Signature</p>
            <p style="font-size: 10px;">Date: _________________</p>
        </div>
    </div>
</body>
</html>
