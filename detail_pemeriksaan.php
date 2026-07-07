<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Data tidak ditemukan.");
}

$id = $_GET['id'];

/* ==========================
   AMBIL DATA PEMERIKSAAN
========================== */

$query = mysqli_query($conn,"
    SELECT
    pm.id AS id_pemeriksaan,
    pd.*,
    u.nama,
    u.nim_nip,
    u.no_hp,
    u.jenis_kelamin,
    u.no_rm,
    d.nama_dokter,
    pm.tekanan_darah,
    pm.suhu,
    pm.berat_badan,
    pm.tinggi_badan,
    pm.diagnosa,
    pm.tindakan,
    pm.catatan


FROM pendaftaran pd

LEFT JOIN users u
ON pd.id_user = u.id

LEFT JOIN pemeriksaan pm
ON pd.id = pm.id_pendaftaran

LEFT JOIN dokter d
ON pm.id_dokter = d.id

WHERE pm.id = '$id'
LIMIT 1
");

if(!$query){
    die(mysqli_error($conn));
}

if(mysqli_num_rows($query)==0){
    die("Data tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);

/* ======================================
   AMBIL DAFTAR OBAT
====================================== */

$queryObat = mysqli_query($conn,"
SELECT
    o.nama_obat,
    ro.jumlah,
    ro.aturan_pakai,
    o.satuan

FROM resep r

INNER JOIN resep_obat ro
ON r.id = ro.id_resep

INNER JOIN obat o
ON ro.id_obat = o.id

WHERE r.id_pemeriksaan='".$data['id_pemeriksaan']."'

ORDER BY ro.id ASC
");

if(!$queryObat){
    die(mysqli_error($conn));
}

?>

<!DOCTYPE html>

<head>

<meta charset="UTF-8">

<title>Detail Pemeriksaan</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f5f7fb;

}

</style>

</head>

<body>

<div class="max-w-6xl mx-auto p-8">

<div class="flex justify-between mb-8">

<div>

<h1 class="text-3xl font-bold text-blue-700">

Detail Pemeriksaan

</h1>

<p class="text-gray-500">

Hasil pemeriksaan pasien

</p>

</div>

<a href="dashboard_dokter.php"
class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl">

Kembali

</a>

</div>

<div class="bg-white rounded-3xl shadow p-8 mb-6">

<h2 class="text-xl font-bold text-blue-700 mb-6">

Informasi Pasien

</h2>

<div class="grid grid-cols-2 gap-6">

<div>

<label class="text-gray-500 text-sm">Nama</label>

<p class="font-semibold"><?= $data['nama']; ?></p>

</div>

<div>

<label class="text-gray-500 text-sm">NIM / NIP</label>

<p><?= $data['nim_nip']; ?></p>

</div>

<div>

<label class="text-gray-500 text-sm">

No. Rekam Medis

</label>

<p>

<?= $data['no_rm']; ?>

</p>

</div>

<div>

<label class="text-gray-500 text-sm">

No HP

</label>

<p>

<?= $data['no_hp']; ?>

</p>

</div>

<div>

<label class="text-gray-500 text-sm">

Jenis Kelamin

</label>

<p>

<?= $data['jenis_kelamin']; ?>

</p>

</div>

<div>

<label class="text-gray-500 text-sm">Nomor Antrian</label>

<p class="font-bold text-blue-600">

<?= $data['nomor_antrian']; ?>

</p>

</div>

<div>

<label class="text-gray-500 text-sm">Dokter</label>

<p><?= $data['nama_dokter']; ?></p>

</div>

<div>

<label class="text-gray-500 text-sm">Tanggal</label>

<p><?= date('d-m-Y',strtotime($data['tanggal_periksa'])); ?></p>

</div>

<div>

<label class="text-gray-500 text-sm">Jam</label>

<p><?= date('H:i',strtotime($data['jam_periksa'])); ?></p>

</div>

<div class="col-span-2">

<label class="text-gray-500 text-sm">Keluhan</label>

<p><?= $data['keluhan']; ?></p>

</div>

</div>

</div>

<div class="bg-white rounded-3xl shadow p-8">

<h2 class="text-xl font-bold text-blue-700 mb-6">

Hasil Pemeriksaan

</h2>

<div class="grid grid-cols-2 gap-6">

<div>

<label class="text-gray-500 text-sm">

Tekanan Darah

</label>

<p><?= $data['tekanan_darah']; ?></p>

</div>

<div>

<label class="text-gray-500 text-sm">

Suhu

</label>

<p><?= $data['suhu']; ?> °C</p>

</div>

<div>

<label class="text-gray-500 text-sm">

Berat Badan

</label>

<p><?= $data['berat_badan']; ?> Kg</p>

</div>

<div>

<label class="text-gray-500 text-sm">

Tinggi Badan

</label>

<p><?= $data['tinggi_badan']; ?> Cm</p>

</div>

</div>

<hr class="my-6">

<div class="mb-6">

<label class="font-semibold">

Diagnosa

</label>

<div class="mt-2 p-4 bg-blue-50 rounded-xl">

<?= !empty($data['diagnosa']) ? nl2br($data['diagnosa']) : '<span class="text-gray-400">Belum diisi dokter</span>'; ?>

</div>

</div>

<div class="mb-6">

<label class="font-semibold">

Tindakan

</label>

<div class="mt-2 p-4 bg-green-50 rounded-xl">

<?= !empty($data['tindakan']) ? nl2br($data['tindakan']) : '<span class="text-gray-400">Belum diisi dokter</span>'; ?>

</div>

</div>

<div class="mb-6">

<label class="font-semibold">

Catatan Dokter

</label>

<div class="mt-2 p-4 bg-gray-100 rounded-xl">

<?= !empty($data['catatan']) ? nl2br($data['catatan']) : '<span class="text-gray-400">Belum ada catatan</span>'; ?>
</div>

</div>

</div>

<hr class="my-8">

<h2 class="text-xl font-bold text-blue-700 mb-5">

💊 Resep Obat

</h2>

<?php if(mysqli_num_rows($queryObat)>0){ ?>

<div class="overflow-x-auto">

<table class="w-full">

<thead>

<tr class="bg-blue-50">

<th class="p-3 text-left">No</th>

<th class="p-3 text-left">Nama Obat</th>

<th class="p-3 text-center">Jumlah</th>

<th class="p-3 text-center">Satuan</th>

<th class="p-3 text-left">Aturan Pakai</th>

</tr>

</thead>

<tbody>

<?php
$no=1;
while($obat=mysqli_fetch_assoc($queryObat)){
?>

<tr class="border-b">

<td class="p-3"><?= $no++; ?></td>

<td class="p-3 font-semibold">

<?= $obat['nama_obat']; ?>

</td>

<td class="p-3 text-center">

<?= $obat['jumlah']; ?>

</td>

<td class="p-3 text-center">

<?= $obat['satuan']; ?>

</td>

<td class="p-3">

<?= $obat['aturan_pakai']; ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php }else{ ?>

<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 text-center text-gray-500">

Belum ada obat pada resep ini.

</div>

<?php } ?>

<div class="flex justify-end mt-6 gap-3">

    <a href="dashboard_dokter.php"
    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-xl">

        ← Kembali

    </a>

    <a href="cetak_resep.php?id=<?= $data['id_pemeriksaan']; ?>"
    target="_blank"
    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl">
    
        🖨 Cetak Resep

    </a>

</div>

</div>

</body>

</html>