<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

/* ===============================
   DATA VERIFIKASI PASIEN
================================ */

$data_verifikasi = mysqli_query($conn,"
SELECT
    p.id,
    p.nomor_antrian,
    p.tanggal_periksa,
    p.jam_periksa,
    p.keluhan,
    p.status,

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

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Data Verifikasi Pasien</title>

<script src="https://cdn.tailwindcss.com"></script>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

*{
font-family:'Poppins',sans-serif;
}

body{
background:#f3f6fb;
}

.card{
background:white;
border-radius:18px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

th{
background:#2563eb;
color:white;
}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-8">

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold">

Data Verifikasi Pasien

</h1>

<p class="text-gray-500">

Daftar pasien yang menunggu verifikasi admin

</p>

</div>

<a href="dashboard_admin.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

← Dashboard

</a>

</div>

<div class="card overflow-hidden">

<table class="w-full">

<thead>

<tr>

<th class="p-4">No</th>

<th class="p-4">No Antrian</th>

<th class="p-4 text-left">Nama Pasien</th>

<th class="p-4 text-left">Dokter</th>

<th class="p-4">Tanggal</th>

<th class="p-4">Jam</th>

<th class="p-4 text-left">Keluhan</th>

<th class="p-4 text-center">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data_verifikasi)>0){

$no=1;

while($row=mysqli_fetch_assoc($data_verifikasi)){

?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="p-4 text-center">

<?= $no++; ?>

</td>

<td class="p-4 text-center font-bold text-blue-600">

<?= htmlspecialchars($row['nomor_antrian']); ?>

</td>

<td class="p-4">

<?= htmlspecialchars($row['nama']); ?>

</td>

<td class="p-4">

<?= htmlspecialchars($row['nama_dokter']); ?>

</td>

<td class="p-4 text-center">

<?= date('d-m-Y',strtotime($row['tanggal_periksa'])); ?>

</td>

<td class="p-4 text-center">

<?= date('H:i',strtotime($row['jam_periksa'])); ?>

</td>

<td class="p-4">

<?= htmlspecialchars($row['keluhan']); ?>

</td>

<td class="p-4 text-center">

<div class="flex justify-center gap-2">

<a
href="verifikasi_pasien.php?id=<?= $row['id']; ?>"
class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">

Verifikasi

</a>

<a
href="batalkan.php?id=<?= $row['id']; ?>"
onclick="return confirm('Batalkan pendaftaran pasien ini?')"
class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">

Batalkan

</a>

</div>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="8" class="p-10 text-center text-gray-500">

Belum ada pasien yang menunggu verifikasi.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<!-- FOOTER -->

<div class="mt-8 text-center text-gray-500 text-sm">

<hr class="mb-4">

<p>

© <?= date('Y'); ?> Klinik BTH - Sistem Informasi Klinik

</p>

<p class="mt-1">

Data Verifikasi Pasien

</p>

</div>

</div>

</body>

</html>