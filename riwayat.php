<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];

/* ===========================
   RIWAYAT PENDAFTARAN
=========================== */

$query = mysqli_query($conn,"
SELECT
    pd.id,
    pd.nomor_antrian,
    pd.tanggal_periksa,
    pd.jam_periksa,
    pd.status,

    d.nama_dokter

FROM pendaftaran pd

LEFT JOIN dokter d
ON pd.id_dokter=d.id

WHERE pd.id_user='$id_user'

ORDER BY
pd.tanggal_periksa DESC,
pd.jam_periksa DESC
");


/* ===========================
   CARD DASHBOARD
=========================== */

$qTotal=mysqli_query($conn,"
SELECT COUNT(*) total
FROM pendaftaran
WHERE id_user='$id_user'
");

$total=mysqli_fetch_assoc($qTotal)['total'];


$qSelesai=mysqli_query($conn,"
SELECT COUNT(*) total
FROM pendaftaran
WHERE id_user='$id_user'
AND status='Selesai'
");

$selesai=mysqli_fetch_assoc($qSelesai)['total'];


$qResep=mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
JOIN pemeriksaan pm
ON r.id_pemeriksaan=pm.id
JOIN pendaftaran pd
ON pm.id_pendaftaran=pd.id
WHERE pd.id_user='$id_user'
");

$totalResep=mysqli_fetch_assoc($qResep)['total'];


$qDiambil=mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
JOIN pemeriksaan pm
ON r.id_pemeriksaan=pm.id
JOIN pendaftaran pd
ON pm.id_pendaftaran=pd.id
WHERE
pd.id_user='$id_user'
AND r.status='Sudah Diambil'
");

$diambil=mysqli_fetch_assoc($qDiambil)['total'];

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Riwayat Berobat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f3f7fc;

}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-5">

<div class="bg-white rounded-3xl shadow overflow-hidden">

<div class="bg-gradient-to-r from-blue-600 to-cyan-500 p-5">

<div class="flex justify-between items-center">

<div>

<h1 class="text-3xl font-bold text-white">

🩺 Riwayat Berobat

</h1>

<p class="text-blue-100 text-sm mt-1">

Seluruh riwayat pemeriksaan Anda

</p>

</div>

<a href="dashboard.php"

class="bg-white text-blue-600 px-5 py-2 rounded-xl font-semibold hover:bg-blue-50">

← Dashboard

</a>

</div>

</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-5">

<div class="bg-blue-50 rounded-2xl p-4">

<p class="text-sm text-blue-600">

Total Berobat

</p>

<h2 class="text-3xl font-bold mt-2">

<?= $total ?>

</h2>

</div>

<div class="bg-green-50 rounded-2xl p-4">

<p class="text-sm text-green-600">

Pemeriksaan

</p>

<h2 class="text-3xl font-bold mt-2">

<?= $selesai ?>

</h2>

</div>

<div class="bg-orange-50 rounded-2xl p-4">

<p class="text-sm text-orange-600">

Resep

</p>

<h2 class="text-3xl font-bold mt-2">

<?= $totalResep ?>

</h2>

</div>

<div class="bg-purple-50 rounded-2xl p-4">

<p class="text-sm text-purple-600">

Diambil

</p>

<h2 class="text-3xl font-bold mt-2">

<?= $diambil ?>

</h2>

</div>

</div>

<div class="px-5 pb-5">

<h2 class="text-2xl font-bold text-gray-800 mb-4">

Riwayat Pemeriksaan

</h2>

<?php if(mysqli_num_rows($query)>0){ ?>

<?php while($data=mysqli_fetch_assoc($query)){ ?>

<div class="bg-gray-50 hover:bg-blue-50 border border-gray-200 rounded-2xl p-5 mb-4 transition duration-300">

<div class="flex flex-col md:flex-row md:justify-between md:items-center">

<div>

<p class="text-sm text-gray-500">

📅 <?= date('d F Y',strtotime($data['tanggal_periksa'])) ?>

&nbsp;&nbsp;&nbsp;

🕒 <?= substr($data['jam_periksa'],0,5) ?> WIB

</p>

<h3 class="text-xl font-bold text-blue-600 mt-2">

<?= htmlspecialchars($data['nomor_antrian']) ?>

</h3>

<p class="text-gray-700 mt-1">

👨‍⚕️

<?= htmlspecialchars($data['nama_dokter'] ?? '-') ?>

</p>

</div>

<div class="mt-4 md:mt-0 text-right">

<?php

$status=$data['status'];

switch($status){

case 'Selesai':

$warna="bg-green-100 text-green-700";

break;

case 'Dipanggil':

$warna="bg-blue-100 text-blue-700";

break;

case 'Menunggu':

$warna="bg-orange-100 text-orange-700";

break;

default:

$warna="bg-gray-100 text-gray-600";

}

?>

<span class="<?= $warna ?> px-4 py-2 rounded-full text-sm font-semibold">

<?= $status ?>

</span>

<br>

<a

href="detail_riwayat.php?id=<?= $data['id'] ?>"

class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm">

👁 Lihat Detail

</a>

</div>

</div>

</div>

<?php } ?>

<?php }else{ ?>

<div class="bg-gray-50 rounded-2xl p-10 text-center">

<div class="text-6xl">

📋

</div>

<h2 class="text-2xl font-bold mt-4">

Belum Ada Riwayat Berobat

</h2>

<p class="text-gray-500 mt-2">

Riwayat pemeriksaan akan muncul setelah Anda melakukan pemeriksaan.

</p>

</div>

<?php } ?>

</div>

