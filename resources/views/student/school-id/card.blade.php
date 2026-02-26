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
  --dark: #03332c;
  --mid:  #4a8f82;
  --lite: #619b90;
  --text: #0f172a;
  --muted: #6b7280;
}

.dark-text {
  color: var(--dark);
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
  top:35%;
  width:160%;
  height:30%;
  background:var(--dark);
  transform:rotate(25deg);
  z-index:0;
}

/* bottom left dark diagonal band */
.front::after{
  content:"";
  position:absolute;
  left:-40%;
  bottom:-66%;
  width:170%;
  height:55%;
  background:var(--dark);
  transform:rotate(30deg);
  z-index:7;
}

/* mid color diagonal band, higher, shallower angle */
.front .band-mid{
  position:absolute;
  left:-40%;
  bottom:-46%;
  width:170%;
  height:55%;
  background:var(--mid);
  transform:rotate(14deg);
  z-index:7;
}

/* dotted overlay - white-ish above dark backgrounds */
.front .dots{
  position:absolute;
  margin-top: 30px;
  inset:0;
  z-index:1;
  opacity:.55;
  background-image: radial-gradient(#ffffff 0.5px, transparent 0.5px);
  background-size: 10px 10px;
  pointer-events:none;
  mix-blend-mode:screen;
  /* filter:blur(0.6px); */
}

/* dotted overlay - grayish above white backgrounds */
.front .dots-dark{
  position:absolute;
  margin-top: 30px;
  inset:0;
  z-index:1;
  opacity:.25;
  background-image: radial-gradient(#888888 0.5px, transparent 0.5px);
  background-size: 10px 10px;
  pointer-events:none;
  mix-blend-mode:multiply;
  /* filter:blur(0.6px); */
}

/* small top-right corner accent */
.front .corner-tr{
  position:absolute;
  right:-20px;
  top:-26px;
  width:50px;
  height:40px;
  background:var(--dark);
  transform:rotate(29deg);
  z-index:8;
}

/* extra lighter band + thin white stripe (lower-left) */
.front .accent-band{
  position:absolute;
  left:-35%;
  bottom:29.5%;
  width:180%;
  height:2.5%;
  background:var(--lite);
  transform:rotate(25deg);
  z-index:6;
}
.front .accent-band-dark{
  position:absolute;
  left:-35%;
  bottom:23.5%;
  width:180%;
  height:2.5%;
  background:var(--dark);
  transform:rotate(25deg);
  z-index:8;
}
.front .accent-line{
  position:absolute;
  left:-35%;
  bottom:-14%;
  width:180%;
  height:40%;
  background:var(--lite);
  transform:rotate(25deg);
  z-index:6;
}

/* Header block (top-left) */
.front .brand{
  position:absolute;
  top:10px;
  left:18px;
  right:0;
  bottom: 1px;
  z-index:5;
  font-family:Montserrat, Arial, sans-serif;
}

.front .brand .dots3{
  display:flex;
  gap:2px;
  margin-bottom:2px;
  margin-left: 2px;
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
}

.below-topline {
  display:flex;
  align-items:stretch;
  height:100%;
  margin-right: 20px;
}

.vertlines{
  display:flex;
  flex-direction:column;
  align-items:stretch;
  gap:0;
  margin-right:5px;
  flex-shrink:0;
  width:2px;
}

.dark-vertline1,
.lightvertline,
.dark-vertline2{
  width:1px;
  flex-shrink:0;
}

.dark-vertline1{
  background:var(--dark);
  height:20%;
}

.lightvertline{
  background:white;
  height:15%;
}

.dark-vertline2{
  background:var(--dark);
  height:65%;
}

.front .brand .text-block{
  display:flex;
  align-items:stretch;
  flex:1;
}

.vertlines-right{
  display:flex;
  flex-direction:column;
  align-items:stretch;
  gap:0;
  margin-left:auto;
  margin-top:30%;
  margin-right:5px;
  flex-shrink:0;
  width:1px;
}

.vertlines-right .dark-vertline1{
  background:var(--dark);
  height:28%;
  width:1px;
}

.vertlines-right .lightvertline{
  background:white;
  height:15%;
  width:1px;
}

.vertlines-right .dark-vertline2{
  background:var(--dark);
  height:40%;
  width:1px;
}

.front .brand .title{
  font-weight:900;
  letter-spacing:1px;
  color:var(--mid);
  font-size:10px;
  line-height:0.9;
  width:100%;
  text-transform:uppercase;
  margin-top: 1px;
}
.front .brand .meta{
  margin-top:1px;
  font-size:4px;
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
  width:65px;
  height:65px;
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
  right:3px;
  top:39%;
  transform:translateY(-50%) rotate(180deg);
  writing-mode:vertical-rl;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:700;
  font-size:6px;
  color:#0f172a;
  z-index:7;
  opacity:.9;
}

/* Photo */
.front .photo-area{
  position:absolute;
  left:50%;
  transform:translateX(-50%);
  top:50px;
  width:70%;
  height:70%;
  z-index:1;
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
  left:32px;
  right:16px;
  bottom:30px;
  z-index:8;
  font-family:Montserrat, Arial, sans-serif;
}
.front .info .name{
  color:#ffffff;
  font-weight:800;
  font-size:12px;
  letter-spacing:1px;
  text-transform:uppercase;
  line-height:1.05;
  text-shadow:2px 0 0 rgba(0,0,0,.7);
}
.front .info .school-id{
  font-weight:800;
  font-size:10px;
  letter-spacing:1px;
  color:var(--dark);
}
.front .info .strand{
  font-weight:600;
  font-size:6px;
  color:#fff;
  margin-right: 50px;
  text-shadow:1px 1px 0 rgba(0,0,0,.7);
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
  max-width:100px;
  object-fit:contain;
  display:block;
  margin:0 auto;
}
.front .signature p{
  font-size:7px;
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


.back .em-title{
  font-style:italic;
  text-transform:uppercase;
  text-align:center;
  font-size:6px;
  margin-bottom:10px;
  font-family:Montserrat, Arial, sans-serif;
}

.back .em-box{
  border:1px solid #9ca3af;
  margin: 5px;
  padding:6px 2px;
  text-align:center;
}

.back .em-box .em-name{
  font-weight:800;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:10px;
  line-height:1.1;
}
.back .em-box .em-contact{
  margin-top:4px;
  font-weight:800;
  font-family:Montserrat, Arial, sans-serif;
  font-size:10px;
  letter-spacing:1px;
}
.back .em-box .em-addr{
  font-weight:800;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:10px;
  line-height:1.15;
}

.back .valid-title{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  text-transform:uppercase;
  font-size:6px;
  margin:3px 0 12px;
}

.back .years{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:800;
  font-size:10px;
  letter-spacing:1px;
  line-height:1.9;
  margin-bottom:5px;
}

.back .important{
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
  font-weight:800;
  font-size:10px;
  margin-bottom:5px;
  text-transform:uppercase;
}

.back ul.notes{
  margin:0;
  padding-left:18px;
  font-size:8px;
  line-height:1.35;
  color:#111827;
}
.back ul.notes li{ margin-bottom:1px; }

.back .principal{
  position:absolute;
  left:18px;
  right:18px;
  bottom:14px;
  text-align:center;
  font-family:Montserrat, Arial, sans-serif;
}
.back .principal .signature{
  height:20px;
  max-width:100px;
  object-fit:contain;
  display:block;
  margin:0 auto 6px;
}
.back .principal .pname{
  font-weight:800;
  font-size:8px;
  text-transform:uppercase;
}
.back .principal .ptitle{
  font-weight:800;
  font-size:8px;
}

.back .side-code{
  top:85%;
  font-size:8px;
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
    <p style="margin-top:10px;font-size:13px;color:#6b7280;">
      ⚠️ In the print dialog, set <strong>Scale to 100%</strong> (not "Fit to page") to ensure the card prints at standard CR80 size (54 × 86 mm).
    </p>
  </div>

  <div class="id-wrapper">

    <!-- FRONT -->
    <div class="id front">
      <div class="dots"></div>
      <div class="dots-dark"></div>
      <div class="corner-tr"></div>
      <div class="band-mid"></div>
      <div class="accent-band"></div>
      <div class="accent-band-dark"></div>
      <div class="accent-line"></div>

      <div class="brand">
        <div class="dots3"><span></span><span></span><span></span></div>
        <div class="topline"></div>
        <div class="below-topline">
            <div class="vertlines">
                <div class="dark-vertline1"></div>
                <div class="lightvertline"></div>
                <div class="dark-vertline2"></div>
            </div>
            <div class="text-block">
              <div>
                <div class="title">TAYSAN SENIOR<br/><span class="dark-text">HIGH SCHOOL</span></div>
                <div class="meta">
                  SCHOOL ID - {{ $profile->school_id }}<br/>
                  BRGY. MAHANADIONG, TAYSAN, BATANGAS
                </div>
              </div>
            </div>
            <div class="vertlines-right">
                <div class="dark-vertline1"></div>
                <div class="lightvertline"></div>
                <div class="dark-vertline2"></div>
            </div>
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
        <p>Student Signature</p>
      </div>
    </div>

    <!-- BACK -->
    <div class="id back">
      <div class="inner">
        <div class="em-box">
        <div class="em-title">PERSON TO BE NOTIFIED IN CASE OF EMERGENCY</div>
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