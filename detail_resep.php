<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Data tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn,"
SELECT

r.id,
r.tanggal,
r.status,

p.nomor_antrian,
p.keluhan,
p.tanggal_periksa,
p.jam_periksa,

u.nama,
u.nim_nip,
u.no_hp,
u.no_rm,

pm.diagnosa,
pm.tindakan,
pm.instruksi

FROM resep r

JOIN pemeriksaan pm
ON pm.id=r.id_pemeriksaan

JOIN pendaftaran p
ON p.id=pm.id_pendaftaran

JOIN users u
ON u.id=p.id_user

WHERE
r.id='$id'
");

$data=mysqli_fetch_assoc($query);

if(!$data){
    die("Data tidak ditemukan.");
}

$obat=mysqli_query($conn,"
SELECT

o.nama_obat,
ro.jumlah,
ro.aturan_pakai

FROM resep_obat ro

JOIN obat o
ON o.id=ro.id_obat

WHERE
ro.id_resep='$id'
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detail Resep</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
}

.card{
    background:white;
    border-radius:20px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

</style>

</head>

<body>

<div class="max-w-6xl mx-auto py-10">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold text-blue-600">
Detail Resep
</h1>

<p class="text-gray-500">
Informasi resep pasien
</p>

</div>

<div>

<a href="resep_obat.php"
class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-xl">

Kembali

</a>

</div>

</div>

<div class="card p-8">

<h2 class="text-2xl font-bold text-blue-600 mb-6">

Informasi Pasien

</h2>

<div class="grid grid-cols-2 gap-8">

<div>

<p class="text-gray-500">Nama</p>

<p class="font-semibold text-xl">

<?= $data['nama']; ?>

</p>

</div>

<div>

<p class="text-gray-500">Nomor RM</p>

<p class="font-semibold">

<?= $data['no_rm']; ?>

</p>

</div>

<div>

<p class="text-gray-500">NIM / NIP</p>

<p>

<?= $data['nim_nip']; ?>

</p>

</div>

<div>

<p class="text-gray-500">No HP</p>

<p>

<?= $data['no_hp']; ?>

</p>

</div>

<div>

<p class="text-gray-500">Nomor Antrian</p>

<p class="font-bold text-blue-600 text-xl">

<?= $data['nomor_antrian']; ?>

</p>

</div>

<div>

<p class="text-gray-500">Tanggal Pemeriksaan</p>

<p>

<?= date('d-m-Y',strtotime($data['tanggal_periksa'])); ?>

</p>

</div>

<div>

<p class="text-gray-500">Jam Pemeriksaan</p>

<p>

<?= date('H:i',strtotime($data['jam_periksa'])); ?>

</p>

</div>

<div>

<p class="text-gray-500">Status Resep</p>

<p class="font-semibold text-green-600">

<?= $data['status']; ?>

</p>

</div>

</div>

<hr class="my-8">

<h2 class="text-2xl font-bold text-blue-600 mb-5">

Hasil Pemeriksaan

</h2>

<div class="space-y-5">

<div>

<p class="text-gray-500">

Keluhan

</p>

<p>

<?= $data['keluhan']; ?>

</p>

</div>

<div>

<p class="text-gray-500">

Diagnosa

</p>

<p>

<?= $data['diagnosa']; ?>

</p>

</div>

<div>

<p class="text-gray-500">

Tindakan

</p>

<p>

<?= $data['tindakan']; ?>

</p>

</div>

<div>

<p class="text-gray-500">

Instruksi

</p>

<p>

<?= $data['instruksi']; ?>

</p>

</div>

</div>

<hr class="my-8">

<h2 class="text-2xl font-bold text-blue-600 mb-5">

Daftar Obat

</h2>

<table class="w-full border">

<thead class="bg-blue-600 text-white">

<tr>

<th class="p-3">No</th>

<th>Nama Obat</th>

<th>Jumlah</th>

<th>Aturan Pakai</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($o=mysqli_fetch_assoc($obat)){

?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3 text-center">

<?= $no++; ?>

</td>

<td>

<?= $o['nama_obat']; ?>

</td>

<td class="text-center">

<?= $o['jumlah']; ?>

</td>

<td>

<?= $o['aturan_pakai']; ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<div class="mt-8 flex gap-3">

<a href="cetak_resep.php?id=<?= $id; ?>"

class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl">

Cetak Resep

</a>

<a href="resep_obat.php"

class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

Kembali

</a>

</div>

</div>

</div>

</body>

</html>

