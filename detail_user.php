<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("ID User tidak ditemukan.");
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
");

if(mysqli_num_rows($query)==0){
    die("Data user tidak ditemukan.");
}

$user = mysqli_fetch_assoc($query);

/* ===========================
   FORMAT ROLE
=========================== */

switch($user['role']){

    case 'superadmin':
        $role = "Super Admin";
    break;

    case 'admin':
        $role = "Admin";
    break;

    case 'apotek':
        $role = "Petugas Apotek";
    break;

    default:
        $role = "Pasien";
}

/* ===========================
   STATUS
=========================== */

$statusColor = ($user['status_user']=="Aktif")
    ? "bg-green-100 text-green-700"
    : "bg-red-100 text-red-700";
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Detail Pengguna</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

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

<div class="max-w-5xl mx-auto p-6">

<!-- ================= HEADER ================= -->

<div class="flex items-center justify-between mb-6">

    <div>

        <h1 class="text-3xl font-bold text-blue-700">
            Detail Pengguna
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Informasi lengkap akun pengguna Klinik BTH
        </p>

    </div>

    <a
    href="kelola_user.php"
    class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl transition">

        ← Kembali

    </a>

</div>


<!-- ================= CARD PROFIL ================= -->

<div class="bg-white rounded-3xl shadow-lg overflow-hidden">

    <div class="bg-gradient-to-r from-blue-600 to-blue-500 h-28"></div>

    <div class="px-8 pb-8">

        <div class="flex items-end gap-6 -mt-14">

            <?php if(!empty($user['foto'])){ ?>

                <img
                src="uploads/<?= $user['foto']; ?>"
                class="w-28 h-28 rounded-full border-4 border-white object-cover shadow">

            <?php }else{ ?>

                <div class="w-28 h-28 rounded-full bg-blue-100 border-4 border-white flex items-center justify-center text-4xl font-bold text-blue-700 shadow">

                    <?= strtoupper(substr($user['nama'],0,1)); ?>

                </div>

            <?php } ?>

            <div class="pt-12">

                <h2 class="text-3xl font-bold text-slate-800">

                    <?= $user['nama']; ?>

                </h2>

                <div class="flex items-center gap-3 mt-3">

                    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-semibold">

                        <?= $role; ?>

                    </span>

                    <span class="<?= $statusColor; ?> px-4 py-1 rounded-full text-sm font-semibold">

                        <?= $user['status_user']; ?>

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ================= DETAIL DATA ================= -->

<div class="grid grid-cols-2 gap-6 mt-6">

<!-- ================= DATA UTAMA ================= -->

<div class="bg-white rounded-2xl shadow p-6">

    <h3 class="text-lg font-semibold text-slate-700 mb-5">
        Data Pengguna
    </h3>

    <div class="space-y-5">

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                Nama Lengkap
            </span>

            <span class="font-semibold text-slate-800">
                <?= $user['nama']; ?>
            </span>

        </div>

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                NIM / NIP
            </span>

            <span class="font-semibold text-slate-800">
                <?= $user['nim_nip']; ?>
            </span>

        </div>

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                Nomor Rekam Medis
            </span>

            <span class="font-semibold text-slate-800">

                <?= empty($user['no_rm']) ? "-" : $user['no_rm']; ?>

            </span>

        </div>

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                Jenis Kelamin
            </span>

            <span class="font-semibold text-slate-800">
                <?= $user['jenis_kelamin']; ?>
            </span>

        </div>

        <div class="flex justify-between">

            <span class="text-gray-500">
                Nomor HP
            </span>

            <span class="font-semibold text-slate-800">

                <?= empty($user['no_hp']) ? "-" : $user['no_hp']; ?>

            </span>

        </div>

    </div>

</div>

<!-- ================= INFORMASI AKUN ================= -->

<div class="bg-white rounded-2xl shadow p-6">

    <h3 class="text-lg font-semibold text-slate-700 mb-5">
        Informasi Akun
    </h3>

    <div class="space-y-5">

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                Role
            </span>

            <span class="font-semibold text-blue-700">
                <?= $role; ?>
            </span>

        </div>

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                Status
            </span>

            <span class="<?= $statusColor; ?> px-3 py-1 rounded-full text-sm font-semibold">

                <?= $user['status_user']; ?>

            </span>

        </div>

        <div class="flex justify-between border-b pb-3">

            <span class="text-gray-500">
                ID User
            </span>

            <span class="font-semibold">

                #<?= $user['id']; ?>

            </span>

        </div>

        <div class="flex justify-between">

            <span class="text-gray-500">
                Tanggal Dibuat
            </span>

            <span class="font-semibold text-slate-800">

                <?= date('d F Y H:i', strtotime($user['created_at'])); ?>

            </span>

        </div>

    </div>

</div>

</div>

<!-- ================= AKSI ================= -->

<div class="bg-white rounded-2xl shadow p-6 mt-6">

<div class="flex justify-end gap-3">

<a
href="edit_user.php?id=<?= $user['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-medium transition">

✏ Edit User

</a>

<a
href="reset_password.php?id=<?= $user['id']; ?>"
class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-medium transition">

🔑 Reset Password

</a>

<a
href="kelola_user.php"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition">

← Kembali

</a>

</div>

</div>

</div>

</body>

</html>