<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'superadmin') {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

/*====================================================
=                 CARD STATISTIK                     =
====================================================*/

// Total User
$qUser=mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role!='superadmin'
");
$totalUser=mysqli_fetch_assoc($qUser)['total'];

// Total Dokter
$qDokter=mysqli_query($conn,"
SELECT COUNT(*) total
FROM dokter
");
$totalDokter=mysqli_fetch_assoc($qDokter)['total'];

// Total Obat
$qObat=mysqli_query($conn,"
SELECT COUNT(*) total
FROM obat
");
$totalObat=mysqli_fetch_assoc($qObat)['total'];

// Total Pemeriksaan
$qPeriksa=mysqli_query($conn,"
SELECT COUNT(*) total
FROM pemeriksaan
");
$totalPeriksa=mysqli_fetch_assoc($qPeriksa)['total'];

// Total Resep
$qResep=mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep
");
$totalResep=mysqli_fetch_assoc($qResep)['total'];

// Pendapatan
$qPendapatan=mysqli_query($conn,"
SELECT
SUM(ro.jumlah*o.harga) total
FROM resep_obat ro
JOIN obat o
ON ro.id_obat=o.id
");

$totalPendapatan=mysqli_fetch_assoc($qPendapatan)['total'];

if(!$totalPendapatan){
    $totalPendapatan=0;
}

/*====================================================
=                 GRAFIK                             =
====================================================*/

$label=[];
$data=[];

for($i=6;$i>=0;$i--){

    $tgl=date("Y-m-d",strtotime("-$i day"));

    $q=mysqli_query($conn,"
    SELECT COUNT(*) total
    FROM pendaftaran
    WHERE tanggal='$tgl'
    ");

    $d=mysqli_fetch_assoc($q);

    $label[]="'".date("d/m",strtotime($tgl))."'";
    $data[]=$d['total'];

}

/*====================================================
=             AKTIVITAS TERBARU                      =
====================================================*/

$aktivitas=mysqli_query($conn,"
SELECT
u.nama,
p.nomor_antrian,
pm.tanggal_pemeriksaan
FROM pemeriksaan pm
JOIN pendaftaran p
ON pm.id_pendaftaran=p.id
JOIN users u
ON p.id_user=u.id
ORDER BY pm.id DESC
LIMIT 5
");

/*====================================================
=               USER TERBARU                         =
====================================================*/

$userBaru=mysqli_query($conn,"
SELECT
nama,
role,
created_at
FROM users
ORDER BY id DESC
LIMIT 5
");

/*====================================================
=            OBAT HAMPIR HABIS                       =
====================================================*/

$obatHabis=mysqli_query($conn,"
SELECT
nama_obat,
stok
FROM obat
WHERE stok<=20
ORDER BY stok ASC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard Super Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f5f7fb;
color:#334155;
overflow-x:hidden;
}

/* SIDEBAR */

.sidebar{
position:fixed;
left:0;
top:0;
width:190px;
height:100vh;
background:#2563eb;
box-shadow:2px 0 12px rgba(0,0,0,.08);
}

/* CONTENT */

.content{
margin-left:190px;
padding:16px;
}

/* MENU */

.menu{
display:flex;
align-items:center;
gap:10px;
padding:11px 18px;
font-size:12px;
font-weight:500;
color:white;
text-decoration:none;
transition:.2s;
}

.menu:hover{
background:rgba(255,255,255,.08);
}

.menu.active{
background:white;
color:#2563eb;
border-radius:0 15px 15px 0;
}

/* CARD */

.card{
background:white;
border-radius:12px;
border:1px solid #e2e8f0;
padding:14px;
}

/* TITLE */

.page-title{
font-size:22px;
font-weight:700;
color:#1e293b;
}

.page-subtitle{
font-size:11px;
color:#64748b;
margin-top:3px;
}

/* STAT */

.stat-title{
font-size:11px;
color:#64748b;
}

.stat-value{
font-size:20px;
font-weight:700;
margin-top:5px;
}

/* SECTION */

.section-title{
font-size:15px;
font-weight:600;
color:#1e293b;
}

/* CHART */

.chart-box{
height:220px;
}

/* TABLE */

table{
font-size:12px;
}

th{
font-size:11px;
}

td{
font-size:11px;
}

::-webkit-scrollbar{
width:6px;
}

::-webkit-scrollbar-thumb{
background:#2563eb;
border-radius:20px;
}

</style>

</head>

<body>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <!-- LOGO -->

    <div class="px-5 py-5 border-b border-blue-500">

        <h2 class="text-xl font-bold tracking-wide">
            KLINIK BTH
        </h2>

        <p class="text-[11px] text-blue-200 mt-1">
            SUPER ADMIN
        </p>

    </div>

    <!-- MENU -->

    <div class="mt-3">

        <a href="dashboard_superadmin.php" class="menu active">

            <span>🏠</span>

            Dashboard

        </a>

        <a href="kelola_user.php" class="menu">

            <span>👥</span>

            Kelola User

        </a>

        <a href="kelola_dokter.php" class="menu">

            <span>🩺</span>

            Kelola Dokter

        </a>

        <a href="data_obat.php" class="menu">

            <span>💊</span>

            Data Obat

        </a>

        <a href="laporan_superadmin.php" class="menu">

            <span>📊</span>

            Laporan

        </a>

        <a href="kelola_user.php" class="menu">

            <span>🔐</span>

            Reset Password

        </a>

    </div>

    <!-- LOGOUT -->

    <div class="absolute bottom-5 left-4 right-4">

        <a href="logout.php"
        class="block text-center bg-red-600 hover:bg-red-700 rounded-lg py-2.5 text-xs font-semibold text-white transition">

            Logout

        </a>

    </div>

</div>

<!-- ================= CONTENT ================= -->

<div class="content">

<!-- ================= HEADER ================= -->

<div class="flex justify-between items-center mb-5">

    <div>

        <h1 class="page-title">

            Dashboard Super Admin

        </h1>

        <p class="page-subtitle">

            Monitoring seluruh sistem Klinik BTH

        </p>

    </div>

    <div class="card px-4 py-3">

        <div class="text-base font-semibold">

            <?= date('d F Y'); ?>

        </div>

        <div class="text-[11px] text-slate-500 mt-1">

            <?= date('l'); ?>

        </div>

    </div>

</div>

<!-- ================= CARD STATISTIK ================= -->

<div class="grid grid-cols-6 gap-3 mb-5">

    <!-- USER -->
    <div class="card">

        <div class="stat-title">
            Total User
        </div>

        <div class="flex justify-between items-end mt-2">

            <div class="stat-value">
                <?= $totalUser ?>
            </div>

            <div class="text-blue-600 text-lg">
                👥
            </div>

        </div>

    </div>

    <!-- DOKTER -->
    <div class="card">

        <div class="stat-title">
            Dokter
        </div>

        <div class="flex justify-between items-end mt-2">

            <div class="stat-value">
                <?= $totalDokter ?>
            </div>

            <div class="text-green-600 text-lg">
                🩺
            </div>

        </div>

    </div>

    <!-- OBAT -->
    <div class="card">

        <div class="stat-title">
            Obat
        </div>

        <div class="flex justify-between items-end mt-2">

            <div class="stat-value">
                <?= $totalObat ?>
            </div>

            <div class="text-orange-500 text-lg">
                💊
            </div>

        </div>

    </div>

    <!-- PEMERIKSAAN -->
    <div class="card">

        <div class="stat-title">
            Pemeriksaan
        </div>

        <div class="flex justify-between items-end mt-2">

            <div class="stat-value">
                <?= $totalPeriksa ?>
            </div>

            <div class="text-cyan-600 text-lg">
                📋
            </div>

        </div>

    </div>

    <!-- RESEP -->
    <div class="card">

        <div class="stat-title">
            Resep
        </div>

        <div class="flex justify-between items-end mt-2">

            <div class="stat-value">
                <?= $totalResep ?>
            </div>

            <div class="text-violet-600 text-lg">
                🧾
            </div>

        </div>

    </div>

    <!-- PENDAPATAN -->

    <div class="rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3">

        <div class="text-[11px] opacity-80">
            Pendapatan
        </div>

        <div class="text-lg font-bold mt-2">

            Rp <?= number_format($totalPendapatan,0,',','.') ?>

        </div>

    </div>

</div>

<!-- ================= GRAFIK & QUICK ACTION ================= -->

<div class="grid grid-cols-12 gap-4">

    <!-- GRAFIK -->

    <div class="col-span-8 card">

        <div class="flex justify-between items-center mb-4">

            <div>

                <h2 class="section-title">
                    Grafik Kunjungan
                </h2>

                <p class="text-[11px] text-slate-500">
                    Data 7 hari terakhir
                </p>

            </div>

        </div>

        <div class="chart-box">

            <canvas id="grafikPasien"></canvas>

        </div>

    </div>

    <!-- QUICK ACTION -->

    <div class="col-span-4 card">

        <div class="flex justify-between items-center mb-4">

            <h2 class="section-title">

                Quick Action

            </h2>

        </div>

        <div class="grid grid-cols-2 gap-3">

            <a href="kelola_user.php"
            class="rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition p-3">

                <div class="text-base">
                    👥
                </div>

                <div class="text-xs font-semibold mt-2">
                    User
                </div>

            </a>

            <a href="kelola_dokter.php"
            class="rounded-lg border border-slate-200 hover:border-green-500 hover:bg-green-50 transition p-3">

                <div class="text-base">
                    🩺
                </div>

                <div class="text-xs font-semibold mt-2">
                    Dokter
                </div>

            </a>

            <a href="data_obat.php"
            class="rounded-lg border border-slate-200 hover:border-orange-500 hover:bg-orange-50 transition p-3">

                <div class="text-base">
                    💊
                </div>

                <div class="text-xs font-semibold mt-2">
                    Obat
                </div>

            </a>

            <a href="laporan_superadmin.php"
            class="rounded-lg border border-slate-200 hover:border-purple-500 hover:bg-purple-50 transition p-3">

                <div class="text-base">
                    📊
                </div>

                <div class="text-xs font-semibold mt-2">
                    Laporan
                </div>

            </a>

            <a href="kelola_user.php"
            class="rounded-lg border border-slate-200 hover:border-red-500 hover:bg-red-50 transition p-3">

                <div class="text-base">
                    🔐
                </div>

                <div class="text-xs font-semibold mt-2">
                    Password
                </div>

            </a>

            <a href="backup_database.php"
            class="rounded-lg border border-slate-200 hover:border-slate-600 hover:bg-slate-100 transition p-3">

                <div class="text-base">
                    💾
                </div>

                <div class="text-xs font-semibold mt-2">
                    Backup
                </div>

            </a>

        </div>

    </div>

</div>

<!-- ================= AKTIVITAS ================= -->

<div class="grid grid-cols-12 gap-4 mt-4">

    <!-- ================= AKTIVITAS ================= -->

    <div class="col-span-8 card">

        <div class="flex justify-between items-center mb-4">

            <div>

                <h2 class="section-title">
                    Aktivitas Terbaru
                </h2>

                <p class="text-[11px] text-slate-500">
                    5 Pemeriksaan Terakhir
                </p>

            </div>

        </div>

        <div class="space-y-3">

            <?php while($a=mysqli_fetch_assoc($aktivitas)){ ?>

            <div class="flex justify-between items-center border-b border-slate-100 pb-3">

                <div>

                    <div class="text-[13px] font-semibold text-slate-700">

                        <?= $a['nama']; ?>

                    </div>

                    <div class="text-[11px] text-slate-500">

                        Nomor Antrian :
                        <b><?= $a['nomor_antrian']; ?></b>

                    </div>

                </div>

                <div class="text-[11px] text-slate-400">

                    <?= date('d M Y',strtotime($a['tanggal_pemeriksaan'])); ?>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

    <!-- ================= USER TERBARU ================= -->

    <div class="col-span-4 card">

        <div class="flex justify-between items-center mb-4">

            <div>

                <h2 class="section-title">
                    User Terbaru
                </h2>

                <p class="text-[11px] text-slate-500">
                    Registrasi Terbaru
                </p>

            </div>

        </div>

        <div class="space-y-3">

            <?php while($u=mysqli_fetch_assoc($userBaru)){ ?>

            <div class="border-b border-slate-100 pb-3">

                <div class="text-[13px] font-semibold text-slate-700">

                    <?= $u['nama']; ?>

                </div>

                <div class="text-[11px] text-blue-600 mt-1">

                    <?= ucfirst($u['role']); ?>

                </div>

                <div class="text-[11px] text-slate-400 mt-1">

                    <?= date('d M Y',strtotime($u['created_at'])); ?>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

<!-- ================= BARIS TERAKHIR ================= -->

<div class="grid grid-cols-12 gap-4 mt-4">

    <!-- ================= OBAT HAMPIR HABIS ================= -->

    <div class="col-span-4 card">

        <div class="flex justify-between items-center mb-3">

            <h2 class="section-title">
                Obat Hampir Habis
            </h2>

            <span class="text-[10px] text-red-500 font-medium">
                ≤ 20
            </span>

        </div>

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-2 font-semibold text-slate-600">
                        Nama
                    </th>

                    <th class="text-center py-2 font-semibold text-slate-600">
                        Stok
                    </th>

                </tr>

            </thead>

            <tbody>

                <?php while($o=mysqli_fetch_assoc($obatHabis)){ ?>

                <tr class="border-b border-slate-100">

                    <td class="py-2 text-[12px]">

                        <?= $o['nama_obat']; ?>

                    </td>

                    <td class="text-center">

                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-[11px]">

                            <?= $o['stok']; ?>

                        </span>

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

    <!-- ================= CONTROL CENTER ================= -->

    <div class="col-span-4 card">

        <h2 class="section-title mb-4">
            Control Center
        </h2>

        <div class="space-y-2">

            <a href="kelola_user.php"
            class="flex justify-between items-center border rounded-lg p-3 hover:bg-blue-50 transition">

                <span class="text-[12px]">
                    Kelola User
                </span>

                <span>👥</span>

            </a>

            <a href="kelola_dokter.php"
            class="flex justify-between items-center border rounded-lg p-3 hover:bg-green-50 transition">

                <span class="text-[12px]">
                    Kelola Dokter
                </span>

                <span>🩺</span>

            </a>

            <a href="data_obat.php"
            class="flex justify-between items-center border rounded-lg p-3 hover:bg-orange-50 transition">

                <span class="text-[12px]">
                    Data Obat
                </span>

                <span>💊</span>

            </a>

            <a href="laporan_superadmin.php"
            class="flex justify-between items-center border rounded-lg p-3 hover:bg-purple-50 transition">

                <span class="text-[12px]">
                    Laporan
                </span>

                <span>📊</span>

            </a>

        </div>

    </div>

    <!-- ================= SISTEM ================= -->

    <div class="col-span-4 card">

        <h2 class="section-title mb-4">
            Sistem
        </h2>

        <div class="space-y-2">

            <a href="kelola_user.php"
            class="flex justify-between items-center rounded-lg bg-red-50 p-3 hover:bg-red-100 transition">

                <span class="text-[12px]">
                    Reset Password
                </span>

                <span>🔐</span>

            </a>

            <a href="backup_database.php"
            class="flex justify-between items-center rounded-lg bg-slate-100 p-3 hover:bg-slate-200 transition">

                <span class="text-[12px]">
                    Backup Database
                </span>

                <span>💾</span>

            </a>

            <a href="log_aktivitas.php"
            class="flex justify-between items-center rounded-lg bg-blue-50 p-3 hover:bg-blue-100 transition">

                <span class="text-[12px]">
                    Log Aktivitas
                </span>

                <span>📄</span>

            </a>

            <a href="pengaturan.php"
            class="flex justify-between items-center rounded-lg bg-green-50 p-3 hover:bg-green-100 transition">

                <span class="text-[12px]">
                    Pengaturan Klinik
                </span>

                <span>⚙️</span>

            </a>

        </div>

    </div>

</div>

</div>

<script>

const ctx=document.getElementById('grafikPasien');

new Chart(ctx,{

type:'line',

data:{

labels:[<?= implode(",",$label); ?>],

datasets:[{

data:[<?= implode(",",$data); ?>],

borderColor:'#2563eb',

backgroundColor:'rgba(37,99,235,.08)',

fill:true,

borderWidth:2,

tension:.35,

pointRadius:3,

pointHoverRadius:5

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{
display:false
}

},

scales:{

x:{
grid:{
display:false
}
},

y:{
beginAtZero:true,
ticks:{
stepSize:1
}
}

}

}

});

</script>

</body>

</html>