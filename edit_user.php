<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!='superadmin'){
    header("Location:login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("User tidak ditemukan");
}

$id=(int)$_GET['id'];

$q=mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
");

if(mysqli_num_rows($q)==0){
    die("Data tidak ditemukan");
}

$user=mysqli_fetch_assoc($q);

if(isset($_POST['simpan'])){

    $nama=mysqli_real_escape_string($conn,$_POST['nama']);
    $nim=mysqli_real_escape_string($conn,$_POST['nim_nip']);
    $hp=mysqli_real_escape_string($conn,$_POST['no_hp']);
    $jk=mysqli_real_escape_string($conn,$_POST['jenis_kelamin']);
    $role=mysqli_real_escape_string($conn,$_POST['role']);
    $status=mysqli_real_escape_string($conn,$_POST['status_user']);

    mysqli_query($conn,"
    UPDATE users SET

    nama='$nama',
    nim_nip='$nim',
    no_hp='$hp',
    jenis_kelamin='$jk',
    role='$role',
    status_user='$status'

    WHERE id='$id'
    ");

    echo "

    <script>

    alert('Data berhasil diperbarui');

    location='kelola_user.php';

    </script>

    ";

}
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Edit User</title>

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

<div class="max-w-5xl mx-auto py-10">

<div class="bg-white rounded-3xl shadow-xl">

<div class="p-8 border-b">

<h1 class="text-3xl font-bold text-blue-700">

Edit Pengguna

</h1>

<p class="text-gray-500 mt-2">

Perbarui informasi akun pengguna

</p>

</div>

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-8">

<div>

<label class="font-semibold">

Nama

</label>

<input

type="text"

name="nama"

value="<?= $user['nama'];?>"

class="w-full border rounded-xl p-3 mt-2"

required>

</div>

<div>

<label class="font-semibold">

NIM / NIP

</label>

<input

type="text"

name="nim_nip"

value="<?= $user['nim_nip'];?>"

class="w-full border rounded-xl p-3 mt-2"

required>

</div>

<div>

<label class="font-semibold">

No HP

</label>

<input

type="text"

name="no_hp"

value="<?= $user['no_hp'];?>"

class="w-full border rounded-xl p-3 mt-2">

</div>

<div>

<label class="font-semibold">

Jenis Kelamin

</label>

<select

name="jenis_kelamin"

class="w-full border rounded-xl p-3 mt-2">

<option <?= $user['jenis_kelamin']=="Laki-laki"?"selected":"";?>>

Laki-laki

</option>

<option <?= $user['jenis_kelamin']=="Perempuan"?"selected":"";?>>

Perempuan

</option>

</select>

</div>

<div>

<label class="font-semibold">

Role

</label>

<select

name="role"

class="w-full border rounded-xl p-3 mt-2">

<option value="user" <?= $user['role']=="user"?"selected":"";?>>

Pasien

</option>

<option value="admin" <?= $user['role']=="admin"?"selected":"";?>>

Admin

</option>

<option value="apotek" <?= $user['role']=="apotek"?"selected":"";?>>

Apotek

</option>

<option value="superadmin" <?= $user['role']=="superadmin"?"selected":"";?>>

Super Admin

</option>

</select>

</div>

<div>

<label class="font-semibold">

Status

</label>

<select

name="status_user"

class="w-full border rounded-xl p-3 mt-2">

<option value="Aktif"

<?= $user['status_user']=="Aktif"?"selected":"";?>>

Aktif

</option>

<option value="Tidak Aktif"

<?= $user['status_user']=="Tidak Aktif"?"selected":"";?>>

Tidak Aktif

</option>

</select>

</div>

</div>

<div class="border-t p-8 flex justify-end gap-3">

<a

href="kelola_user.php"

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