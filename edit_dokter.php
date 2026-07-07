<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "superadmin") {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Data dokter tidak ditemukan.");
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM dokter
WHERE id='$id'
");

if(mysqli_num_rows($query)==0){
    die("Dokter tidak ditemukan.");
}

$d = mysqli_fetch_assoc($query);

if(isset($_POST['simpan'])){

    $nama   = mysqli_real_escape_string($conn,$_POST['nama_dokter']);
    $nip    = mysqli_real_escape_string($conn,$_POST['nip']);
    $poli   = mysqli_real_escape_string($conn,$_POST['poli']);
    $no_hp  = mysqli_real_escape_string($conn,$_POST['no_hp']);
    $jadwal = mysqli_real_escape_string($conn,$_POST['jadwal']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);

    $foto = $d['foto'];

    if($_FILES['foto']['name']!=""){

        if($foto!="" && file_exists("uploads/".$foto)){
            unlink("uploads/".$foto);
        }

        $ext = strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));

        $foto = time().".".$ext;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "uploads/".$foto
        );
    }

    mysqli_query($conn,"
    UPDATE dokter SET

    nama_dokter='$nama',
    nip='$nip',
    poli='$poli',
    no_hp='$no_hp',
    jadwal='$jadwal',
    status='$status',
    foto='$foto'

    WHERE id='$id'
    ");

    echo "
    <script>
    alert('Data dokter berhasil diperbarui');
    window.location='kelola_dokter.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Edit Dokter</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

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

<div class="bg-white rounded-3xl shadow-lg overflow-hidden">

<div class="px-8 py-6 border-b">

<h1 class="text-3xl font-bold text-blue-700">

Edit Dokter

</h1>

<p class="text-gray-500 mt-1">

Perbarui data dokter Klinik BTH

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
value="<?= htmlspecialchars($d['nama_dokter']); ?>"
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
value="<?= htmlspecialchars($d['nip']); ?>"
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
value="<?= htmlspecialchars($d['no_hp']); ?>"
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
value="<?= htmlspecialchars($d['poli']); ?>"
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
value="<?= htmlspecialchars($d['jadwal']); ?>"
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

<option value="Aktif" <?= $d['status']=="Aktif"?"selected":""; ?>>
Aktif
</option>

<option value="Nonaktif" <?= $d['status']=="Nonaktif"?"selected":""; ?>>
Nonaktif
</option>

</select>

</div>

<div>

<label class="font-semibold">

Foto Baru

</label>

<input
type="file"
name="foto"
accept="image/*"
class="w-full border rounded-xl p-3 mt-2">

</div>

<div class="flex items-end">

<?php if($d['foto']!=""){ ?>

<img
src="uploads/<?= $d['foto']; ?>"
class="w-24 h-24 rounded-xl object-cover border">

<?php } ?>

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

Simpan Perubahan

</button>

</div>

</form>

</div>

</div>

</body>

</html>