<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit;
}

/* ==========================
   FILTER
========================== */

$cari = $_GET['cari'] ?? '';
$status = $_GET['status'] ?? '';

$where = "1=1";

if($cari != ""){
    $cari = mysqli_real_escape_string($conn,$cari);

    $where .= " AND (
        nama_dokter LIKE '%$cari%'
        OR username LIKE '%$cari%'
        OR poli LIKE '%$cari%'
    )";
}

if($status != ""){
    $status = mysqli_real_escape_string($conn,$status);
    $where .= " AND status='$status'";
}

/* ==========================
   STATISTIK
========================== */

$totalDokter = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM dokter
"))['total'];

$dokterAktif = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM dokter
WHERE status='Aktif'
"))['total'];

$dokterNonaktif = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM dokter
WHERE status='Nonaktif'
"))['total'];

$totalPoli = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(DISTINCT poli) total
FROM dokter
"))['total'];

/* ==========================
   DATA DOKTER
========================== */

$dataDokter = mysqli_query($conn,"
SELECT *
FROM dokter
WHERE $where
ORDER BY nama_dokter ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Kelola Dokter</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:Poppins;
    background:#edf4fb;
}

.card{
    transition:.25s;
}

.card:hover{
    transform:translateY(-3px);
}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-6">

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold text-blue-700">
Kelola Dokter
</h1>

<p class="text-gray-500 mt-1 text-sm">
Manajemen seluruh data dokter Klinik BTH
</p>

</div>

<div class="flex gap-3">

<a href="dashboard_superadmin.php"
class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl">

Dashboard

</a>

<a href="tambah_dokter.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

+ Tambah Dokter

</a>

</div>

</div>

<div class="grid grid-cols-4 gap-4 mb-6">

<div class="card bg-white rounded-2xl shadow p-5">

<div class="text-2xl">👨‍⚕️</div>

<div class="text-3xl font-bold mt-2">
<?= $totalDokter ?>
</div>

<div class="text-gray-500 text-sm">
Total Dokter
</div>

</div>

<div class="card bg-white rounded-2xl shadow p-5">

<div class="text-2xl">✅</div>

<div class="text-3xl font-bold mt-2 text-green-600">
<?= $dokterAktif ?>
</div>

<div class="text-gray-500 text-sm">
Dokter Aktif
</div>

</div>

<div class="card bg-white rounded-2xl shadow p-5">

<div class="text-2xl">⛔</div>

<div class="text-3xl font-bold mt-2 text-red-600">
<?= $dokterNonaktif ?>
</div>

<div class="text-gray-500 text-sm">
Dokter Nonaktif
</div>

</div>

<div class="card bg-white rounded-2xl shadow p-5">

<div class="text-2xl">🏥</div>

<div class="text-3xl font-bold mt-2">
<?= $totalPoli ?>
</div>

<div class="text-gray-500 text-sm">
Jumlah Poli
</div>

</div>

</div>

<!-- ================= FILTER ================= -->

<div class="bg-white rounded-2xl shadow p-5 mb-6">

<form method="GET">

<div class="grid grid-cols-4 gap-4">

<input
type="text"
name="cari"
value="<?= htmlspecialchars($cari); ?>"
placeholder="Cari Nama Dokter / Username / Poli..."
class="border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none">

<select
name="status"
class="border rounded-xl p-3">

<option value="">Semua Status</option>

<option value="Aktif" <?= $status=="Aktif"?"selected":""; ?>>
Aktif
</option>

<option value="Nonaktif" <?= $status=="Nonaktif"?"selected":""; ?>>
Nonaktif
</option>

</select>

<button
class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl">

Cari

</button>

<a
href="kelola_dokter.php"
class="bg-gray-500 hover:bg-gray-600 text-white rounded-xl flex items-center justify-center">

Reset

</a>

</div>

</form>

</div>

<!-- ================= TABEL ================= -->

<div class="bg-white rounded-2xl shadow overflow-hidden">

<div class="flex justify-between items-center px-6 py-5 border-b">

<div>

<h2 class="text-xl font-bold text-slate-700">

Daftar Dokter

</h2>

<p class="text-sm text-gray-500">

Total :
<b><?= mysqli_num_rows($dataDokter); ?></b>
Dokter

</p>

</div>

</div>

<div class="overflow-x-auto">

<table class="min-w-full">

<thead class="bg-blue-600 text-white">

<tr>

<th class="py-4 px-4 w-16 text-center">No</th>

<th class="px-4 text-left">Nama Dokter</th>

<th class="px-4 text-center">Username</th>

<th class="px-4 text-center">Poli</th>

<th class="px-4 text-center">Hari Praktik</th>

<th class="px-4 text-center">Jam Praktik</th>

<th class="px-4 text-center">Status</th>

<th class="px-4 text-center w-40">Aksi</th>

</tr>

</thead>

<tbody>

<tr class="border-b hover:bg-slate-50">

<td class="px-4 py-4 text-center">

<?= $no++; ?>

</td>

<td class="px-4 py-4 font-semibold">

<?= $d['nama_dokter']; ?>

</td>

<td class="px-4 py-4 text-center">

<?= $d['username']; ?>

</td>

<td class="px-4 py-4 text-center">

<?= $d['poli']; ?>

</td>

<td class="px-4 py-4 text-center">

<?= $d['hari_praktik']; ?>

</td>

<td class="px-4 py-4 text-center">

<?= date('H:i',strtotime($d['jam_mulai'])); ?>

-

<?= date('H:i',strtotime($d['jam_selesai'])); ?>

</td>

<td class="px-4 py-4 text-center">

<?php if($d['status']=="Aktif"){ ?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

Aktif

</span>

<?php } else { ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">

Nonaktif

</span>

<?php } ?>

</td>

<td class="px-4 py-4">

<div class="flex justify-center gap-2">

<a href="detail_dokter.php?id=<?= $d['id']; ?>"
class="bg-sky-500 hover:bg-sky-600 text-white w-9 h-9 rounded-lg flex items-center justify-center">

👁

</a>

<a href="edit_dokter.php?id=<?= $d['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white w-9 h-9 rounded-lg flex items-center justify-center">

✏️

</a>

<a href="hapus_dokter.php?id=<?= $d['id']; ?>"
onclick="return confirm('Hapus dokter ini?')"
class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 rounded-lg flex items-center justify-center">

🗑

</a>

</div>

</td>

</tr>

<?php
$no=1;
while($d=mysqli_fetch_assoc($dataDokter)){
?>

<tr class="border-b hover:bg-slate-50">

<td class="text-center font-semibold">

<?= $no++; ?>

</td>

<td>

<div class="font-semibold">

<?= $d['nama_dokter']; ?>

</div>

</td>

<td class="text-center">

<?= $d['username']; ?>

</td>

<td class="text-center">

<?= $d['poli']; ?>

</td>

<td class="text-center">

<?= $d['hari_praktik']; ?>

</td>

<td class="text-center">

<?= $d['jam_mulai']; ?>
-
<?= $d['jam_selesai']; ?>

</td>

<td class="text-center">

<?php if($d['status']=="Aktif"){ ?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

Aktif

</span>

<?php }else{ ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">

Nonaktif

</span>

<?php } ?>

</td>

<td>

<div class="flex justify-center gap-2">

<a
href="detail_dokter.php?id=<?= $d['id']; ?>"
class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg">

👁

</a>

<a
href="edit_dokter.php?id=<?= $d['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">

✏️

</a>

<a
href="hapus_dokter.php?id=<?= $d['id']; ?>"
onclick="return confirm('Hapus dokter ini?')"
class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">

🗑

</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>