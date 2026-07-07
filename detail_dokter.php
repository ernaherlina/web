<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location: login.php");
    exit;
}

$id=(int)$_GET['id'];

$q=mysqli_query($conn,"
SELECT *
FROM dokter
WHERE id='$id'
");

if(mysqli_num_rows($q)==0){
    die("Dokter tidak ditemukan");
}

$d=mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Detail Dokter</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
background:#edf4fb;
font-family:Poppins;
}

</style>

</head>

<body>

<div class="max-w-3xl mx-auto py-10">

<div class="bg-white rounded-3xl shadow overflow-hidden">

<div class="bg-blue-600 text-white px-8 py-6">

<h1 class="text-3xl font-bold">

Detail Dokter

</h1>

</div>

<div class="p-8">

<div class="flex gap-8">

<div>

<?php if($d['foto']==""){ ?>

<div class="w-40 h-40 rounded-2xl bg-blue-100 flex items-center justify-center text-6xl text-blue-600">

<?= strtoupper(substr($d['nama_dokter'],0,1)); ?>

</div>

<?php }else{ ?>

<img
src="uploads/<?= $d['foto']; ?>"
class="w-40 h-40 rounded-2xl object-cover">

<?php } ?>

</div>

<div class="flex-1 space-y-4">

<div>

<label class="text-gray-500 text-sm">

Nama Dokter

</label>

<div class="font-semibold text-xl">

<?= $d['nama_dokter']; ?>

</div>

</div>

<div>

<label class="text-gray-500 text-sm">

NIP

</label>

<div>

<?= $d['nip']; ?>

</div>

</div>

<div>

<label class="text-gray-500 text-sm">

Poli

</label>

<div>

<?= $d['poli']; ?>

</div>

</div>

<div>

<label class="text-gray-500 text-sm">

No HP

</label>

<div>

<?= $d['no_hp']; ?>

</div>

</div>

<div>

<label class="text-gray-500 text-sm">

Jadwal

</label>

<div>

<?= $d['jadwal']; ?>

</div>

</div>

<div>

<label class="text-gray-500 text-sm">

Status

</label>

<div>

<?php
if($d['status']=="Aktif"){
?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

Aktif

</span>

<?php }else{ ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

Nonaktif

</span>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

<div class="border-t p-6 text-right">

<a
href="kelola_dokter.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

Kembali

</a>

</div>

</div>

</div>

</body>

</html>