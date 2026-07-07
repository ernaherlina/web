<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

$hari_ini = date('Y-m-d');

/* ===========================
   CARD DASHBOARD
=========================== */

$total_verifikasi = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE status='Menunggu Verifikasi'
"));

$total_menunggu = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE status='Menunggu'
"));

$total_dipanggil = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE status='Dipanggil'
"));

$total_diperiksa = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE status='Diperiksa'
"));

$total_selesai = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE status='Selesai'
"));


/* ===========================
   DATA VERIFIKASI
=========================== */

$data_verifikasi = mysqli_query($conn,"
SELECT
    p.*,
    u.nama,
    d.nama_dokter

FROM pendaftaran p

JOIN users u
ON u.id = p.id_user

LEFT JOIN dokter d
ON d.id = p.id_dokter

WHERE p.status='Menunggu Verifikasi'

ORDER BY
p.tanggal_periksa ASC,
p.jam_periksa ASC
");

/* ===========================
   DATA ANTRIAN HARI INI
=========================== */

$data_antrian = mysqli_query($conn,"
SELECT
pendaftaran.*,
users.nama
FROM pendaftaran
JOIN users
ON users.id = pendaftaran.id_user
WHERE status='Menunggu'
ORDER BY tanggal_periksa ASC,
jam_periksa ASC
");

/* ===========================
   DATA DIPANGGIL
=========================== */

$data_dipanggil = mysqli_query($conn,"
SELECT
pendaftaran.*,
users.nama
FROM pendaftaran
JOIN users
ON users.id = pendaftaran.id_user
WHERE status='Dipanggil'
ORDER BY tanggal_periksa ASC,
jam_periksa ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;

background:#f3f6fb;

}

.sidebar{

width:220px;

}

.card{

border-radius:18px;

box-shadow:0 2px 10px rgba(0,0,0,.08);

}

table td{

font-size:14px;

}

table th{

font-size:14px;

}

</style>

</head>

<body>

<div class="flex min-h-screen">

<!-- SIDEBAR -->

<div class="sidebar bg-white p-5 shadow-lg">

<h1 class="text-3xl font-bold text-blue-600">

Klinik BTH

</h1>

<p class="text-gray-500 text-sm">

Dashboard Admin

</p>

<div class="mt-8 space-y-2">

<a href="dashboard_admin.php"
class="block bg-blue-50 text-blue-600 px-4 py-3 rounded-xl">

Dashboard

</a>

<a href="data_verifikasi.php"
class="block hover:bg-gray-100 px-4 py-3 rounded-xl">

Data Verifikasi

</a>

<a href="antrian_hari_ini.php"
class="block hover:bg-gray-100 px-4 py-3 rounded-xl">

Antrian Hari Ini

</a>

<a href="pasien_dipanggil.php"
class="block hover:bg-gray-100 px-4 py-3 rounded-xl">

Pasien Dipanggil

</a>

<a href="logout.php"
class="block mt-10 bg-red-500 text-white text-center py-3 rounded-xl">

Logout

</a>

</div>

</div>

<!-- CONTENT -->

<div class="flex-1 p-6">

<div class="flex justify-between items-center">

<div>

<h1 class="text-3xl font-bold">

Dashboard Admin 👨‍⚕️

</h1>

<p class="text-gray-500">

Kelola seluruh antrian pasien

</p>

</div>

</div>

<!-- CARD -->

<div class="grid grid-cols-5 gap-4 mt-6">

<div class="card bg-white p-4">

<p class="text-gray-500 text-sm">

Verifikasi

</p>

<h1 class="text-3xl font-bold text-red-500 mt-2">

<?= $total_verifikasi['total']; ?>

</h1>

</div>

<div class="card bg-white p-4">

<p class="text-gray-500 text-sm">

Menunggu

</p>

<h1 class="text-3xl font-bold text-orange-500 mt-2">

<?= $total_menunggu['total']; ?>

</h1>

</div>

<div class="card bg-white p-4">

<p class="text-gray-500 text-sm">

Dipanggil

</p>

<h1 class="text-3xl font-bold text-blue-500 mt-2">

<?= $total_dipanggil['total']; ?>

</h1>

</div>

<div class="card bg-white p-4">

<p class="text-gray-500 text-sm">

Diperiksa

</p>

<h1 class="text-3xl font-bold text-purple-500 mt-2">

<?= $total_diperiksa['total']; ?>

</h1>

</div>

<div class="card bg-white p-4">

<p class="text-gray-500 text-sm">

Selesai

</p>

<h1 class="text-3xl font-bold text-green-500 mt-2">

<?= $total_selesai['total']; ?>

</h1>

</div>

</div>


<!-- ===========================================
VERIFIKASI PASIEN
=========================================== -->

<div id="verifikasi" class="mt-8 bg-white rounded-2xl shadow overflow-hidden">

    <div class="px-5 py-4 border-b">

        <h2 class="text-xl font-bold">

            Pasien Menunggu Verifikasi

        </h2>

    </div>

    <table class="w-full">

        <thead class="bg-blue-600 text-white">

            <tr>

                <th class="p-3 text-left">No</th>

                <th class="p-3 text-left">Nama</th>

                <th class="p-3 text-left">Dokter</th>

                <th class="p-3 text-left">Tanggal</th>

                <th class="p-3 text-left">Jam</th>

                <th class="p-3 text-left">Keluhan</th>

                <th class="p-3 text-center">Aksi</th>

            </tr>

        </thead>

        <tbody>

<?php

if(mysqli_num_rows($data_verifikasi)>0){

$no=1;

while($row=mysqli_fetch_assoc($data_verifikasi)){

?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3">
<?= $no++; ?>
</td>

<td class="p-3 font-semibold">
<?= $row['nama']; ?>
</td>

<td class="p-3">
<?= $row['nama_dokter']; ?>
</td>

<td class="p-3">
<?= date('d-m-Y',strtotime($row['tanggal_periksa'])); ?>
</td>

<td class="p-3">
<?= date('H:i',strtotime($row['jam_periksa'])); ?>
</td>

<td class="p-3">
<?= $row['keluhan']; ?>
</td>

<td class="p-3 text-center">

<a
href="verifikasi_pasien.php?id=<?= $row['id']; ?>"
class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">

Verifikasi

</a>

<a
href="batalkan.php?id=<?= $row['id']; ?>"
class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm ml-2">

Batalkan

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7"
class="text-center p-6 text-gray-500">

Belum ada pasien menunggu verifikasi

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>





<!-- ===========================================
ANTRIAN HARI INI
=========================================== -->

<div id="antrian"
class="mt-8 bg-white rounded-2xl shadow overflow-hidden">

<div class="px-5 py-4 border-b">

<h2 class="text-xl font-bold">

Antrian Hari Ini

</h2>

</div>

<table class="w-full">

<thead class="bg-orange-500 text-white">

<tr>

<th class="p-3 text-left">

No

</th>

<th class="p-3 text-left">

Antrian

</th>

<th class="p-3 text-left">

Nama

</th>

<th class="p-3 text-left">

Dokter

</th>

<th class="p-3 text-left">

Jam

</th>

<th class="p-3 text-left">

Status

</th>

<th class="p-3 text-center">

Aksi

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data_antrian)>0){

$no=1;

while($row=mysqli_fetch_assoc($data_antrian)){

?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3">

<?= $no++; ?>

</td>

<td class="p-3 font-bold text-blue-600">

<?= $row['nomor_antrian']; ?>

</td>

<td class="p-3">

<?= $row['nama']; ?>

</td>

<td class="p-3">

<?= $row['dokter']; ?>

</td>

<td class="p-3">

<?= date('H:i',strtotime($row['jam_periksa'])); ?>

</td>

<td class="p-3">

<span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs">

<?= $row['status']; ?>

</span>

</td>

<td class="p-3 text-center">

<a
href="ubah_status.php?id=<?= $row['id']; ?>&status=Dipanggil"
class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">

Panggil

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7"
class="text-center p-6 text-gray-500">

Belum ada antrian hari ini

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<!-- ===========================================
PASIEN DIPANGGIL
=========================================== -->

<div id="dipanggil"
class="mt-8 bg-white rounded-2xl shadow overflow-hidden">

<div class="px-5 py-4 border-b">

<h2 class="text-xl font-bold">

Pasien Dipanggil

</h2>

</div>

<table class="w-full">

<thead class="bg-cyan-600 text-white">

<tr>

<th class="p-3 text-left">No</th>

<th class="p-3 text-left">Antrian</th>

<th class="p-3 text-left">Nama</th>

<th class="p-3 text-left">Dokter</th>

<th class="p-3 text-left">Jam</th>

<th class="p-3 text-left">Status</th>

<th class="p-3 text-center">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data_dipanggil)>0){

$no=1;

while($row=mysqli_fetch_assoc($data_dipanggil)){

?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3">
<?= $no++; ?>
</td>

<td class="p-3 font-bold text-blue-600">
<?= $row['nomor_antrian']; ?>
</td>

<td class="p-3">
<?= $row['nama']; ?>
</td>

<td class="p-3">
<?= $row['dokter']; ?>
</td>

<td class="p-3">
<?= date('H:i',strtotime($row['jam_periksa'])); ?>
</td>

<td class="p-3">

<span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">

<?= $row['status']; ?>

</span>

</td>

<td class="p-3 text-center">

<a href="ubah_status.php?id=<?= $row['id']; ?>&status=Sedang Diperiksa"
class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm">

Kirim ke Dokter

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7"
class="text-center p-6 text-gray-500">

Belum ada pasien yang dipanggil

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<!-- FOOTER -->

<div class="mt-8 text-center text-gray-400 text-sm">

Klinik BTH © <?= date('Y'); ?>

</div>

</div>

</div>

</body>

</html>