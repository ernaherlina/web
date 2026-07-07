<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['id_dokter'])) {
    header("Location: login_dokter.php");
    exit;
}

$id_dokter = $_SESSION['id_dokter'];

$query = mysqli_query($conn,"
SELECT
    p.*,
    u.nama,
    u.nim_nip,
    u.no_hp,
    u.jenis_kelamin,
    u.no_rm

FROM pendaftaran p

JOIN users u
ON u.id=p.id_user

WHERE
p.id_dokter='$id_dokter'
AND
(
p.status='Dipanggil'
OR
p.status='Diperiksa'
)

ORDER BY
p.tanggal_periksa,
p.jam_periksa
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Data Pasien</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f4f7fb;

}

.card{

border-radius:18px;
box-shadow:0 5px 15px rgba(0,0,0,.08);

}

</style>

</head>

<body>

<div class="flex min-h-screen">

<!-- Sidebar -->

<div class="w-64 bg-white shadow-lg p-6">

<h1 class="text-4xl font-bold text-blue-600">

Klinik BTH

</h1>

<p class="text-gray-500">

Dashboard Dokter

</p>

<div class="mt-10 space-y-2">

<a href="dashboard_dokter.php"
class="block px-4 py-3 rounded-xl hover:bg-blue-50">

Dashboard

</a>

<a href="data_pasien.php"
class="block px-4 py-3 rounded-xl bg-blue-100 text-blue-700 font-semibold">

Data Pasien

</a>

<a href="riwayat_pasien.php"
class="block px-4 py-3 rounded-xl hover:bg-blue-50">

Riwayat Pasien

</a>

<a href="resep_obat.php"
class="block px-4 py-3 rounded-xl hover:bg-blue-50">

Resep Obat

</a>

<a href="logout.php"
class="block mt-10 bg-red-500 hover:bg-red-600 text-white text-center py-3 rounded-xl">

Logout

</a>

</div>

</div>

<!-- Content -->

<div class="flex-1 p-8">

<div class="flex justify-between items-center">

<div>

<h1 class="text-4xl font-bold">

Data Pasien

</h1>

<p class="text-gray-500">

Pasien yang siap diperiksa hari ini

</p>

</div>

</div>

<div class="card bg-white mt-8 overflow-hidden">

<table class="w-full">

<thead class="bg-blue-600 text-white">

<tr>

<th class="p-4">No</th>

<th>Nomor Antrian</th>

<th>Nama</th>

<th>NIM/NIP</th>

<th>No HP</th>

<th>Keluhan</th>

<th>Tanggal</th>

<th>Jam</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0){

$no=1;

while($d=mysqli_fetch_assoc($query)){

?>

<tr class="border-b hover:bg-gray-50">

<td class="p-4"><?= $no++; ?></td>

<td class="font-bold text-blue-600">

<?= $d['nomor_antrian']; ?>

</td>

<td>

<?= $d['nama']; ?>

</td>

<td>

<?= $d['nim_nip']; ?>

</td>

<td>

<?= $d['no_hp']; ?>

</td>

<td>

<?= $d['keluhan']; ?>

</td>

<td>

<?= date('d-m-Y',strtotime($d['tanggal_periksa'])); ?>

</td>

<td>

<?= date('H:i',strtotime($d['jam_periksa'])); ?>

</td>

<td>

<?php

if($d['status']=="Dipanggil"){

?>

<span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
Dipanggil
</span>

<?php

}elseif($d['status']=="Diperiksa"){

?>

<span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs">
Diperiksa
</span>

<?php

}

?>


</td>

<td>

<?php if($d['status']=="Dipanggil"){ ?>

<a href="mulai_periksa.php?id=<?= $d['id']; ?>"
class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">

Mulai Periksa

</a>

<?php }else{ ?>

<a href="pemeriksaan.php?id=<?= $d['id']; ?>"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

Lanjut Pemeriksaan

</a>

<?php } ?>
</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="10"
class="text-center py-10 text-gray-500">

Belum ada pasien yang siap diperiksa

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>

