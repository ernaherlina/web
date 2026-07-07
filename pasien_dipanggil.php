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

$data_dipanggil = mysqli_query($conn,"
SELECT
    p.id,
    p.nomor_antrian,
    p.tanggal_periksa,
    p.jam_periksa,
    p.status,

    u.nama,

    d.nama_dokter

FROM pendaftaran p

JOIN users u
ON u.id = p.id_user

LEFT JOIN dokter d
ON d.id = p.id_dokter

WHERE p.status='Dipanggil'

ORDER BY
p.tanggal_periksa ASC,
p.jam_periksa ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Pasien Dipanggil</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f3f6fb;

}

.card{

background:white;
border-radius:18px;
box-shadow:0 2px 10px rgba(0,0,0,.08);

}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-8">

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold">

Pasien Dipanggil

</h1>

<p class="text-gray-500">

Daftar pasien yang sedang dipanggil

</p>

</div>

<a href="dashboard_admin.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

← Dashboard

</a>

</div>

<div class="card overflow-hidden">

<table class="w-full">

<thead class="bg-cyan-600 text-white">

<tr>

<th class="p-4">No</th>

<th class="p-4">No Antrian</th>

<th class="p-4 text-left">Nama Pasien</th>

<th class="p-4 text-left">Dokter</th>

<th class="p-4">Tanggal</th>

<th class="p-4">Jam</th>

<th class="p-4">Status</th>

<th class="p-4 text-center">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data_dipanggil)>0){

$no=1;

while($row=mysqli_fetch_assoc($data_dipanggil)){

?>

<tr class="border-b hover:bg-cyan-50">

<td class="p-4 text-center">

<?= $no++; ?>

</td>

<td class="p-4 text-center font-bold text-blue-600">

<?= $row['nomor_antrian']; ?>

</td>

<td class="p-4">

<?= $row['nama']; ?>

</td>

<td class="p-4">

<?= $row['nama_dokter']; ?>

</td>

<td class="p-4 text-center">

<?= date('d-m-Y',strtotime($row['tanggal_periksa'])); ?>

</td>

<td class="p-4 text-center">

<?= date('H:i',strtotime($row['jam_periksa'])); ?>

</td>

<td class="p-4 text-center">

<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">

<?= $row['status']; ?>

</span>

</td>

<td class="p-4 text-center">

<a
href="ubah_status.php?id=<?= $row['id']; ?>&status=Diperiksa"
class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">

Kirim ke Dokter

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="8" class="text-center p-10 text-gray-500">

Belum ada pasien yang dipanggil.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<div class="mt-8 text-center text-gray-500 text-sm">

© <?= date('Y'); ?> Klinik BTH

</div>

</div>

</body>

</html>

