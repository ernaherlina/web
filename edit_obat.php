<?php
session_start();
include 'config/koneksi.php';

if(!isset($_GET['id'])){
    die("ID obat tidak ditemukan.");
}

$id = intval($_GET['id']);

$data = mysqli_query($conn,"SELECT * FROM obat WHERE id='$id'");
$obat = mysqli_fetch_assoc($data);

if(!$obat){
    die("Data obat tidak ditemukan.");
}

if(isset($_POST['update'])){

    $nama_obat     = mysqli_real_escape_string($conn,$_POST['nama_obat']);
    $kategori      = mysqli_real_escape_string($conn,$_POST['kategori']);
    $bentuk        = mysqli_real_escape_string($conn,$_POST['bentuk']);
    $dosis_default = mysqli_real_escape_string($conn,$_POST['dosis_default']);
    $satuan        = mysqli_real_escape_string($conn,$_POST['satuan']);
    $harga         = intval($_POST['harga']);
    $stok          = intval($_POST['stok']);
    $keterangan    = mysqli_real_escape_string($conn,$_POST['keterangan']);

    mysqli_query($conn,"UPDATE obat SET

        nama_obat='$nama_obat',
        kategori='$kategori',
        bentuk='$bentuk',
        dosis_default='$dosis_default',
        satuan='$satuan',
        harga='$harga',
        stok='$stok',
        keterangan='$keterangan'

        WHERE id='$id'
    ");

    echo "<script>
    alert('Data obat berhasil diperbarui');
    window.location='data_obat.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Obat</title>

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

Edit Data Obat

</h2>

<form method="POST">

<div class="grid grid-cols-2 gap-5">

<div>
<label class="font-medium">Nama Obat</label>

<input
type="text"
name="nama_obat"
value="<?= htmlspecialchars($obat['nama_obat']); ?>"
required
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Kategori</label>

<input
type="text"
name="kategori"
value="<?= htmlspecialchars($obat['kategori'] ?? ''); ?>"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>
<label class="font-medium">Bentuk</label>

<input
type="text"
name="bentuk"
value="<?= htmlspecialchars($obat['bentuk'] ?? ''); ?>"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Dosis Default</label>

<input
type="text"
name="dosis_default"
value="<?= htmlspecialchars($obat['dosis_default'] ?? ''); ?>"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Satuan</label>

<input
type="text"
name="satuan"
value="<?= htmlspecialchars($obat['satuan'] ?? ''); ?>"
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Harga</label>

<input
type="number"
name="harga"
value="<?= $obat['harga']; ?>"
required
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Stok</label>

<input
type="number"
name="stok"
value="<?= $obat['stok']; ?>"
required
class="w-full border rounded-lg p-3 mt-2">
</div>

<div>
<label class="font-medium">Keterangan</label>

<input
type="text"
name="keterangan"
value="<?= htmlspecialchars($obat['keterangan'] ?? ''); ?>"
class="w-full border rounded-lg p-3 mt-2">
</div>

</div>

<div class="mt-8 flex gap-3">

<button
type="submit"
name="update"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Simpan Perubahan

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