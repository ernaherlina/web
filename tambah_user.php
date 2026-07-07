<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location:login.php");
    exit;
}

if(isset($_POST['simpan'])){

if(isset($_POST['simpan'])){

    $nama = mysqli_real_escape_string($conn,$_POST['nama']);
    $nim_nip = mysqli_real_escape_string($conn,$_POST['nim_nip']);

    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if($password != $konfirmasi){

        echo "

        <script>

        alert('Konfirmasi password tidak sama.');

        history.back();

        </script>

        ";

        exit;

    }

    $password = password_hash($password,PASSWORD_DEFAULT);

    $role = mysqli_real_escape_string($conn,$_POST['role']);
    $jenis_kelamin = mysqli_real_escape_string($conn,$_POST['jenis_kelamin']);
    $no_hp = mysqli_real_escape_string($conn,$_POST['no_hp']);
    $status_user = mysqli_real_escape_string($conn,$_POST['status_user']);

    // lanjutkan proses INSERT...
}

    $nama            = mysqli_real_escape_string($conn,$_POST['nama']);
    $nim_nip         = mysqli_real_escape_string($conn,$_POST['nim_nip']);
    $password        = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role            = mysqli_real_escape_string($conn,$_POST['role']);
    $jenis_kelamin   = mysqli_real_escape_string($conn,$_POST['jenis_kelamin']);
    $no_hp           = mysqli_real_escape_string($conn,$_POST['no_hp']);
    $email           = mysqli_real_escape_string($conn,$_POST['email']);
    $status_user     = mysqli_real_escape_string($conn,$_POST['status_user']);

    mysqli_query($conn,"
INSERT INTO users
(
    nama,
    nim_nip,
    no_hp,
    jenis_kelamin,
    status_user,
    password,
    role,
    foto,
    no_rm
)
VALUES
(
    '$nama',
    '$nim_nip',
    '$no_hp',
    '$jenis_kelamin',
    '$status_user',
    '$password',
    '$role',
    '$foto',
    '$no_rm'
)
");

    echo "

    <script>

    alert('User berhasil ditambahkan');

    location='kelola_user.php';

    </script>

    ";

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Tambah User</title>

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

<div class="max-w-5xl mx-auto py-8">

<div class="bg-white rounded-3xl shadow-xl overflow-hidden">

<div class="bg-blue-600 px-8 py-6">

<h1 class="text-3xl font-bold text-white">

Tambah Pengguna

</h1>

<p class="text-blue-100 mt-1">

Menambahkan akun baru ke sistem Klinik BTH

</p>

</div>

<form method="POST">

<div class="p-8 grid grid-cols-2 gap-5">

<!-- Nama -->

<div class="col-span-2">

<label class="font-semibold">

Nama Lengkap

</label>

<input
type="text"
name="nama"
required
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- NIM / NIP -->

<div>

<label class="font-semibold">

NIM / NIP

</label>

<input
type="text"
name="nim_nip"
required
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- ROLE -->

<div>

<label class="font-semibold">

Role

</label>

<select
name="role"
required
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

<option value="user">Pasien</option>

<option value="admin">Admin</option>

<option value="apotek">Apotek</option>

<option value="superadmin">Super Admin</option>

</select>

</div>

<!-- PASSWORD -->

<div>

<label class="font-semibold">

Password

</label>

<input
type="password"
name="password"
required
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- KONFIRMASI -->

<div>

<label class="font-semibold">

Konfirmasi Password

</label>

<input
type="password"
name="konfirmasi"
required
class="w-full border rounded-xl p-3 mt-2 focus:ring-2 focus:ring-blue-500 outline-none">

</div>

<!-- JENIS KELAMIN -->

<div>

<label class="font-semibold">

Jenis Kelamin

</label>

<select
name="jenis_kelamin"
required
class="w-full border rounded-xl p-3 mt-2">

<option value="Laki-laki">

Laki-laki

</option>

<option value="Perempuan">

Perempuan

</option>

</select>

</div>

<!-- NO HP -->

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

<!-- STATUS -->

<div>

<label class="font-semibold">

Status User

</label>

<select
name="status_user"
class="w-full border rounded-xl p-3 mt-2">

<option value="Aktif">

Aktif

</option>

<option value="Nonaktif">

Nonaktif

</option>

</select>

</div>

</div>

<div class="border-t bg-gray-50 px-8 py-6">

<div class="flex justify-end gap-3">

<a
href="kelola_user.php"
class="px-6 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 text-white font-medium">

Batal

</a>

<button
type="submit"
name="simpan"
class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">

Simpan User

</button>

</div>

</div>

</form>

</div>

</div>

<script>

const form=document.querySelector("form");

form.addEventListener("submit",function(e){

const password=document.querySelector("input[name=password]").value;

const konfirmasi=document.querySelector("input[name=konfirmasi]").value;

if(password!==konfirmasi){

alert("Konfirmasi password tidak sama.");

e.preventDefault();

return;

}

if(password.length<6){

alert("Password minimal 6 karakter.");

e.preventDefault();

return;

}

});

</script>

</body>

</html>