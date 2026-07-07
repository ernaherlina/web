<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
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

    $password=$_POST['password'];
    $konfirmasi=$_POST['konfirmasi'];

    if($password!=$konfirmasi){

        echo "

        <script>

        alert('Konfirmasi password tidak sama');

        history.back();

        </script>

        ";

        exit;

    }

    $hash=password_hash($password,PASSWORD_DEFAULT);

    mysqli_query($conn,"
    UPDATE users
    SET password='$hash'
    WHERE id='$id'
    ");

    echo "

    <script>

    alert('Password berhasil direset');

    location='kelola_user.php';

    </script>

    ";

}
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Reset Password</title>

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

<div class="max-w-xl mx-auto mt-20">

<div class="bg-white rounded-3xl shadow-xl">

<div class="p-8 border-b">

<h1 class="text-3xl font-bold text-red-600">

Reset Password

</h1>

<p class="text-gray-500 mt-2">

Reset password akun pengguna.

</p>

</div>

<form method="POST">

<div class="p-8">

<div class="mb-5">

<label class="font-semibold">

Nama User

</label>

<input

type="text"

value="<?= $user['nama']; ?>"

class="w-full border rounded-xl p-3 mt-2 bg-gray-100"

readonly>

</div>

<div class="mb-5">

<label class="font-semibold">

Password Baru

</label>

<input

type="password"

name="password"

class="w-full border rounded-xl p-3 mt-2"

required>

</div>

<div class="mb-5">

<label class="font-semibold">

Konfirmasi Password

</label>

<input

type="password"

name="konfirmasi"

class="w-full border rounded-xl p-3 mt-2"

required>

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

class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl">

Reset Password

</button>

</div>

</form>

</div>

</div>

</body>

</html>