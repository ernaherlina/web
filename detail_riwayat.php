<?php
session_start();
require_once "config/koneksi.php";

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = intval($_SESSION['id']);

if(!isset($_GET['id'])){
    die("Data tidak ditemukan.");
}

$id_pendaftaran = intval($_GET['id']);

/* ===================================================
   AMBIL DATA PEMERIKSAAN
=================================================== */

$query = mysqli_query($conn,"
SELECT

pd.id,
pd.nomor_antrian,
pd.keluhan,
pd.tanggal_periksa,
pd.jam_periksa,
pd.status,

pm.id AS id_pemeriksaan,
pm.tekanan_darah,
pm.suhu,
pm.nadi,
pm.respirasi,
pm.spo2,
pm.berat_badan,
pm.tinggi_badan,
pm.diagnosa,
pm.tindakan,
pm.instruksi,
pm.catatan,

d.nama_dokter

FROM pendaftaran pd

LEFT JOIN pemeriksaan pm
ON pm.id_pendaftaran=pd.id

LEFT JOIN dokter d
ON d.id=pm.id_dokter

WHERE

pd.id='$id_pendaftaran'
AND pd.id_user='$id_user'

LIMIT 1
");

if(!$query){

die(mysqli_error($conn));

}

if(mysqli_num_rows($query)==0){

die("Data tidak ditemukan.");

}

$data=mysqli_fetch_assoc($query);

/* ===================================================
   AMBIL DATA RESEP
=================================================== */

$qResep=mysqli_query($conn,"
SELECT

r.id,
r.status,

ro.jumlah,
ro.aturan_pakai,

o.nama_obat,
o.bentuk,
o.satuan

FROM resep r

LEFT JOIN resep_obat ro
ON r.id=ro.id_resep

LEFT JOIN obat o
ON ro.id_obat=o.id

WHERE

r.id_pemeriksaan='".$data['id_pemeriksaan']."'

ORDER BY o.nama_obat ASC

");

$statusResep="Tidak Ada Resep";

if(mysqli_num_rows($qResep)>0){

mysqli_data_seek($qResep,0);

$cek=mysqli_fetch_assoc($qResep);

$statusResep=$cek['status'];

mysqli_data_seek($qResep,0);

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>Detail Riwayat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f4f7fb;

}

.card{

background:white;
border-radius:18px;
box-shadow:0 8px 25px rgba(0,0,0,.06);

}

</style>

</head>

<body>

<div class="max-w-6xl mx-auto p-5">


<div class="card p-5 mb-5">

<div class="flex justify-between items-center">

<div>

<a href="riwayat.php"
class="text-blue-600 text-sm">

← Kembali

</a>

<h1 class="text-2xl font-bold mt-2">

Detail Pemeriksaan

</h1>

<p class="text-gray-500 text-sm">

Riwayat Pemeriksaan Pasien

</p>

</div>

<div class="text-right">

<div class="text-3xl font-bold text-blue-600">

<?= htmlspecialchars($data['nomor_antrian']) ?>

</div>

<p class="text-sm text-gray-500">

Nomor Antrian

</p>

</div>

</div>

</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">

<div class="card p-4">

<p class="text-xs text-gray-500">

Dokter

</p>

<p class="font-semibold mt-1 text-sm">

<?= htmlspecialchars($data['nama_dokter'] ?? '-') ?>

</p>

</div>

<div class="card p-4">

<p class="text-xs text-gray-500">

Tanggal

</p>

<p class="font-semibold mt-1 text-sm">

<?= date('d M Y',strtotime($data['tanggal_periksa'])) ?>

</p>

</div>

<div class="card p-4">

<p class="text-xs text-gray-500">

Jam

</p>

<p class="font-semibold mt-1 text-sm">

<?= substr($data['jam_periksa'],0,5) ?>

WIB

</p>

</div>

<div class="card p-4">

<p class="text-xs text-gray-500">

Status

</p>

<span class="inline-block mt-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

<?= htmlspecialchars($data['status']) ?>

</span>

</div>

</div>

<!-- ==========================
     DETAIL PEMERIKSAAN
=========================== -->

<div class="grid md:grid-cols-2 gap-4 mb-5">

    <!-- KOLOM KIRI -->
    <div class="space-y-4">

        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">
                Keluhan
            </p>

            <p class="text-sm text-gray-800">
                <?= !empty($data['keluhan']) ? nl2br(htmlspecialchars($data['keluhan'])) : '-' ?>
            </p>
        </div>

        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">
                Diagnosa
            </p>

            <p class="text-sm text-gray-800">
                <?= !empty($data['diagnosa']) ? nl2br(htmlspecialchars($data['diagnosa'])) : '-' ?>
            </p>
        </div>

        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">
                Tindakan
            </p>

            <p class="text-sm text-gray-800">
                <?= !empty($data['tindakan']) ? nl2br(htmlspecialchars($data['tindakan'])) : '-' ?>
            </p>
        </div>

    </div>


    <!-- KOLOM KANAN -->
    <div class="space-y-4">

        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">
                Instruksi Dokter
            </p>

            <p class="text-sm text-gray-800">
                <?= !empty($data['instruksi']) ? nl2br(htmlspecialchars($data['instruksi'])) : '-' ?>
            </p>
        </div>

        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">
                Catatan Dokter
            </p>

            <p class="text-sm text-gray-800">
                <?= !empty($data['catatan']) ? nl2br(htmlspecialchars($data['catatan'])) : '-' ?>
            </p>
        </div>

    </div>

</div>

<!-- ==========================
     TANDA VITAL
=========================== -->

<div class="card p-4 mb-5">

    <h2 class="font-semibold text-gray-700 mb-3">

        🩺 Tanda Vital

    </h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <div>

            <p class="text-xs text-gray-500">

                Tekanan Darah

            </p>

            <p class="font-semibold">

                <?= $data['tekanan_darah'] ?: '-' ?>

            </p>

        </div>

        <div>

            <p class="text-xs text-gray-500">

                Suhu

            </p>

            <p class="font-semibold">

                <?= $data['suhu'] ?: '-' ?>

            </p>

        </div>

        <div>

            <p class="text-xs text-gray-500">

                Nadi

            </p>

            <p class="font-semibold">

                <?= $data['nadi'] ?: '-' ?>

            </p>

        </div>

        <div>

            <p class="text-xs text-gray-500">

                SpO₂

            </p>

            <p class="font-semibold">

                <?= $data['spo2'] ?: '-' ?>

            </p>

        </div>

    </div>

</div>

<!-- ==========================
     RESEP OBAT
=========================== -->

<div class="card p-4 mb-5">

    <div class="flex justify-between items-center mb-4">

        <h2 class="font-semibold text-gray-700">

            💊 Resep Obat

        </h2>

        <span class="text-xs text-gray-500">

            <?= mysqli_num_rows($qResep); ?> Obat

        </span>

    </div>

<?php

if(mysqli_num_rows($qResep)>0){

while($obat=mysqli_fetch_assoc($qResep)){

?>

<div class="border rounded-xl p-3 mb-3">

<div class="flex justify-between items-center">

<div>

<h3 class="font-semibold text-sm">

<?= htmlspecialchars($obat['nama_obat']) ?>

</h3>

<p class="text-xs text-gray-500 mt-1">

<?= htmlspecialchars($obat['bentuk'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
<?= htmlspecialchars($obat['satuan'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
</p>

</div>

<div class="text-right">

<p class="text-blue-600 font-bold">

<?= $obat['jumlah'] ?>

</p>

<p class="text-xs text-gray-500">

Jumlah

</p>

</div>

</div>

<div class="mt-3 text-sm">

<b>Aturan Pakai</b>

<br>

<?= htmlspecialchars($obat['aturan_pakai']) ?>

</div>

</div>

<?php

}

}else{

?>

<div class="text-center py-8">

<div class="text-5xl">

💊

</div>

<p class="text-gray-500 mt-3">

Belum ada resep obat.

</p>

</div>

<?php

}

?>

</div>

<!-- ==========================
     STATUS RESEP
=========================== -->

<div class="card p-4 mb-5">

<div class="flex justify-between items-center">

<p class="font-semibold">

Status Resep

</p>

<?php

$badge="bg-gray-100 text-gray-700";

switch($statusResep){

case "Belum Diambil":
$badge="bg-yellow-100 text-yellow-700";
break;

case "Sedang Disiapkan":
$badge="bg-blue-100 text-blue-700";
break;

case "Siap Diambil":
$badge="bg-green-100 text-green-700";
break;

case "Sudah Diambil":
$badge="bg-purple-100 text-purple-700";
break;

}

?>

<span class="<?= $badge ?> px-3 py-1 rounded-full text-xs">

<?= htmlspecialchars($statusResep) ?>

</span>

</div>

</div>

<div class="flex justify-end gap-3 mb-5">

<a
href="riwayat.php"
class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm">

← Kembali

</a>

<?php if($data['id_pemeriksaan']){ ?>

<a
href="cetak_resep.php?id=<?= $data['id_pemeriksaan'] ?>"
class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm">

🖨 Cetak Resep

</a>

<?php } ?>

</div>

</div>

</body>

</html>