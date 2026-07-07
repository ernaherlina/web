<?php
session_start();
include "config/koneksi.php";

/* =====================================
   FILTER STATUS RESEP
===================================== */

$status = $_GET['status'] ?? 'Belum Diambil';

$status = mysqli_real_escape_string($conn,$status);

$judul = $status;

date_default_timezone_set("Asia/Jakarta");

/*====================================================
LOGIN
====================================================*/

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != "apotek"){
    header("Location: login.php");
    exit;
}

/*====================================================
CARD
====================================================*/

$masuk=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
WHERE status='Belum Diambil'
"));

$proses=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
WHERE status='Sedang Disiapkan'
"));

$siap=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
WHERE status='Siap Diambil'
"));

$ambil=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
WHERE status='Sudah Diambil'
"));

?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>Dashboard Apotek</title>

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

overflow-x:hidden;

}

.sidebar{

width:200px;

background:linear-gradient(180deg,#1447e6,#0f2f86);

min-height:100vh;

position:fixed;

left:0;

top:0;

}

.content{

margin-left:200px;

padding:22px;

}

.menu{

height:46px;

display:flex;

align-items:center;

padding:0 18px;

border-radius:14px;

margin-bottom:8px;

transition:.25s;

font-size:14px;

font-weight:500;

color:white;

}

.menu:hover{

background:rgba(255,255,255,.15);

}

.menu.active{

background:white;

color:#1447e6;

}

.menu i{

width:22px;

font-size:17px;

}

.card{

background:white;

border-radius:18px;

box-shadow:0 10px 25px rgba(15,23,42,.05);

transition:.25s;

}

.card:hover{

transform:translateY(-2px);

}

.small-card{

height:90px;

padding:16px;

display:flex;

justify-content:space-between;

align-items:center;

}

.badge{

padding:5px 12px;

border-radius:999px;

font-size:12px;

font-weight:600;

}

.search{

height:46px;

border-radius:14px;

font-size:14px;

}

</style>

</head>

<body>

<!-- ==============================
SIDEBAR
============================== -->

<div class="sidebar">

<div class="p-5">

<div class="flex items-center gap-3">

<div
class="w-12
h-12
rounded-2xl
bg-orange-500
flex
items-center
justify-center
text-white
text-2xl">

💊

</div>

<div>

<div class="text-white font-bold text-xl leading-6">

KLINIK BTH

</div>

<div class="text-cyan-300 text-sm">

APOTEK

</div>

</div>

</div>

</div>

<div class="px-3 mt-5">

<a href="dashboard_apotek.php" class="menu <?= !isset($_GET['status']) ? 'active' : '' ?>">
    <i class="fa-solid fa-house"></i>
    Dashboard
</a>

<a href="dashboard_apotek.php?status=Belum Diambil"
   class="menu <?= ($status=='Belum Diambil') ? 'active' : '' ?>">
    <i class="fa-solid fa-file-prescription"></i>
    Resep Masuk
</a>

<a href="dashboard_apotek.php?status=Sedang Disiapkan"
   class="menu <?= ($status=='Sedang Disiapkan') ? 'active' : '' ?>">
    <i class="fa-solid fa-capsules"></i>
    Sedang Disiapkan
</a>

<a href="dashboard_apotek.php?status=Siap Diambil"
   class="menu <?= ($status=='Siap Diambil') ? 'active' : '' ?>">
    <i class="fa-solid fa-circle-check"></i>
    Siap Diambil
</a>

<a href="dashboard_apotek.php?status=Sudah Diambil"
   class="menu <?= ($status=='Sudah Diambil') ? 'active' : '' ?>">
    <i class="fa-solid fa-box-open"></i>
    Riwayat
</a>

<a href="data_obat.php" class="menu">
    <i class="fa-solid fa-pills"></i>
    Data Obat
</a>

<a href="laporan_apotek.php" class="menu">
    <i class="fa-solid fa-chart-column"></i>
    Laporan
</a>

</div>

<div
class="absolute
bottom-5
left-3
right-3">

<a
href="logout.php"
class="menu">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

<!-- ==============================
CONTENT
============================== -->

<div class="content">

<div class="flex justify-between items-center">

<div>

<h1 class="text-2xl font-bold text-blue-700">

Dashboard Apotek

</h1>

<p class="text-gray-500 mt-1">

Status :

<span class="font-semibold text-blue-700">

<?= $judul ?>

</span>

</p>

<p class="text-gray-500 mt-1 text-sm">

Selamat datang,

<b><?= $_SESSION['nama']; ?></b>

</p>

</div>

<div class="flex items-center gap-4">

<div
class="card
px-5
py-3
text-center">

<div class="font-semibold text-sm">

<?= date('d F Y'); ?>

</div>

<div class="text-gray-500 text-xs">

<?= date('l'); ?>

</div>

</div>

<div
class="card
px-4
py-2
flex
items-center
gap-3">

<div
class="w-10
h-10
rounded-full
bg-blue-600
text-white
flex
items-center
justify-center
font-bold">

<?= strtoupper(substr($_SESSION['nama'],0,1)); ?>

</div>

<div>

<div class="font-semibold text-sm">

<?= $_SESSION['nama']; ?>

</div>

<div class="text-xs text-green-600">

● Online

</div>

</div>

</div>

</div>

</div>

<!-- PART 2 DIMULAI DI SINI -->

<!-- ==========================================
CARD STATISTIK
========================================== -->

<div class="grid grid-cols-4 gap-4 mt-7">

    <!-- Resep Masuk -->

    <div class="card small-card">

        <div>

            <div class="text-gray-500 text-xs uppercase tracking-wide">

                Resep Masuk

            </div>

            <div class="text-3xl font-bold text-blue-700 mt-1">

                <?= $masuk['total']; ?>

            </div>

            <div class="text-xs text-gray-400">

                Menunggu diproses

            </div>

        </div>

        <div
        class="w-14 h-14 rounded-2xl bg-blue-100
        flex items-center justify-center">

            <i class="fa-solid fa-file-prescription text-blue-700 text-xl"></i>

        </div>

    </div>

    <!-- Sedang Diproses -->

    <div class="card small-card">

        <div>

            <div class="text-gray-500 text-xs uppercase tracking-wide">

                Diproses

            </div>

            <div class="text-3xl font-bold text-orange-600 mt-1">

                <?= $proses['total']; ?>

            </div>

            <div class="text-xs text-gray-400">

                Sedang diracik

            </div>

        </div>

        <div
        class="w-14 h-14 rounded-2xl bg-orange-100
        flex items-center justify-center">

            <i class="fa-solid fa-capsules text-orange-600 text-xl"></i>

        </div>

    </div>

    <!-- Siap Diambil -->

    <div class="card small-card">

        <div>

            <div class="text-gray-500 text-xs uppercase tracking-wide">

                Siap Diambil

            </div>

            <div class="text-3xl font-bold text-green-600 mt-1">

                <?= $siap['total']; ?>

            </div>

            <div class="text-xs text-gray-400">

                Menunggu pasien

            </div>

        </div>

        <div
        class="w-14 h-14 rounded-2xl bg-green-100
        flex items-center justify-center">

            <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>

        </div>

    </div>

    <!-- Selesai -->

    <div class="card small-card">

        <div>

            <div class="text-gray-500 text-xs uppercase tracking-wide">

                Selesai

            </div>

            <div class="text-3xl font-bold text-purple-600 mt-1">

                <?= $ambil['total']; ?>

            </div>

            <div class="text-xs text-gray-400">

                Sudah diambil

            </div>

        </div>

        <div
        class="w-14 h-14 rounded-2xl bg-purple-100
        flex items-center justify-center">

            <i class="fa-solid fa-box-open text-purple-600 text-xl"></i>

        </div>

    </div>

</div>

<!-- ==========================================
SEARCH & FILTER
========================================== -->

<div class="card mt-5 p-4">

<form method="GET">

<div class="grid grid-cols-12 gap-3">

<div class="col-span-6">

<input

type="text"

name="keyword"

placeholder="Cari nama pasien, No RM, nomor antrian..."

value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"

class="search
w-full
border
border-gray-200
px-4
focus:ring-2
focus:ring-blue-500
outline-none">

</div>

<div class="col-span-3">

<select

name="status"

class="search
w-full
border
border-gray-200
px-4
focus:ring-2
focus:ring-blue-500
outline-none">

<option value="">Semua Status</option>

<option value="Belum Diambil">Belum Diambil</option>

<option value="Sedang Disiapkan">Sedang Disiapkan</option>

<option value="Siap Diambil">Siap Diambil</option>

<option value="Sudah Diambil">Sudah Diambil</option>

</select>

</div>

<div class="col-span-2">

<button

class="search
w-full
bg-blue-600
hover:bg-blue-700
text-white
font-semibold
transition">

<i class="fa fa-search mr-2"></i>

Cari

</button>

</div>

<div class="col-span-1">

<a

href="dashboard_apotek.php"

class="search
flex
items-center
justify-center
bg-gray-100
hover:bg-gray-200
transition">

<i class="fa-solid fa-rotate-right"></i>

</a>

</div>

</div>

</form>

</div>

<!-- ==========================================
PART 3 DIMULAI DI SINI
========================================== -->

<?php

$keyword = mysqli_real_escape_string($conn, $_GET['keyword'] ?? '');
$status  = mysqli_real_escape_string($conn, $_GET['status'] ?? '');

$where = "WHERE 1=1";

if($keyword!=""){

    $where .= "
    AND
    (
        u.nama LIKE '%$keyword%'
        OR u.no_rm LIKE '%$keyword%'
        OR pd.nomor_antrian LIKE '%$keyword%'
    )";

}

if($status!=""){

    $where .= "
    AND r.status='$status'";

}

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

WHERE r.status='$status'

ORDER BY r.id DESC

");

?>

<div class="card mt-5 overflow-hidden">

<div class="flex justify-between items-center px-5 py-4 border-b">

<div>

<h2 class="text-lg font-semibold text-slate-700">

Daftar Resep Masuk

</h2>

<p class="text-xs text-gray-400">

Resep dari dokter yang menunggu diproses

</p>

</div>

<div>

<span class="badge bg-blue-100 text-blue-700">

<?= mysqli_num_rows($query); ?> Resep

</span>

</div>

</div>

<div class="overflow-x-auto">

<table class="w-full">

<thead class="bg-slate-50">

<tr class="text-sm text-gray-600">

<th class="px-4 py-3 text-left">

Antrian

</th>

<th class="px-4 py-3 text-left">

Pasien

</th>

<th class="px-4 py-3 text-left">

Dokter

</th>

<th class="px-4 py-3 text-left">

Diagnosa

</th>

<th class="px-4 py-3 text-center">

Status

</th>

<th class="px-4 py-3 text-center">

Aksi

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0){

while($r=mysqli_fetch_assoc($query)){

?>

<tr class="border-b hover:bg-slate-50 transition">

<td class="px-4 py-3">

<div class="font-bold text-blue-700">

<?= $r['nomor_antrian']; ?>

</div>

<div class="text-xs text-gray-400">

<?= $r['no_rm']; ?>

</div>

</td>

<td class="px-4 py-3">

<div class="font-semibold">

<?= $r['nama']; ?>

</div>

</td>

<td class="px-4 py-3 text-sm">

<?= $r['nama_dokter']; ?>

</td>

<td class="px-4 py-3 text-sm max-w-xs truncate">

<?= $r['diagnosa']; ?>

</td>

<td class="px-4 py-3 text-center">

<?php

switch($r['status']){

case 'Belum Diambil':

echo '<span class="badge bg-yellow-100 text-yellow-700">

Menunggu

</span>';

break;

case 'Sedang Disiapkan':

echo '<span class="badge bg-orange-100 text-orange-700">

Diproses

</span>';

break;

case 'Siap Diambil':

echo '<span class="badge bg-green-100 text-green-700">

Siap

</span>';

break;

default:

echo '<span class="badge bg-purple-100 text-purple-700">

Selesai

</span>';

}

?>

</td>

<td class="px-4 py-3">

<div class="flex justify-center gap-2">

<td class="px-4 py-4">

<div class="flex justify-center gap-2">

<!-- DETAIL -->

<a href="detail_resep_apotek.php?id=<?= $r['id']; ?>"
class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">

<i class="fas fa-eye"></i>

</a>

<?php if($r['status']=="Belum Diambil"){ ?>

<a href="proses_resep.php?id=<?= $r['id']; ?>"
class="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-500 hover:bg-orange-600 text-white transition"
onclick="return confirm('Mulai proses resep ini?')">

<i class="fas fa-play"></i>

</a>

<?php } ?>

<?php if($r['status']=="Sedang Disiapkan"){ ?>

<a href="siap_diambil.php?id=<?= $r['id']; ?>"
class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-600 hover:bg-green-700 text-white transition"
onclick="return confirm('Resep sudah siap diambil?')">

<i class="fas fa-check"></i>

</a>

<?php } ?>

<?php if($r['status']=="Siap Diambil"){ ?>

<a href="serahkan_obat.php?id=<?= $r['id']; ?>"
class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition"
onclick="return confirm('Obat sudah diserahkan kepada pasien?')">

<i class="fas fa-box-open"></i>

</a>

<?php } ?>

<a href="cetak_resep.php?id=<?= $r['id']; ?>"
target="_blank"
class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-600 hover:bg-red-700 text-white transition">

<i class="fas fa-print"></i>

</a>

</div>

</td>

<?php

}

}else{

?>

<tr>

<td colspan="6">

<div class="text-center py-20">

<i class="fa-solid fa-file-prescription text-6xl text-gray-300"></i>

<p class="mt-4 text-gray-500">

Belum ada resep masuk.

</p>

</div>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

<!-- PART 4 -->

<?php

/* ==========================================
STOK OBAT HAMPIR HABIS
========================================== */

$stok = mysqli_query($conn,"
SELECT
nama_obat,
stok,
bentuk
FROM obat
WHERE stok<=10
ORDER BY stok ASC
LIMIT 5
");

/* ==========================================
AKTIVITAS TERBARU
========================================== */

$aktivitas = mysqli_query($conn,"
SELECT

u.nama,
r.status,
r.tanggal

FROM resep r

INNER JOIN pemeriksaan pm
ON r.id_pemeriksaan=pm.id

INNER JOIN pendaftaran pd
ON pm.id_pendaftaran=pd.id

INNER JOIN users u
ON pd.id_user=u.id

ORDER BY r.id DESC

LIMIT 5
");

?>

<div class="grid grid-cols-2 gap-5 mt-5">

<!-- ==========================================
STOK
========================================== -->

<div class="card">

<div class="px-5 py-4 border-b">

<h2 class="font-semibold text-slate-700">

<i class="fa-solid fa-pills text-red-500 mr-2"></i>

Stok Obat Hampir Habis

</h2>

</div>

<div class="p-5">

<?php

if(mysqli_num_rows($stok)>0){

while($o=mysqli_fetch_assoc($stok)){

?>

<div class="flex justify-between items-center py-3 border-b last:border-0">

<div>

<div class="font-medium">

<?= $o['nama_obat']; ?>

</div>

<div class="text-xs text-gray-400">

<?= $o['bentuk']; ?>

</div>

</div>

<span class="badge bg-red-100 text-red-700">

<?= $o['stok']; ?>

</span>

</div>

<?php

}

}else{

?>

<div class="text-center py-10">

<i class="fa-solid fa-circle-check text-green-500 text-5xl"></i>

<p class="mt-3 text-gray-500">

Semua stok masih aman.

</p>

</div>

<?php

}

?>

</div>

</div>

<!-- ==========================================
AKTIVITAS
========================================== -->

<div class="card">

<div class="px-5 py-4 border-b">

<h2 class="font-semibold text-slate-700">

<i class="fa-solid fa-clock-rotate-left text-blue-600 mr-2"></i>

Aktivitas Terbaru

</h2>

</div>

<div class="p-5">

<?php

while($a=mysqli_fetch_assoc($aktivitas)){

?>

<div class="flex gap-3 py-3 border-b last:border-0">

<div
class="w-11
h-11
rounded-full
bg-blue-100
flex
items-center
justify-center">

<i class="fa-solid fa-user text-blue-600"></i>

</div>

<div class="flex-1">

<div class="font-medium">

<?= $a['nama']; ?>

</div>

<div class="text-sm text-gray-500">

<?php

switch($a['status']){

case "Belum Diambil":

echo "Resep baru masuk.";

break;

case "Sedang Disiapkan":

echo "Obat sedang disiapkan.";

break;

case "Siap Diambil":

echo "Obat siap diambil.";

break;

default:

echo "Obat sudah diambil.";

}

?>

</div>

<div class="text-xs text-gray-400 mt-1">

<?= date('d M Y H:i',strtotime($a['tanggal'])); ?>

</div>

</div>

</div>

<?php

}

?>

</div>

</div>

</div>

<!-- ==========================================
FOOTER
========================================== -->

<div class="mt-6 text-center text-sm text-gray-400">

© <?= date('Y'); ?>

Klinik BTH Tasikmalaya

</div>

</div>

</body>

</html>