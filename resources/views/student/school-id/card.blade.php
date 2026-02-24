<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>School ID</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700;800;900&display=swap');

@page { size: A4; margin: 10mm; }
*{ box-sizing:border-box; margin:0; padding:0; }

*, *::before, *::after {
  -webkit-print-color-adjust: exact !important;
  print-color-adjust: exact !important;
  color-adjust: exact !important;
}

:root{
  --dark: #0b3b2f;
  --mid:  #2f7f72;
  --lite: #7fb0a7;
  --text: #0f172a;
  --muted:#6b7280;
}

body{
  font-family: Arial, sans-serif;
  background:#f3f4f6;
  padding:20px;
}

.container{ max-width:1100px; margin:auto; }

/* ===== BUTTONS ===== */
.no-print{ text-align:center; margin-bottom:20px; }
.btn{
  padding:12px 24px;
  border:none;
  border-radius:6px;
  font-size:15px;
  cursor:pointer;
  text-decoration:none;
  margin:5px;
}
.btn-primary{ background:#16a34a; color:#fff; }
.btn-secondary{ background:#6b7280; color:#fff; }

.id-wrapper{
  display:flex;
  gap:25px;
  flex-wrap:wrap;
  justify-content:center;
}

/* ===== CARD SIZE (portrait CR80 style) ===== */
.id{
  width:54mm;
  height:86mm;
  background:#fff;
  border-radius:4mm;
  overflow:hidden;
  position:relative;
  border:1.5px solid #111827;
}

/* =========================
   FRONT (match sample)
   ========================= */
.front{
  color:#0b1220;
  background:#fff;
  position:relative;
}

/* big dark diagonal field */
.front::before{
  content:"";
  position:absolute;
  left:-25%;
  top:30%;
  width:160%;
  height:42%;
  background:var(--dark);
  transform:rotate(18deg);
  z-index:0;
}

/* bottom teal diagonal band */
.front::after{
  content:"";
  position:absolute;
  left:-30%;
  bottom:-50%;
  width:170%;
  height:55%;
  background:var(--mid);
  transform:rotate(13deg);
  z-index:1;
}

/* dotted overlay similar to sample */
.front .dots{
  position:absolute;
  inset:0;
  z-index:1;
  opacity:.35;
  background-image: radial-gradient(#ffffff 1px, transparent 1px);
  background-size: 10px 10px;
  pointer-events:none;
  mix-blend-mode:soft-light;
}

/* extra lighter band + thin white stripe (lower-left) */
.front .accent-band{
  position:absolute;
  left:-35%;
  bottom:8%;
  width:180%;
  height:16%;
  background:var(--lite);
  transform:rotate(-13deg);
  z-index:2;
}
.front .accent-line{
  position:absolute;
  left:-35%;
  bottom:20%;
  width:180%;
  height:1.8%;
  background:#ffffff;
  transform:rotate(-13deg);
  z-index:3;
}

/* Header block (top-left) */
.front .brand{
  position:absolute;
  top:10px;
  left:12px;
  right:80px; /* leave space for seal */
  z-index:5;
  font-family:Montserrat, Arial, sans-serif;
}

.front .brand .dots3{
  display:flex;
  gap:2px;
  margin-bottom:2px;
  margin-top: 11px;
}
.front .brand .dots3 span{
  width:3px; height:3px;
  background:var(--dark);
  border-radius:50%;
  display:inline-block;
}
.front .brand .topline{
  height:1px;
  background:var(--dark);
  width:60%;
  margin-bottom:2px;
}

.front .brand .title{
  font-weight:900;
  letter-spacing:1px;
  color:var(--mid);
  font-size:10px;
  line-height:1.05;
  width:100%;
  text-transform:uppercase;
}
.front .brand .meta{
  margin-top:6px;
  font-size:9px;
  letter-spacing:.6px;
  color:#0f172a;
  text-transform:uppercase;
  line-height:1.3;
}

/* Big seal (top-right) */
.front .seal{
  position:absolute;
  top:8px;
  right:1px;
  width:78px;
  height:78px;
  z-index:6;
}
.front .seal img{
  width:100%;
  height:100%;
  object-fit:contain;
}

/* Side vertical code (right edge) */
.side-code{
  position:absolute;
  right:6px;
  top:44%;
  transform:translateY(-50%) rotate(180deg);
  writing-mode:vertical-rl;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:700;
  font-size:12px;
  letter-spacing:2px;
  color:#0f172a;
  z-index:7;
  opacity:.9;
}

/* decorative vertical bars similar to sample */
.front .vbar{
  position:absolute;
  width:2px;
  background:#ffffff;
  opacity:.65;
  z-index:2;
}
.front .vbar.left{ left:16px; top:34%; height:34%; }
.front .vbar.right{ right:16px; top:34%; height:34%; }

/* Photo */
.front .photo-area{
  position:absolute;
  left:50%;
  transform:translateX(-50%);
  top:110px;
  width:78%;
  height:52%;
  z-index:6;
  display:flex;
  align-items:flex-end;
  justify-content:center;
}
.front .photo{
  width:100%;
  height:100%;
  overflow:hidden;
  border-radius:4px;
  background:transparent;
}
.front .photo img{
  width:100%;
  height:100%;
  object-fit:cover;
}

/* Name + ID + Strand (on the lower bands) */
.front .info{
  position:absolute;
  left:16px;
  right:16px;
  bottom:22px;
  z-index:8;
  font-family:Montserrat, Arial, sans-serif;
}
.front .info .name{
  color:#ffffff;
  font-weight:900;
  font-size:20px;
  letter-spacing:1px;
  text-transform:uppercase;
  line-height:1.05;
  text-shadow:0 2px 0 rgba(0,0,0,.25);
}
.front .info .school-id{
  margin-top:10px;
  font-weight:800;
  font-size:16px;
  letter-spacing:1px;
  color:#0b1220;
}
.front .info .strand{
  margin-top:6px;
  font-weight:700;
  font-size:11px;
  color:#0b1220;
}

/* Signature bottom-right */
.front .signature{
  position:absolute;
  right:14px;
  bottom:10px;
  z-index:9;
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  color:#0b1220;
}
.front .signature img{
  height:24px;
  max-width:110px;
  object-fit:contain;
  display:block;
  margin:0 auto 3px;
}
.front .signature span{
  font-size:9px;
  font-weight:700;
}

/* =========================
   BACK (match sample)
   ========================= */
.back{
  background:#fff;
  padding:12px 12px 10px;
  position:relative;
  color:#111827;
}

/* subtle inner border like the sample */
.back .inner{
  border:1.5px solid #9ca3af;
  border-radius:2mm;
  padding:12px;
  height:100%;
}

.back .em-title{
  font-style:italic;
  text-transform:uppercase;
  text-align:center;
  font-size:11px;
  letter-spacing:.8px;
  margin-bottom:10px;
  font-family:Montserrat, Arial, sans-serif;
}

.back .em-box{
  border:1.5px solid #9ca3af;
  padding:12px 10px;
  text-align:center;
  margin-bottom:14px;
}

.back .em-box .em-name{
  font-weight:900;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:16px;
  line-height:1.1;
}
.back .em-box .em-contact{
  margin-top:8px;
  font-weight:900;
  font-family:Montserrat, Arial, sans-serif;
  font-size:18px;
  letter-spacing:1px;
}
.back .em-box .em-addr{
  margin-top:6px;
  font-weight:900;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:14px;
  line-height:1.15;
}

.back .valid-title{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:12px;
  letter-spacing:.8px;
  margin:10px 0 12px;
}

.back .years{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:900;
  font-size:18px;
  letter-spacing:1px;
  line-height:1.9;
  margin-bottom:10px;
}

.back .important{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:900;
  font-size:16px;
  margin:6px 0 10px;
  text-transform:uppercase;
}

.back ul.notes{
  margin:0;
  padding-left:18px;
  font-size:12px;
  line-height:1.35;
  color:#111827;
}
.back ul.notes li{ margin-bottom:8px; }

.back .principal{
  position:absolute;
  left:18px;
  right:18px;
  bottom:14px;
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
}
.back .principal .sign{
  height:28px;
  max-width:160px;
  object-fit:contain;
  display:block;
  margin:0 auto 6px;
}
.back .principal .pname{
  font-weight:900;
  font-size:16px;
  text-transform:uppercase;
}
.back .principal .ptitle{
  font-weight:800;
  font-size:14px;
}

.back .side-code{
  top:62%;
  font-size:12px;
}

/* PRINT */
@media print{
  body{ background:#fff; padding:0; }
  .no-print{ display:none; }
  .id-wrapper{ gap:10mm; }
}
</style>
</head>

<body>
<div class="container">

  <!-- PRINT BUTTONS -->
  <div class="no-print">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Print ID Card</button>
    <a href="{{ route('student.school-id.upload') }}" class="btn btn-secondary">← Back to Upload</a>
  </div>

  <div class="id-wrapper">

    <!-- FRONT -->
    <div class="id front">
      <div class="dots"></div>
      <div class="accent-band"></div>
      <div class="accent-line"></div>

      <div class="brand">
        <div class="dots3"><span></span><span></span><span></span></div>
        <div class="topline"></div>
        <div class="title">TAYSAN SENIOR<br/>HIGH SCHOOL</div>
        <div class="meta">
          SCHOOL ID - {{ $profile->school_id }}<br/>
          BRGY. MAHANADIONG, TAYSAN, BATANGAS
        </div>
      </div>

      <div class="seal">
        <!-- CHANGE THIS PATH TO YOUR REAL SEAL FILE -->
        <img src="{{ asset('tshs_logo-removebg.png') }}" alt="School Seal">
      </div>

      <!-- optional: show a code like the sample; set your own field -->
      <div class="side-code">
        {{ $profile->id_code ?? '2025-0005' }}
      </div>

      <div class="vbar left"></div>
      <div class="vbar right"></div>

      <div class="photo-area">
        <div class="photo">
          <img src="{{ Storage::url($profile->id_photo_path) }}" alt="Student Photo">
        </div>
      </div>

      <div class="info">
        <div class="name">{{ $profile->user->full_name }}</div>
        <div class="school-id">{{ $profile->school_id }}</div>
        <div class="strand">
          {{ $profile->strand->name }}
        </div>
      </div>

      <div class="signature">
        <img src="{{ Storage::url($profile->signature_path) }}" alt="Signature">
        <span>Student Signature</span>
      </div>
    </div>

    <!-- BACK -->
    <div class="id back">
      <div class="inner">

        <div class="em-title">PERSON TO BE NOTIFIED IN CASE OF EMERGENCY</div>

        <div class="em-box">
          @if($emergencyContact)
            <div class="em-name">{{ $emergencyContact['name'] }}</div>
            <div class="em-contact">{{ $emergencyContact['contact'] }}</div>
            <div class="em-addr">{{ $emergencyContact['address'] }}</div>
          @else
            <div class="em-name">{{ $profile->guardian_name }}</div>
            <div class="em-contact">{{ $profile->guardian_contact }}</div>
            <div class="em-addr">{{ $profile->address }}</div>
          @endif
        </div>

        <div class="valid-title">THIS IDENTIFICATION CARD IS VALID ONLY FOR THE:</div>

        <div class="years">
          @foreach($validityYears as $year)
            S.Y. {{ $year }}<br>
          @endforeach
        </div>

        <div class="important">IMPORTANT</div>

        <ul class="notes">
          <li>The school ID is issued by TAYSAN SENIOR HIGH SCHOOL and remains the property of the school. The school ID must be worn visibly at all times while on school premises.</li>
          <li>In case of loss, report immediately to the office of the Principal or call landline number (043) 786-6254.</li>
        </ul>

        <!-- optional: right-side vertical code like the sample -->
        <div class="side-code">
          {{ $profile->id_code ?? '2025-0005' }}
        </div>

        <div class="principal">
          <!-- If you have a principal signature image, put it here; otherwise remove this img -->
          <!-- <img class="sign" src="{{ asset('images/principal-signature.png') }}" alt="Principal Signature"> -->
          <div class="pname">DR. ROWENA D. RAMIREZ</div>
          <div class="ptitle">School Head</div>
        </div>

      </div>
    </div>

  </div>
</div>
</body>
</html>