<?php
session_start();
include "config/koneksi.php";

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

if($_SESSION['status_user']!="Petugas Apotek"){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Data resep tidak ditemukan.");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn,"

SELECT

r.id,
r.status,
r.tanggal,

pm.id AS id_pemeriksaan,
pm.diagnosa,
pm.catatan,

pd.nomor_antrian,

u.nama,
u.no_rm,
u.jenis_kelamin,

d.nama_dokter

FROM resep r

INNER JOIN pemeriksaan pm
ON r.id_pemeriksaan=pm.id

INNER JOIN pendaftaran pd
ON pm.id_pendaftaran=pd.id

INNER JOIN users u
ON pd.id_user=u.id

INNER JOIN dokter d
ON pm.id_dokter=d.id

WHERE r.id='$id'

");

$data=mysqli_fetch_assoc($query);

if(!$data){

die("Resep tidak ditemukan.");

}

/*====================================
AMBIL DAFTAR OBAT
====================================*/

$obat=mysqli_query($conn,"

SELECT

ro.*,

o.nama_obat,
o.satuan

FROM resep_obat ro

INNER JOIN obat o
ON ro.id_obat=o.id

WHERE ro.id_resep='$id'

");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>Detail Resep</title>

<script src="https://cdn.tailwindcss.com"></script>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

box-shadow:0 10px 30px rgba(0,0,0,.05);

}

.badge{

padding:6px 14px;

border-radius:999px;

font-size:13px;

font-weight:600;

}

</style>

</head>

<body>

<div class="max-w-6xl mx-auto py-8">

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold text-slate-800">

Detail Resep Pasien

</h1>

<p class="text-gray-500 mt-1">

Periksa data resep sebelum menyiapkan obat.

</p>

</div>

<a

href="dashboard_apotek.php"

class="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl">

<i class="fa fa-arrow-left mr-2"></i>

Kembali

</a>

</div>

<!-- =====================================
IDENTITAS PASIEN
===================================== -->

<div class="grid grid-cols-2 gap-6">

    <!-- DATA PASIEN -->

    <div class="card p-6">

        <h2 class="text-lg font-semibold text-slate-700 mb-5">

            <i class="fa-solid fa-user text-blue-600 mr-2"></i>

            Data Pasien

        </h2>

        <table class="w-full text-sm">

            <tr>

                <td class="py-2 w-40 text-gray-500">

                    Nomor RM

                </td>

                <td class="font-semibold">

                    <?= htmlspecialchars($data['no_rm']); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Nomor Antrian

                </td>

                <td class="font-semibold text-blue-700">

                    <?= htmlspecialchars($data['nomor_antrian']); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Nama Pasien

                </td>

                <td class="font-semibold">

                    <?= htmlspecialchars($data['nama']); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Jenis Kelamin

                </td>

                <td>

                    <?= htmlspecialchars($data['jenis_kelamin']); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Tanggal Resep

                </td>

                <td>

                    <?= date('d F Y',strtotime($data['tanggal'])); ?>

                </td>

            </tr>

        </table>

    </div>

    <!-- DATA PEMERIKSAAN -->

    <div class="card p-6">

        <h2 class="text-lg font-semibold text-slate-700 mb-5">

            <i class="fa-solid fa-user-doctor text-green-600 mr-2"></i>

            Hasil Pemeriksaan

        </h2>

        <table class="w-full text-sm">

            <tr>

                <td class="py-2 w-40 text-gray-500">

                    Dokter

                </td>

                <td class="font-semibold">

                    <?= htmlspecialchars($data['nama_dokter']); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Diagnosa

                </td>

                <td>

                    <?= nl2br(htmlspecialchars($data['diagnosa'])); ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Catatan Dokter

                </td>

                <td>

                    <?= !empty($data['catatan'])
                        ? nl2br(htmlspecialchars($data['catatan']))
                        : '-'; ?>

                </td>

            </tr>

            <tr>

                <td class="py-2 text-gray-500">

                    Status Resep

                </td>

                <td>

<?php

switch($data['status']){

case "Belum Diambil":

echo '<span class="badge bg-yellow-100 text-yellow-700">

Menunggu Disiapkan

</span>';

break;

case "Sedang Disiapkan":

echo '<span class="badge bg-orange-100 text-orange-700">

Sedang Disiapkan

</span>';

break;

case "Siap Diambil":

echo '<span class="badge bg-green-100 text-green-700">

Siap Diambil

</span>';

break;

default:

echo '<span class="badge bg-purple-100 text-purple-700">

Sudah Diambil

</span>';

}

?>

                </td>

            </tr>

        </table>

    </div>

</div>

<!-- =====================================
PART 3
===================================== -->

<!-- =====================================
DAFTAR OBAT
===================================== -->

<div class="card p-6 mt-6">

    <div class="flex justify-between items-center mb-5">

        <h2 class="text-lg font-semibold text-slate-700">

            <i class="fa-solid fa-capsules text-blue-600 mr-2"></i>

            Daftar Obat

        </h2>

        <span class="badge bg-blue-100 text-blue-700">

            <?= mysqli_num_rows($obat); ?> Obat

        </span>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="bg-slate-100 text-sm text-slate-600">

                    <th class="px-4 py-3 text-center w-16">No</th>

                    <th class="px-4 py-3 text-left">Nama Obat</th>

                    <th class="px-4 py-3 text-center">Jumlah</th>

                    <td><?= htmlspecialchars($r['satuan'] ?? '-'); ?></td>

                    <th class="px-4 py-3 text-left">Aturan Pakai</th>

                </tr>

            </thead>

            <tbody>

<?php

$no=1;

if(mysqli_num_rows($obat)>0){

while($o=mysqli_fetch_assoc($obat)){

?>

<tr class="border-b hover:bg-slate-50 transition">

<td class="px-4 py-3 text-center">

<?= $no++; ?>

</td>

<td class="px-4 py-3">

<div class="font-semibold text-slate-700">

<?= htmlspecialchars($o['nama_obat']); ?>

</div>

</td>

<td class="px-4 py-3 text-center">

<?= $o['jumlah']; ?>

</td>

<td class="px-4 py-3 text-center">

</td>

<td class="px-4 py-3">

<?= htmlspecialchars($o['aturan_pakai']); ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5" class="text-center py-10 text-gray-500">

<i class="fa-solid fa-pills text-5xl mb-3 text-gray-300"></i>

<p>

Belum ada obat pada resep ini.

</p>

</td>

</tr>

<?php

}

?>

            </tbody>

        </table>

    </div>

</div>


<!-- =====================================
TOMBOL AKSI
===================================== -->

<div class="card p-6 mt-6">

<div class="flex justify-between items-center">

<div>

<h2 class="font-semibold text-slate-700">

Status Resep

</h2>

<p class="text-sm text-gray-500 mt-1">

Ubah status resep sesuai proses pelayanan di apotek.

</p>

</div>

<div class="flex gap-3">

<a
href="cetak_resep.php?id=<?= $data['id_pemeriksaan']; ?>"
target="_blank"
class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl">

<i class="fa-solid fa-print mr-2"></i>

Cetak Resep

</a>

<?php

if($data['status']=="Belum Diambil"){

?>

<a
href="proses_status_resep.php?id=<?= $data['id']; ?>&status=Sedang Disiapkan"
class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl">

<i class="fa-solid fa-capsules mr-2"></i>

Proses Resep

</a>

<?php

}elseif($data['status']=="Sedang Disiapkan"){

?>

<a
href="proses_status_resep.php?id=<?= $data['id']; ?>&status=Siap Diambil"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl">

<i class="fa-solid fa-circle-check mr-2"></i>

Obat Siap

</a>

<?php

}elseif($data['status']=="Siap Diambil"){

?>

<a
href="proses_status_resep.php?id=<?= $data['id']; ?>&status=Sudah Diambil"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

<i class="fa-solid fa-box-open mr-2"></i>

Obat Diserahkan

</a>

<?php

}else{

?>

<button
disabled
class="bg-gray-300 text-gray-600 px-5 py-3 rounded-xl cursor-not-allowed">

<i class="fa-solid fa-circle-check mr-2"></i>

Pelayanan Selesai

</button>

<?php

}

?>

</div>

</div>

</div>

