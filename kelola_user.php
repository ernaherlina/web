<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit;
}

/* ======================================
   FILTER
====================================== */

$cari = $_GET['cari'] ?? '';
$role = $_GET['role'] ?? '';

$where = "1=1";

if($cari!=""){
    $cari=mysqli_real_escape_string($conn,$cari);
    $where .= " AND (
        nama LIKE '%$cari%'
        OR nim_nip LIKE '%$cari%'
        OR no_rm LIKE '%$cari%'
    )";
}

if($role!=""){
    $role=mysqli_real_escape_string($conn,$role);
    $where .= " AND role='$role'";
}

/* ======================================
   STATISTIK
====================================== */

$totalUser=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
"))['total'];

$totalPasien=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='user'
"))['total'];

$totalAdmin=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='admin'
"))['total'];

$totalApotek=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='apotek'
"))['total'];

$totalSuper=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='superadmin'
"))['total'];

/* ======================================
   DATA USER
====================================== */

$dataUser = mysqli_query($conn,"
SELECT *
FROM users
WHERE $where
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Kelola User</title>

<meta name="viewport"
content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

font-family:Poppins;

background:#edf3fb;

}

.card:hover{

transform:translateY(-4px);

transition:.25s;

}

</style>

</head>

<body>

<div class="max-w-[1700px] mx-auto p-8">

<div class="flex justify-between items-center">

<div>

<h1 class="text-4xl font-bold text-blue-700">

Kelola Pengguna

</h1>

<p class="text-gray-500 mt-2">

Manajemen seluruh akun Klinik BTH

</p>

</div>

<a
href="dashboard_superadmin.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

Dashboard

</a>

</div>

<div class="grid grid-cols-5 gap-4 mt-8">

<div class="card bg-white rounded-2xl p-5 shadow">

<div class="text-3xl">

👥

</div>

<div class="text-3xl font-bold mt-2">

<?= $totalUser ?>

</div>

<div class="text-gray-500">

Total User

</div>

</div>

<div class="card bg-white rounded-2xl p-5 shadow">

<div class="text-3xl">

🧑

</div>

<div class="text-3xl font-bold mt-2">

<?= $totalPasien ?>

</div>

<div>

Pasien

</div>

</div>

<div class="card bg-white rounded-2xl p-5 shadow">

<div class="text-3xl">

🛡️

</div>

<div class="text-3xl font-bold mt-2">

<?= $totalAdmin ?>

</div>

<div>

Admin

</div>

</div>

<div class="card bg-white rounded-2xl p-5 shadow">

<div class="text-3xl">

💊

</div>

<div class="text-3xl font-bold mt-2">

<?= $totalApotek ?>

</div>

<div>

Apotek

</div>

</div>

<div class="card bg-white rounded-2xl p-5 shadow">

<div class="text-3xl">

👑

</div>

<div class="text-3xl font-bold mt-2">

<?= $totalSuper ?>

</div>

<div>

Super Admin

</div>

</div>

</div>

<div class="bg-white rounded-2xl shadow p-5 mt-8">

<form method="GET">

<div class="grid grid-cols-4 gap-4">

<div>

<input

type="text"

name="cari"

value="<?= htmlspecialchars($cari) ?>"

placeholder="Cari Nama / NIM / RM"

class="w-full border rounded-xl p-3">

</div>

<div>

<select

name="role"

class="w-full border rounded-xl p-3">

<option value="">Semua Role</option>

<option value="user">Pasien</option>

<option value="admin">Admin</option>

<option value="apotek">Apotek</option>

<option value="superadmin">Super Admin</option>

</select>

</div>

<div>

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl">

Cari

</button>

</div>

<div class="text-right">

<a
href="tambah_user.php"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl">

+ Tambah User

</a>

</div>

</div>

</form>

<!-- TABEL USER -->

<div class="bg-white rounded-2xl shadow mt-6 overflow-hidden">

    <div class="flex justify-between items-center px-6 py-5 border-b">

        <div>

            <h2 class="text-xl font-bold text-slate-700">
                Daftar Pengguna
            </h2>

            <p class="text-gray-500 text-sm">
                Seluruh akun yang terdaftar pada sistem Klinik BTH
            </p>

        </div>

        <div class="text-sm text-gray-500">

            Total :
            <b><?= mysqli_num_rows($dataUser); ?></b>
            Pengguna

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-blue-600 text-white">

            <tr>

                <th class="px-5 py-4">Foto</th>

                <th class="px-5 py-4 text-left">Nama</th>

                <th class="px-5 py-4">NIM/NIP</th>

                <th class="px-5 py-4">Role</th>

                <th class="px-5 py-4">Status</th>

                <th class="px-5 py-4">No RM</th>

                <th class="px-5 py-4">Aksi</th>

            </tr>

            </thead>

            <tbody>

<?php
while($u=mysqli_fetch_assoc($dataUser)){
?>

<tr class="border-b hover:bg-slate-50 transition">

    <td class="py-3 text-center">

        <?php
        if($u['foto']==""){
        ?>

            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center mx-auto font-bold text-blue-700">

                <?= strtoupper(substr($u['nama'],0,1)); ?>

            </div>

        <?php
        }else{
        ?>

            <img
            src="uploads/<?= $u['foto']; ?>"
            class="w-11 h-11 rounded-full object-cover mx-auto">

        <?php } ?>

    </td>

    <td>

        <div class="font-semibold">

            <?= $u['nama']; ?>

        </div>

    </td>

    <td class="text-center">

        <?= $u['nim_nip']; ?>

    </td>

    <td class="text-center">

<?php

switch($u['role']){

case 'superadmin':

echo '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Super Admin</span>';

break;

case 'admin':

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Admin</span>';

break;

case 'apotek':

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Apotek</span>';

break;

default:

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">Pasien</span>';

}

?>

    </td>

    <td class="text-center">

<?php

if($u['status_user']=="Aktif"){

?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">

Aktif

</span>

<?php }else{ ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">

Nonaktif

</span>

<?php } ?>

    </td>

    <td class="text-center">

        <?= $u['no_rm']; ?>

    </td>

    <td>

        <div class="flex justify-center gap-2">

<a
href="detail_user.php?id=<?= $u['id']; ?>"
class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg">

👁

</a>

<a
href="edit_user.php?id=<?= $u['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">

✏️

</a>

<a
href="reset_password.php?id=<?= $u['id']; ?>"
class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">

🔑

</a>

<a
href="hapus_user.php?id=<?= $u['id']; ?>"
onclick="return confirm('Yakin ingin menghapus user ini?')"
class="bg-gray-800 hover:bg-red-600 text-white px-3 py-2 rounded-lg">

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

</div>