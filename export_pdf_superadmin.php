<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location:login.php");
    exit;
}

require('fpdf/fpdf.php');

date_default_timezone_set("Asia/Jakarta");

/* ================= FILTER ================= */

$tgl_awal  = $_GET['tgl_awal'] ?? "";
$tgl_akhir = $_GET['tgl_akhir'] ?? "";
$dokter    = $_GET['dokter'] ?? "";
$poli      = $_GET['poli'] ?? "";

$where="1=1";

if($tgl_awal!=""){
    $where.=" AND DATE(p.tanggal_periksa)>='$tgl_awal'";
}

if($tgl_akhir!=""){
    $where.=" AND DATE(p.tanggal_periksa)<='$tgl_akhir'";
}

if($dokter!=""){
    $dokter=mysqli_real_escape_string($conn,$dokter);
    $where.=" AND d.nama_dokter='$dokter'";
}

if($poli!=""){
    $poli=mysqli_real_escape_string($conn,$poli);
    $where.=" AND d.poli='$poli'";
}

$query=mysqli_query($conn,"
SELECT

p.nomor_antrian,

u.nama,

u.no_rm,

d.nama_dokter,

d.poli,

p.keluhan,

pm.diagnosa,

pm.status,

pm.tanggal_pemeriksaan,

IFNULL(

(
SELECT SUM(ro.jumlah*o.harga)

FROM resep r

JOIN resep_obat ro
ON r.id=ro.id_resep

JOIN obat o
ON ro.id_obat=o.id

WHERE r.id_pemeriksaan=pm.id

)

,0) total_biaya

FROM pemeriksaan pm

JOIN pendaftaran p
ON pm.id_pendaftaran=p.id

JOIN users u
ON p.id_user=u.id

JOIN dokter d
ON pm.id_dokter=d.id

WHERE $where

ORDER BY pm.tanggal_pemeriksaan DESC
");

$pdf=new FPDF('L','mm','A4');

$pdf->AddPage();

$pdf->SetFont('Arial','B',18);

$pdf->Cell(0,10,'LAPORAN KLINIK BTH',0,1,'C');

$pdf->SetFont('Arial','',10);

$pdf->Cell(0,6,'Tanggal Cetak : '.date('d F Y H:i'),0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);

$pdf->SetFillColor(37,99,235);
$pdf->SetTextColor(255,255,255);

$pdf->Cell(10,8,'No',1,0,'C',true);
$pdf->Cell(22,8,'Antrian',1,0,'C',true);
$pdf->Cell(42,8,'Pasien',1,0,'C',true);
$pdf->Cell(22,8,'No RM',1,0,'C',true);
$pdf->Cell(45,8,'Dokter',1,0,'C',true);
$pdf->Cell(22,8,'Poli',1,0,'C',true);
$pdf->Cell(55,8,'Diagnosa',1,0,'C',true);
$pdf->Cell(25,8,'Status',1,0,'C',true);
$pdf->Cell(32,8,'Biaya',1,1,'C',true);

$pdf->SetFont('Arial','',8);

$pdf->SetTextColor(0,0,0);

$no=1;

$total=0;

while($d=mysqli_fetch_assoc($query)){

$total += $d['total_biaya'];

$pdf->Cell(10,8,$no++,1,0,'C');

$pdf->Cell(22,8,$d['nomor_antrian'],1,0,'C');

$pdf->Cell(42,8,substr($d['nama'],0,24),1);

$pdf->Cell(22,8,$d['no_rm'],1,0,'C');

$pdf->Cell(45,8,substr($d['nama_dokter'],0,28),1);

$pdf->Cell(22,8,$d['poli'],1,0,'C');

$pdf->Cell(55,8,substr($d['diagnosa'],0,35),1);

$pdf->Cell(25,8,$d['status'],1,0,'C');

$pdf->Cell(
32,
8,
'Rp '.number_format($d['total_biaya'],0,",","."),
1,
1,
'R'
);

}

$pdf->SetFont('Arial','B',10);

$pdf->Cell(
221,
9,
'TOTAL PENDAPATAN',
1,
0,
'R'
);

$pdf->Cell(
32,
9,
'Rp '.number_format($total,0,",","."),
1,
1,
'R'
);

$pdf->Ln(10);

$pdf->SetFont('Arial','',10);

$pdf->Cell(
0,
6,
'Tasikmalaya, '.date('d F Y'),
0,
1,
'R'
);

$pdf->Ln(12);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(
0,
6,
'Super Admin Klinik BTH',
0,
1,
'R'
);

$pdf->Ln(18);

$pdf->Cell(
0,
6,
$_SESSION['nama'],
0,
1,
'R'
);

$pdf->Output(
'I',
'Laporan_Super_Admin.pdf'
);

exit;