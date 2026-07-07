<?php
session_start();
include 'config/koneksi.php';

$id_user = $_SESSION['id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id_user'
"));

$namaHari = [
    "Minggu",
    "Senin",
    "Selasa",
    "Rabu",
    "Kamis",
    "Jumat",
    "Sabtu"
];

$hariIni = $namaHari[date("w")];

$dokterHariIni = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM dokter
WHERE status='Aktif'
AND FIND_IN_SET('$hariIni',hari_praktik)
LIMIT 1
"));


$id_user = $_SESSION['id'];

$notif = mysqli_fetch_assoc(mysqli_query($conn,"

SELECT COUNT(*) as total
FROM notifikasi
WHERE id_user='$id_user'
AND status_baca='Belum Dibaca'
"));

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = intval($_SESSION['id']);

$query = mysqli_query($conn, "

SELECT

pd.*,

u.nama,
u.no_rm,
u.jenis_kelamin,

pm.id AS id_pemeriksaan,
pm.diagnosa,
pm.tindakan,
pm.instruksi,

r.id AS id_resep,
r.status AS status_resep,
r.tanggal AS tanggal_resep

FROM pendaftaran pd

LEFT JOIN users u
ON pd.id_user=u.id

LEFT JOIN pemeriksaan pm
ON pm.id_pendaftaran=pd.id

LEFT JOIN resep r
ON r.id_pemeriksaan=pm.id

WHERE pd.id_user='$id_user'
ORDER BY pd.id DESC
LIMIT 1

");

if(!$query){

    die(mysqli_error($conn));

}

$data = mysqli_fetch_assoc($query);

$qTotal = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE tanggal_periksa = CURDATE()
");

$totalDaftar = mysqli_fetch_assoc($qTotal);

// ==============================
// TOTAL PASIEN HARI INI
// ==============================

$qTotalHari = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE tanggal_periksa = CURDATE()
");

$totalHari = mysqli_fetch_assoc($qTotalHari)['total'];

$nomor_antrian = $data['nomor_antrian'] ?? '-';
$status = $data['status'] ?? '-';
$status_resep = $data['status_resep'] ?? '';

$statusSaatIni = '';

if($status_resep == 'Sedang Disiapkan'){

    $statusSaatIni = '💊 Obat Sedang Disiapkan';

}elseif($status_resep == 'Siap Diambil'){

    $statusSaatIni = '📦 Obat Siap Diambil';

}elseif($status_resep == 'Sudah Diambil'){

    $statusSaatIni = '✅ Obat Sudah Diambil';

}else{

    switch($status){

        case 'Menunggu Verifikasi':
            $statusSaatIni = '🟡 Menunggu Verifikasi';
        break;

        case 'Menunggu':
            $statusSaatIni = '🟢 Menunggu Pemeriksaan';
        break;

        case 'Dipanggil':
            $statusSaatIni = '🔵 Dipanggil Dokter';
        break;

        case 'Selesai':
            $statusSaatIni = '🩺 Pemeriksaan Selesai';
        break;

        default:
            $statusSaatIni = '-';
        break;

    }

}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard User</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family: 'Poppins', sans-serif;
    background: #f4f7fb;
    overflow-x:hidden;
}

.menu-hover{
    transition: 0.3s;
}

.menu-hover:hover{
    background: #eef4ff;
    transform: translateX(3px);
}

.card-hover{
    transition: 0.3s;
}

.card-hover:hover{
    transform: translateY(-2px);
}

/* MOBILE */
@media only screen and (max-width:768px){

    .sidebar-text{
        display:none;
    }

}

</style>

</head>

<body>

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <div class="w-20 lg:w-56 bg-white shadow-lg flex flex-col justify-between">

        <div>

            <!-- LOGO -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-5 md:rounded-br-[35px]">

                <h1 class="text-xl md:text-2xl font-bold text-white">
                    Klinik BTH
                </h1>

                <p class="text-blue-100 text-xs mt-1 sidebar-text">
                    Sistem Pendaftaran Online
                </p>

            </div>

            <!-- MENU -->
            <div class="p-2 md:p-4 space-y-2 mt-4">

                <!-- DASHBOARD -->
                <a href="dashboard.php"
                class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl bg-blue-50 text-blue-600 text-sm font-medium">

                    <span>🏠</span>
                    <span class="sidebar-text">Dashboard</span>

                </a>

                <!-- DAFTAR -->
                <a href="daftar_berobat.php"
                class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl text-gray-600 text-sm">

                    <span>🩺</span>
                    <span class="sidebar-text">Daftar Berobat</span>

                </a>

                <!-- ANTRIAN -->
                <a href="antrian_saya.php"
                class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl text-gray-600 text-sm">

                    <span>🎫</span>
                    <span class="sidebar-text">Antrian Saya</span>

                </a>



                <a href="riwayat.php"
                class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl text-gray-600 text-sm">
                 
                <span>📋</span>
                 <span class="sidebar-text">Riwayat Berobat</span>
            
            </a>


            <a href="resep_saya.php"
class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl text-gray-600 hover:text-blue-600">

    <span>💊</span>

    <span class="sidebar-text">

        Resep Saya

    </span>

</a>


                <!-- PROFIL -->
                <a href="profil.php"
                class="menu-hover flex items-center justify-center md:justify-start gap-1 p-1 rounded-1xl text-gray-600 text-sm">

                    <span>👤</span>
                    <span class="sidebar-text">Profil Saya</span>

                </a>

            </div>

        </div>

        <!-- LOGOUT -->
        <div class="p-2 md:p-4">

            <a href="logout.php"
            class="flex items-center justify-center md:justify-center gap-2 border border-red-200 text-red-500 hover:bg-red-500 hover:text-white transition p-3 rounded-2xl text-sm font-medium">

                🚪
                <span class="sidebar-text">Logout</span>

            </a>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-3 md:p-5 overflow-auto">

        <!-- TOP -->
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-5">

            <div>

                <h1 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight">

                    Selamat datang,
                    <?php echo $_SESSION['nama']; ?> 👋

                </h1>

                <p class="text-gray-500 text-sm mt-1">

                    Semoga sehat selalu.

                </p>

            </div>

            <!-- PROFILE -->
            <div class="flex items-center gap-3">

                <!-- NOTIF -->
                 <a href="notifikasi.php"
                 class="relative bg-white w-12 h-12 rounded-2xl shadow flex items-center justify-center text-sm hover:bg-gray-50 transition">
                 🔔
                 <?php if($notif['total'] > 0){ ?>
                 <div class="absolute top-1 right-1 bg-red-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center">

        <?php echo $notif['total']; ?>

    </div>

    <?php } ?>

</a>


                
                <!-- USER -->
<a href="profil.php"
class="bg-white px-4 py-2 rounded-2xl shadow flex items-center gap-3 hover:bg-blue-50">

    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-blue-500">

        <?php if(!empty($user['foto'])){ ?>

            <img
            src="uploads/<?php echo $user['foto']; ?>"
            class="w-full h-full object-cover">

        <?php } else { ?>

            <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white font-bold">

                <?php echo strtoupper(substr($_SESSION['nama'],0,1)); ?>

            </div>

        <?php } ?>

    </div>

    <div>


                        <h3 class="font-semibold text-sm">
                            <?php echo $_SESSION['nama']; ?>
                        </h3>

                        <p class="text-xs text-gray-500">
                            Pasien
                        </p>

                    </div>
                
                </a>

            </div>

        </div>

<!-- CARD -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-1xl shadow-lg p-3">

    <p class="text-gray-500 text-sm">

        📅 Jadwal Pemeriksaan Saya

    </p>

<?php if(!empty($data['id'])){ ?>

    <h2 class="text-xl font-bold text-blue-700 mt-4">

        <?= $data['dokter']; ?>

    </h2>

    <p class="text-gray-500 mt-3">

        📅 <?= date('d M Y',strtotime($data['tanggal_periksa'])); ?>

    </p>

    <p class="text-blue-600 font-semibold mt-2">

        🕒 <?= date('H:i',strtotime($data['jam_periksa'])); ?> WIB

    </p>

    <span class="inline-block mt-4 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">

        Jadwal Aktif

    </span>

<?php }else{ ?>

    <h2 class="text-lg font-semibold text-gray-700 mt-4">

        Belum Ada Jadwal

    </h2>

    <a href="daftar_berobat.php"
    class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-xl text-sm">

        Daftar Berobat

    </a>

<?php } ?>

</div>

<div class="card-hover bg-white p-4 rounded-1xl shadow">

    <p class="text-gray-500 text-sm">

        🎫 Nomor Antrian Saya

    </p>

<?php if(!empty($data['id'])){ ?>

    <h1 class="text-3xl font-bold text-green-600 mt-3">

        <?= $nomor_antrian; ?>

    </h1>

    <p class="text-gray-500 mt-4">

        Status Antrian

    </p>

    <span class="inline-block mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

        <?= $statusSaatIni; ?>

    </span>

<?php }else{ ?>

    <h2 class="text-lg font-semibold text-gray-700 mt-4">

        Belum Memiliki Nomor Antrian

    </h2>

<?php } ?>

</div>

            <!-- STATUS -->
            <div class="card-hover bg-white p-2 rounded-1xl shadow">

                <p class="text-gray-500 text-xs">
                    Status Saat Ini
                </p>

                <h1 class="text-lg md:text-xl font-semibold text-blue-600 mt-2">

                    <?= $statusSaatIni; ?>

                </h1>

        </div>

</div>

<!-- BOTTOM -->


            <!-- RIWAYAT -->
<div class="bg-white rounded-2xl shadow p-5">

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-xl font-bold text-gray-800">
            Riwayat Pendaftaran
        </h2>

    </div>

    <div class="space-y-3">

    <?php

    $riwayat = mysqli_query(
        $conn,
        "SELECT *
        FROM pendaftaran
        WHERE id_user='$id_user'
        ORDER BY id DESC
        LIMIT 1"
    );

    if(mysqli_num_rows($riwayat) > 0){

        if($r=mysqli_fetch_assoc($riwayat)){

    ?>

        <div class="bg-gray-50 rounded-2xl p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-3">

            <div>

                <h3 class="text-lg font-bold text-blue-600">

                    <?php echo $r['nomor_antrian']; ?>

                </h3>

                <p class="text-sm text-gray-500">

                    Dokter :
                    <?php echo $r['dokter']; ?>

                </p>

                <p class="text-sm text-gray-500">

                    Tanggal :
                    <?php echo date('d M Y',strtotime($r['tanggal_periksa'])); ?>

                </p>

                <p class="text-sm text-gray-500">

                    Jam :
                    <?php echo date('H:i',strtotime($r['jam_periksa'])); ?> WIB

                </p>

            </div>

            <div class="text-right">

                <?php

                if($r['status']=="Menunggu Verifikasi"){

                    echo '<span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs">
                    Menunggu Verifikasi
                    </span>';

                }

                elseif($r['status']=="Menunggu"){

                    echo '<span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs">
                    Menunggu
                    </span>';

                }

                elseif($r['status']=="Dipanggil"){

                    echo '<span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
                    Dipanggil
                    </span>';

                }

                elseif($r['status']=="Diperiksa"){

                    echo '<span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-xs">
                    Diperiksa
                    </span>';

                }

                else{

                    echo '<span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                    Selesai
                    </span>';

                }

                ?>

                <br><br>

                <a
                href="cetak_bukti.php?id=<?php echo $r['id']; ?>"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs">

                    📄 Download PDF
                    

                </a>
<a href="riwayat.php"
class="inline-flex items-center justify-center mt-1 px-1 py-1 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">

📋 Lihat Semua Riwayat

</a>
            </div>

        </div>

    <?php

        }

    }else{

    ?>

        <div class="bg-gray-50 rounded-2xl p-6 text-center text-gray-500">

            Belum ada riwayat pendaftaran.

        </div>

    <?php } ?>

    </div>

</div>