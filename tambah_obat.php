<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['simpan'])){

    $nama_obat = mysqli_real_escape_string($conn,$_POST['nama_obat']);
    $kategori = mysqli_real_escape_string($conn,$_POST['kategori']);
    $bentuk = mysqli_real_escape_string($conn,$_POST['bentuk']);
    $dosis_default = mysqli_real_escape_string($conn,$_POST['dosis_default']);
    $satuan = mysqli_real_escape_string($conn,$_POST['satuan']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $keterangan = mysqli_real_escape_string($conn,$_POST['keterangan']);

    mysqli_query($conn,"INSERT INTO obat
    (nama_obat,kategori,bentuk,dosis_default,satuan,harga,stok,keterangan)
    VALUES
    ('$nama_obat','$kategori','$bentuk','$dosis_default','$satuan','$harga','$stok','$keterangan')");

    echo "<script>
    alert('Data obat berhasil ditambahkan');
    window.location='data_obat.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Obat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f4f7fb;

}

</style>

</head>

<body>

<div class="max-w-4xl mx-auto mt-10 bg-white shadow-xl rounded-xl p-8">

<h2 class="text-3xl font-bold text-blue-700 mb-8">

Tambah Data Obat

</h2>

<form method="POST">

<div class="grid grid-cols-2 gap-5">

<div>

<label class="font-medium">Nama Obat</label>

<input
type="text"
name="nama_obat"
required
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Kategori</label>

<input
type="text"
name="kategori"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Bentuk</label>

<input
type="text"
name="bentuk"
placeholder="Tablet / Sirup / Kapsul"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Dosis Default</label>

<input
type="text"
name="dosis_default"
placeholder="3 x 1"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Satuan</label>

<input
type="text"
name="satuan"
placeholder="Strip / Botol / Tablet"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Harga</label>

<input
type="number"
name="harga"
required
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Stok</label>

<input
type="number"
name="stok"
required
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-medium">Keterangan</label>

<input
type="text"
name="keterangan"
placeholder="Opsional"
class="w-full border rounded-lg p-3 mt-2">

</div>

</div>

<div class="mt-8 flex gap-3">

<button
type="submit"
name="simpan"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Simpan

</button>

<a
href="data_obat.php"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Kembali

</a>

</div>

</form>

</div>

</body>
</html>

