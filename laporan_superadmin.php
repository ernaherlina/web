<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location:login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

/*====================================================
=                    FILTER                          =
====================================================*/

$tgl_awal  = $_GET['tgl_awal']  ?? "";
$tgl_akhir = $_GET['tgl_akhir'] ?? "";
$dokter    = $_GET['dokter']    ?? "";
$poli      = $_GET['poli']      ?? "";

$where = "1=1";

if($tgl_awal!=""){
    $where .= " AND DATE(p.tanggal_periksa)>='$tgl_awal'";
}

if($tgl_akhir!=""){
    $where .= " AND DATE(p.tanggal_periksa)<='$tgl_akhir'";
}

if($dokter!=""){
    $dokter=mysqli_real_escape_string($conn,$dokter);
    $where .= " AND d.nama_dokter='$dokter'";
}

if($poli!=""){
    $poli=mysqli_real_escape_string($conn,$poli);
    $where .= " AND d.poli='$poli'";
}

/*====================================================
=                    STATISTIK                       =
====================================================*/

$totalPasien=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='user'
"))['total'];

$totalPeriksa=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM pemeriksaan
"))['total'];

$totalResep=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
"))['total'];

$totalDokter=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM dokter
"))['total'];

$totalPendapatan=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
IFNULL(SUM(ro.jumlah*o.harga),0) total
FROM resep_obat ro
JOIN obat o
ON ro.id_obat=o.id
"))['total'];

if(!$totalPendapatan){
    $totalPendapatan=0;
}

/*====================================================
=                LIST FILTER                         =
====================================================*/

$listDokter=mysqli_query($conn,"
SELECT nama_dokter
FROM dokter
ORDER BY nama_dokter
");

$listPoli=mysqli_query($conn,"
SELECT DISTINCT poli
FROM dokter
ORDER BY poli
");

/*====================================================
=                 DATA LAPORAN                       =
====================================================*/

$data=mysqli_query($conn,"
SELECT

pm.id,

u.nama,

u.no_rm,

d.nama_dokter,

d.poli,

p.nomor_antrian,

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

,0) AS total_biaya

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

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Super Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f1f5f9;
color:#334155;
}

.card{
background:white;
border-radius:16px;
padding:18px;
border:1px solid #e2e8f0;
box-shadow:0 2px 8px rgba(0,0,0,.04);
transition:.2s;
}

.card:hover{
transform:translateY(-3px);
}

.title{
font-size:24px;
font-weight:700;
color:#1e3a8a;
}

.subtitle{
font-size:13px;
color:#64748b;
margin-top:3px;
}

.stat-title{
font-size:12px;
color:#64748b;
}

.stat-value{
font-size:26px;
font-weight:700;
margin-top:6px;
}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-6">

<!-- ================= HEADER ================= -->

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="title">

Laporan Super Admin

</h1>

<p class="subtitle">

Monitoring seluruh aktivitas Klinik BTH

</p>

</div>

<div class="flex gap-3">

<a
href="dashboard_superadmin.php"
class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl text-sm">

Dashboard

</a>

<a
href="javascript:window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm">

🖨 Print

</a>

</div>

</div>

<!-- ================= CARD STATISTIK ================= -->

<div class="grid grid-cols-5 gap-4 mb-6">

<div class="card">

<div class="flex justify-between">

<div>

<div class="stat-title">

Total Pasien

</div>

<div class="stat-value text-blue-600">

<?= $totalPasien; ?>

</div>

</div>

<div class="text-3xl">

👥

</div>

</div>

</div>

<div class="card">

<div class="flex justify-between">

<div>

<div class="stat-title">

Pemeriksaan

</div>

<div class="stat-value text-green-600">

<?= $totalPeriksa; ?>

</div>

</div>

<div class="text-3xl">

🩺

</div>

</div>

</div>

<div class="card">

<div class="flex justify-between">

<div>

<div class="stat-title">

Resep

</div>

<div class="stat-value text-orange-600">

<?= $totalResep; ?>

</div>

</div>

<div class="text-3xl">

💊

</div>

</div>

</div>

<div class="card">

<div class="flex justify-between">

<div>

<div class="stat-title">

Dokter

</div>

<div class="stat-value text-purple-600">

<?= $totalDokter; ?>

</div>

</div>

<div class="text-3xl">

👨‍⚕️

</div>

</div>

</div>

<div class="rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white p-5">

<div class="text-sm opacity-80">

Total Pendapatan

</div>

<div class="text-2xl font-bold mt-3">

Rp <?= number_format($totalPendapatan,0,',','.'); ?>

</div>

</div>

</div>

<!-- ================= FILTER ================= -->

<div class="bg-white rounded-2xl shadow border border-slate-200 p-6 mb-6">

<form method="GET">

<div class="grid grid-cols-6 gap-4">

<!-- Tanggal Awal -->

<div>

<label class="text-sm font-medium text-slate-600">

Tanggal Awal

</label>

<input
type="date"
name="tgl_awal"
value="<?= htmlspecialchars($tgl_awal); ?>"
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- Tanggal Akhir -->

<div>

<label class="text-sm font-medium text-slate-600">

Tanggal Akhir

</label>

<input
type="date"
name="tgl_akhir"
value="<?= htmlspecialchars($tgl_akhir); ?>"
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- Dokter -->

<div>

<label class="text-sm font-medium text-slate-600">

Dokter

</label>

<select
name="dokter"
class="w-full border rounded-xl p-3 mt-2">

<option value="">

Semua Dokter

</option>

<?php while($dr=mysqli_fetch_assoc($listDokter)){ ?>

<option
value="<?= $dr['nama_dokter']; ?>"
<?= ($dokter==$dr['nama_dokter'])?'selected':''; ?>>

<?= $dr['nama_dokter']; ?>

</option>

<?php } ?>

</select>

</div>

<!-- Poli -->

<div>

<label class="text-sm font-medium text-slate-600">

Poli

</label>

<select
name="poli"
class="w-full border rounded-xl p-3 mt-2">

<option value="">

Semua Poli

</option>

<?php while($pl=mysqli_fetch_assoc($listPoli)){ ?>

<option
value="<?= $pl['poli']; ?>"
<?= ($poli==$pl['poli'])?'selected':''; ?>>

<?= $pl['poli']; ?>

</option>

<?php } ?>

</select>

</div>

<!-- Tombol Cari -->

<div class="flex items-end">

<button
type="submit"
class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-3 font-medium">

🔍 Cari

</button>

</div>

<!-- Reset -->

<div class="flex items-end">

<a
href="laporan_superadmin.php"
class="w-full text-center bg-gray-500 hover:bg-gray-600 text-white rounded-xl p-3 font-medium">

Reset

</a>

</div>

</div>

</form>

</div>

<!-- ================= TOOLBAR ================= -->

<div class="flex justify-between items-center mb-5">

<div>

<h2 class="text-xl font-bold text-slate-700">

Data Laporan Pemeriksaan

</h2>

<p class="text-sm text-slate-500 mt-1">

Total Data :
<b><?= mysqli_num_rows($data); ?></b>

</p>

</div>

<div class="flex gap-3">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

🖨 Print

</button>

<a
href="export_excel_superadmin.php?
tgl_awal=<?= $tgl_awal; ?>
&tgl_akhir=<?= $tgl_akhir; ?>
&dokter=<?= urlencode($dokter); ?>
&poli=<?= urlencode($poli); ?>"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl">

📊 Excel

</a>

<a
href="export_pdf_superadmin.php?
tgl_awal=<?= $tgl_awal; ?>
&tgl_akhir=<?= $tgl_akhir; ?>
&dokter=<?= urlencode($dokter); ?>
&poli=<?= urlencode($poli); ?>"
class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl">

📄 PDF

</a>

</div>

</div>

<!-- ================= TABEL LAPORAN ================= -->

<div class="bg-white rounded-2xl shadow overflow-hidden">

<div class="overflow-x-auto">

<table class="min-w-full">

<thead class="bg-blue-600 text-white">

<tr>

<th class="px-4 py-4 text-center">No</th>

<th class="px-4 py-4 text-center">No Antrian</th>

<th class="px-4 py-4 text-left">Nama Pasien</th>

<th class="px-4 py-4 text-center">No RM</th>

<th class="px-4 py-4 text-left">Dokter</th>

<th class="px-4 py-4 text-center">Poli</th>

<th class="px-4 py-4 text-left">Keluhan</th>

<th class="px-4 py-4 text-left">Diagnosa</th>

<th class="px-4 py-4 text-center">Tanggal</th>

<th class="px-4 py-4 text-center">Status</th>

<th class="px-4 py-4 text-right">Biaya</th>

</tr>

</thead>

<tbody>

<?php

$no=1;
$totalBiaya=0;

while($r=mysqli_fetch_assoc($data)){

$totalBiaya += $r['total_biaya'];

?>

<tr class="border-b hover:bg-slate-50 transition">

<td class="px-4 py-3 text-center">

<?= $no++; ?>

</td>

<td class="px-4 py-3 text-center font-semibold text-blue-600">

<?= $r['nomor_antrian']; ?>

</td>

<td class="px-4 py-3">

<div class="font-semibold">

<?= $r['nama']; ?>

</div>

</td>

<td class="px-4 py-3 text-center">

<?= empty($r['no_rm']) ? "-" : $r['no_rm']; ?>

</td>

<td class="px-4 py-3">

<?= $r['nama_dokter']; ?>

</td>

<td class="px-4 py-3 text-center">

<?= $r['poli']; ?>

</td>

<td class="px-4 py-3">

<?= $r['keluhan']; ?>

</td>

<td class="px-4 py-3">

<?= $r['diagnosa']; ?>

</td>

<td class="px-4 py-3 text-center">

<?= date('d-m-Y',strtotime($r['tanggal_pemeriksaan'])); ?>

<br>

<span class="text-xs text-gray-500">

<?= date('H:i',strtotime($r['tanggal_pemeriksaan'])); ?>

</span>

</td>

<td class="px-4 py-3 text-center">

<?php

if($r['status']=="Selesai"){

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>';

}else if($r['status']=="Diproses"){

echo '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Diproses</span>';

}else{

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">'.$r['status'].'</span>';

}

?>

</td>

<td class="px-4 py-3 text-right font-semibold text-green-600">

Rp <?= number_format($r['total_biaya'],0,",","."); ?>

</td>

</tr>

<?php } ?>

</tbody>

<tfoot class="bg-slate-100">

<tr>

<td colspan="10" class="px-4 py-4 text-right font-bold">

TOTAL PENDAPATAN

</td>

<td class="px-4 py-4 text-right font-bold text-green-700">

Rp <?= number_format($totalBiaya,0,",","."); ?>

</td>

</tr>

</tfoot>

</table>

</div>

</div>