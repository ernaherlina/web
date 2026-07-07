<?php
session_start();
require_once __DIR__ . "/config/koneksi.php";

// Memanggil library FPDF
require('fpdf/fpdf.php');

// Membuat class PDF premium dengan fungsi desain tingkat lanjut
class PremiumPDF extends FPDF {
    // Fungsi membuat kotak dengan sudut melengkung
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';

        $MyArc = 4/3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_Arc($xc+$r*$MyArc, $yc-$r, $xc+$r, $yc-$r*$MyArc, $xc+$r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_Arc($xc+$r, $yc+$r*$MyArc, $xc+$r*$MyArc, $yc+$r, $xc, $yc+$r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_Arc($xc-$r*$MyArc, $yc+$r, $xc-$r, $yc+$r*$MyArc, $xc-$r, $yc);

        $xc = $x+$r;
        $yc = $y+$r;
        $this->_Arc($xc-$r, $yc-$r*$MyArc, $xc-$r*$MyArc, $yc-$r, $xc, $yc-$r);

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', 
            $x1*$this->k, ($h-$y1)*$this->k, 
            $x2*$this->k, ($h-$y2)*$this->k, 
            $x3*$this->k, ($h-$y3)*$this->k
        ));
    }

    // Fungsi untuk membuat garis putus-putus estetik
    function SetDash($black=null, $white=null) {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d', $black*$this->k, $white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
}

// Cek parameter id pendaftaran
if (!isset($_GET['id'])) {
    die("ID Pendaftaran tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data pendaftaran dari database
$query = mysqli_query($conn, "
    SELECT 
        p.*, 
        u.nama, 
        u.nim_nip, 
        u.no_hp, 
        u.jenis_kelamin 
    FROM pendaftaran p
    JOIN users u ON p.id_user = u.id
    WHERE p.id = '$id'
");

if(mysqli_num_rows($query) == 0){
    die("Data pendaftaran tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);

// =========================================
// KONFIGURASI KERTAS (A6: 105mm x 148mm)
// =========================================
$pdf = new PremiumPDF('P', 'mm', array(105, 148));
$pdf->SetAutoPageBreak(false);
$pdf->SetMargins(8, 8, 8);
$pdf->AddPage();

// Komposisi Palet Warna Eksekutif Klinik (Modern Teal & Blue)
$brand_blue  = array(10, 102, 194);   // Royal Corporate Blue
$brand_light = array(240, 246, 255);  // Ice Blue Background
$text_dark   = array(44, 62, 80);     // Charcoal Text
$text_muted  = array(127, 140, 141);  // Cool Gray Text
$border_gray = array(230, 235, 240);  // Clean Soft Divider

// =========================================
// 1. TOP BRANDING HEADER
// =========================================
if(file_exists('assets/logo_klinik.png')){
    // Menaruh logo tepat di tengah atas secara proporsional
    $pdf->Image('assets/logo_klinik.png', 46, 7, 13);
}

$pdf->SetY(22);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 4.5, 'KLINIK BTH TASIKMALAYA', 0, 1, 'C');

$pdf->SetTextColor($text_muted[0], $text_muted[1], $text_muted[2]);
$pdf->SetFont('Arial', '', 7.5);
$pdf->Cell(0, 3.5, 'Sistem Aplikasi Pendaftaran Layanan Medis Online', 0, 1, 'C');

// Aksentuasi Garis Pembatas Header yang Elegan
$pdf->SetDrawColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->SetLineWidth(0.4);
$pdf->Line(12, 33, 93, 33);

// Kotak hiasan kecil di tengah garis
$pdf->SetFillColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Rect(50.5, 32.3, 4, 1.4, 'F');

// =========================================
// 2. NOMOR ANTRIAN PLATINUM DESIGN
// =========================================
$pdf->SetY(38);
// Efek Bayangan Belakang Kotak Antrian (Soft Shadow Effect)
$pdf->SetDrawColor(240, 240, 240);
$pdf->SetFillColor(248, 249, 250);
$pdf->RoundedRect(31, 38, 43, 16, 2, 'DF');

// Kotak Utama Antrian dengan garis putus-putus biru corporate
$pdf->SetDrawColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->SetLineWidth(0.3);
$pdf->SetDash(1.5, 1);
$pdf->RoundedRect(32, 38, 41, 15, 2, 'D');
$pdf->SetDash(); // Reset garis normal

// Label Nomor Antrian
$pdf->SetXY(32, 39.5);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->SetTextColor($text_muted[0], $text_muted[1], $text_muted[2]);
$pdf->Cell(41, 3, 'NOMOR ANTRIAN', 0, 1, 'C');

// Angka Antrian Besar Berwarna Kontras Tegas
$pdf->SetX(32);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Cell(41, 9, $data['nomor_antrian'], 0, 1, 'C');

// =========================================
// 3. MAIN DATA CARD (BIODATA RESMI)
// =========================================
$y_card = 57;

// Membuat Base Card Putih dengan bayangan halus disekitarnya
$pdf->SetDrawColor(218, 224, 233);
$pdf->SetFillColor(255, 255, 255);
$pdf->RoundedRect(8, $y_card, 89, 56, 3, 'DF');

// Judul di dalam area Card Data Pasien
$pdf->SetXY(12, $y_card + 2.5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Cell(0, 4, 'INFORMASI PENDAFTARAN PASIEN', 0, 1, 'L');

// Garis tipis pembatas interior judul card
$pdf->SetDrawColor($border_gray[0], $border_gray[1], $border_gray[2]);
$pdf->SetLineWidth(0.2);
$pdf->Line(12, $pdf->GetY() + 1, 93, $pdf->GetY() + 1);
$pdf->SetY($pdf->GetY() + 3.5);

// Fungsi custom render baris data klinis agar terlihat presisi tinggi
if (!function_exists('RenderPremiumRow')) {
    function RenderPremiumRow($pdf, $label, $value, $text_dark, $text_muted, $border_gray, $is_status = false, $status_text = 'MENUNGGU') {
        $pdf->SetX(12);
        
        // Sisi Kiri: Label Data
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor($text_muted[0], $text_muted[1], $text_muted[2]);
        $pdf->Cell(23, 4.2, $label, 0, 0);
        
        // Titik Dua Pembatas Menengah
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->Cell(3, 4.2, ':', 0, 0, 'C');
        
        // Sisi Kanan: Nilai Data Dinamis
        if ($is_status) {
            $cX = $pdf->GetX();
            $cY = $pdf->GetY();
            // Desain Badge Status Melengkung yang Indah & Fresh
            $pdf->SetFillColor(232, 245, 233); // Soft Light Green
            $pdf->RoundedRect($cX, $cY + 0.5, 22, 3.8, 1, 'F');
            $pdf->SetTextColor(46, 125, 50);   // Emerald Deep Green
            $pdf->SetFont('Arial', 'B', 6.5);
            $pdf->Cell(22, 4.5, strtoupper($status_text), 0, 1, 'C');
        } else {
            $pdf->SetFont('Arial', 'B', 7.5);
            $pdf->SetTextColor($text_dark[0], $text_dark[1], $text_dark[2]);
            $pdf->Cell(58, 4.2, $value, 0, 1, 'L');
        }
        
        // Render Garis Pembatas Horisontal Tipis Estetik tiap baris informasi
        $pdf->SetDrawColor($border_gray[0], $border_gray[1], $border_gray[2]);
        $pdf->SetLineWidth(0.15);
        $pdf->Line(12, $pdf->GetY(), 93, $pdf->GetY());
        $pdf->SetY($pdf->GetY() + 0.8);
    }
}

// Mengisi data rekam medis secara runtut dan proporsional
RenderPremiumRow($pdf, 'Nama Pasien', $data['nama'], $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Dokter Medis', $data['dokter'], $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Poliklinik', !empty($data['poli']) ? $data['poli'] : 'Poliklinik Umum', $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Jadwal Periksa', date('d F Y', strtotime($data['tanggal_periksa'])), $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Estimasi Jam', date('H:i', strtotime($data['jam_periksa'])) . ' WIB', $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Keluhan Utama', $data['keluhan'], $text_dark, $text_muted, $border_gray);
RenderPremiumRow($pdf, 'Status Antrian', '', $text_dark, $text_muted, $border_gray, true, $data['status'] ?? 'MENUNGGU');

// =========================================
// 4. NOTICE BOX ALERT (BIRU MODERN)
// =========================================
$pdf->SetY(116);
$pdf->SetX(8);
$pdf->SetFillColor($brand_light[0], $brand_light[1], $brand_light[2]); // Latar Ice Blue
$pdf->RoundedRect(8, 116, 89, 9, 1.5, 'F');

$pdf->SetXY(11, 117.2);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Cell(0, 3.2, "* PENTING: Harap hadir di klinik 15 menit sebelum estimasi jam pemeriksaan.", 0, 1);
$pdf->SetX(11);
$pdf->SetFont('Arial', '', 6.5);
$pdf->SetTextColor($text_dark[0], $text_dark[1], $text_dark[2]);
$pdf->Cell(0, 2.5, "Tunjukkan file PDF atau struk cetak digital ini langsung kepada petugas pendaftaran.", 0, 1);

// =========================================
// 5. FOOTER INFORMASI INSTITUSI
// =========================================
$pdf->SetXY(8, 128);
$pdf->SetDrawColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->SetLineWidth(0.2);
$pdf->Line(8, 128, 97, 128); // Garis bawah pembatas

// Alamat Sisi Kiri Footer
$pdf->SetY(130);
$pdf->SetX(8);
$pdf->SetFont('Arial', '', 6.5);
$pdf->SetTextColor($text_muted[0], $text_muted[1], $text_muted[2]);
$pdf->Cell(50, 3, 'Jl. Cilolohan No. 36, Kahuripan, Tawang,', 0, 0, 'L');

// Greeting Sisi Kanan Footer
$pdf->SetFont('Arial', 'I', 7);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Cell(39, 3, 'Health and Care Priority', 0, 1, 'R');

$pdf->SetX(8);
$pdf->SetFont('Arial', '', 6.5);
$pdf->SetTextColor($text_muted[0], $text_muted[1], $text_muted[2]);
$pdf->Cell(50, 2.5, 'Kota Tasikmalaya | Telp: (0265) 123456', 0, 0, 'L');

// Waktu Cetak Realtime Otomatis
date_default_timezone_set('Asia/Jakarta');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(39, 2.5, 'Valid: ' . date('d-m-Y H:i') . ' WIB', 0, 1, 'R');

$pdf->SetX(8);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->SetTextColor($brand_blue[0], $brand_blue[1], $brand_blue[2]);
$pdf->Cell(50, 3, 'www.klinikbth.co.id', 0, 1, 'L');

// =========================================
// AJUSTMEN DASAR / AKSEN STRIP WARNA PREMIUM
// =========================================
// Strip Aksen Bawah (Dua warna eksklusif pelengkap identitas visual)
$pdf->SetFillColor(255, 193, 7); // Kuning Gold hangat
$pdf->Rect(0, 143.5, 105, 1, 'F');
$pdf->SetFillColor($brand_blue[0], $brand_blue[1], $brand_blue[2]); // Biru Utama
$pdf->Rect(0, 144.5, 105, 3.5, 'F');

// =========================================
// STREAM OUTPUT GENERATOR
// =========================================
if (ob_get_contents()) ob_end_clean();
$pdf->Output('I', 'Bukti_Pendaftaran_'.$data['nomor_antrian'].'.pdf');
exit;
?>