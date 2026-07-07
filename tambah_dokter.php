<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "superadmin") {
    header("Location: login.php");
    exit;
}

if(isset($_POST['simpan'])){

    $nama     = mysqli_real_escape_string($conn,$_POST['nama_dokter']);
    $nip      = mysqli_real_escape_string($conn,$_POST['nip']);
    $poli     = mysqli_real_escape_string($conn,$_POST['poli']);
    $no_hp    = mysqli_real_escape_string($conn,$_POST['no_hp']);
    $jadwal   = mysqli_real_escape_string($conn,$_POST['jadwal']);
    $status   = mysqli_real_escape_string($conn,$_POST['status']);

    $foto="";

    if($_FILES['foto']['name']!=""){

        $ext = strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));

        $foto = time().".".$ext;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "uploads/".$foto
        );

    }

    mysqli_query($conn,"
    INSERT INTO dokter
    (
        nama_dokter,
        nip,
        poli,
        no_hp,
        jadwal,
        status,
        foto
    )
    VALUES
    (
        '$nama',
        '$nip',
        '$poli',
        '$no_hp',
        '$jadwal',
        '$status',
        '$foto'
    )
    ");

    echo "
    <script>
    alert('Dokter berhasil ditambahkan');
    location='kelola_dokter.php';
    </script>
    ";

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Tambah Dokter</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:Poppins;
    background:#edf4fb;
}

</style>

</head>

<body>

<div class="max-w-3xl mx-auto py-10">

<div class="bg-white rounded-3xl shadow-lg overflow-hidden">

<div class="px-8 py-6 border-b">

<h1 class="text-3xl font-bold text-blue-700">

Tambah Dokter

</h1>

<p class="text-gray-500 mt-1">

Menambahkan dokter baru ke sistem Klinik BTH

</p>

</div>

<form method="POST" enctype="multipart/form-data">

<div class="p-8 grid grid-cols-2 gap-5">

<div class="col-span-2">

<label class="font-semibold">

Nama Dokter

</label>

<input
type="text"
name="nama_dokter"
required
class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

NIP

</label>

<input
type="text"
name="nip"
required
class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

No HP

</label>

<input
type="text"
name="no_hp"
required
class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

Poli

</label>

<input
type="text"
name="poli"
required
class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

Jadwal Praktik

</label>

<input
type="text"
name="jadwal"
placeholder="Senin - Jumat (08.00 - 14.00)"
required
class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

Status

</label>

<select
name="status"
class="w-full border rounded-xl p-3 mt-2">

<option>Aktif</option>
<option>Nonaktif</option>

</select>

</div>

<div>

<label class="font-semibold">

Foto Dokter

</label>

<input
type="file"
name="foto"
accept="image/*"
class="w-full border rounded-xl p-3 mt-2">

</div>

</div>

<div class="border-t px-8 py-6 flex justify-end gap-3">

<a
href="kelola_dokter.php"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl">

Batal

</a>

<button
type="submit"
name="simpan"
class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl">

Simpan Dokter

</button>

</div>

</form>

</div>

</div>

</body>
</html>