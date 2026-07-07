<?php
session_start();
include 'config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

$nama_dokter = $_SESSION['nama_dokter'];
$hari_ini = date('Y-m-d');

/* ======================
   STATISTIK
====================== */

$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pendaftaran
WHERE dokter='$nama_dokter'
AND tanggal_periksa='$hari_ini'
"))['total'];

$menunggu = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pendaftaran
WHERE dokter='$nama_dokter'
AND tanggal_periksa='$hari_ini'
AND status='Menunggu'
"))['total'];

$dipanggil = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pendaftaran
WHERE dokter='$nama_dokter'
AND tanggal_periksa='$hari_ini'
AND status='Dipanggil'
"))['total'];

$selesai = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pendaftaran
WHERE dokter='$nama_dokter'
AND tanggal_periksa='$hari_ini'
AND status='Selesai'
"))['total'];

/* ======================
   PASIEN HARI INI
====================== */

$query = mysqli_query($conn,"
SELECT pendaftaran.*, users.nama
FROM pendaftaran
JOIN users
ON pendaftaran.id_user = users.id
WHERE dokter='$nama_dokter'
AND status IN ('Menunggu','Dipanggil')
ORDER BY tanggal_periksa ASC, jam_periksa ASC
");
$total = mysqli_num_rows($query);

/* ======================
   RIWAYAT PEMERIKSAAN
====================== */

$riwayat = mysqli_query($conn,"
SELECT
    pm.id,
    pd.nomor_antrian,
    pd.jam_periksa,
    u.nama,
    pm.diagnosa
FROM pemeriksaan pm

JOIN pendaftaran pd
ON pm.id_pendaftaran = pd.id

JOIN users u
ON pd.id_user = u.id

WHERE
pd.dokter='$nama_dokter'
AND pd.status='Selesai'

ORDER BY pm.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Dokter</title>

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

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <div class="w-56 bg-white shadow-lg p-4">
        <h1 class="text-3xl font-bold text-blue-600">
            Klinik BTH
        </h1>

        <p class="text-gray-500 mt-1">
            Dashboard Dokter
        </p>

        <div class="mt-10 space-y-3">

            <a href="#"
            class="block bg-blue-50 text-blue-600 p-4 rounded-2xl font-medium">
            Dashboard
            </a>

            <a href="data_pasien.php"
            class="block hover:bg-gray-100 p-4 rounded-2xl">
            Data Pasien
            </a>

            <a href="riwayat_pasien.php"
            class="block hover:bg-gray-100 p-4 rounded-2xl">
                Riwayat Pasien
            </a>

            <a href="resep_obat.php"
            class="block hover:bg-gray-100 p-4 rounded-2xl">
                Resep Obat
            </a>

            <a href="logout.php"
            class="block bg-red-500 text-white p-4 rounded-2xl text-center mt-20">
                Logout
            </a>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-5">

        <!-- HEADER -->
        <div class="mb-8">

            <h1 class="text-2xl font-bold text-gray-800">
                Dashboard Dokter 👨‍⚕️
            </h1>

            <p class="text-gray-500 mt-2">
                Selamat Datang,
                <?php echo $nama_dokter; ?>
            </p>

        </div>

        <!-- CARD -->
        <div class="grid grid-cols-4 gap-3 mb-5">

            <div class="bg-white p-4 rounded-2xl shadow">
                <p class="text-gray-500">Total Pasien</p>
                <h1 class="text-2xl font-bold text-blue-600 mt-2">
                    <?php echo $total; ?>
                </h1>
            </div>

            <div class="bg-orange-50 p-4 rounded-2xl shadow">
                <p class="text-gray-500">Menunggu</p>
                <h1 class="text-2xl font-bold text-orange-600 mt-2">
                    <?php echo $menunggu; ?>
                </h1>
            </div>

            <div class="bg-cyan-50 p-4 rounded-2xl shadow">
                <p class="text-gray-500">Dipanggil</p>
                <h1 class="text-2xl font-bold text-cyan-600 mt-2">
                    <?php echo $dipanggil; ?>
                </h1>
            </div>

            <div class="bg-green-50 p-4 rounded-2xl shadow">
                <p class="text-gray-500">Selesai</p>
                <h1 class="text-2xl font-bold text-green-600 mt-2">
                    <?php echo $selesai; ?>
                </h1>
            </div>

        </div>

<!-- PASIEN AKTIF -->
<div class="hidden">

            <h2 class="text-xl font-semibold">
                Pasien Sedang Diperiksa
            </h2>

            <?php

$aktif = mysqli_query($conn,"
SELECT pendaftaran.*, users.nama
FROM pendaftaran
JOIN users
ON pendaftaran.id_user=users.id
WHERE dokter='$nama_dokter'
AND status='Diperiksa'
ORDER BY tanggal_periksa ASC, jam_periksa ASC
LIMIT 1
");

            if(mysqli_num_rows($aktif)>0){

                $pasien = mysqli_fetch_assoc($aktif);

                echo "
                <h1 class='text-4xl font-bold mt-3'>
                {$pasien['nomor_antrian']}
                </h1>

                <p class='mt-2'>
                Keluhan : {$pasien['keluhan']}
                </p>
                ";

            }else{

                echo "
                <p class='mt-3'>
                Tidak ada pasien yang sedang diperiksa
                </p>
                ";
            }

            ?>

        </div>

        <!-- TABEL -->
        <div class="bg-white rounded-3xl shadow overflow-hidden">

            <div class="p-6 border-b">

                <h2 class="text-2xl font-bold text-gray-800">
                    Data Pasien Hari Ini
                </h2>

            </div>

            <table class="w-full">

                <thead class="bg-blue-600 text-white">

                    <tr>

                        <th class="p-3">Antrian</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Jam</th>
                        <th class="p-3">Keluhan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($query)>0){

                    while($data=mysqli_fetch_assoc($query)){

                ?>

                <tr class="border-b text-center hover:bg-gray-50">

                    <td class="p-5 font-bold text-blue-600">
                        <?php echo $data['nomor_antrian']; ?>
                    </td>

                    <td class="p-5">
                        <?php echo $data['nama']; ?>
                    </td>

                    <td class="p-5 font-bold text-indigo-600">
                        <?php echo date('H:i',strtotime($data['jam_periksa'])); ?>
                    </td>

                    <td class="p-5">
                        <?php echo $data['keluhan']; ?>
                    </td>

                    <td class="p-5">

                        <?php
                        if($data['status']=="Menunggu"){
                            echo '<span class="bg-orange-100 text-orange-500 px-4 py-2 rounded-full">Menunggu</span>';
                        }
                        elseif($data['status']=="Dipanggil"){
                            echo '<span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full">Dipanggil</span>';
                        }
                        else{
                            echo '<span class="bg-green-100 text-green-600 px-4 py-2 rounded-full">Selesai</span>';
                        }
                        ?>

                    </td>

<td class="p-5">

<?php

if($data['status']=="Dipanggil"){

?>

<a href="mulai_periksa.php?id=<?php echo $data['id']; ?>"
class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-xl">

Mulai Periksa

</a>

<?php

}elseif($data['status']=="Diperiksa"){

?>

<a href="pemeriksaan.php?id=<?php echo $data['id']; ?>"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

Lanjut Pemeriksaan

</a>

<?php

}elseif($data['status']=="Selesai"){

?>

<a href="detail_pemeriksaan.php?id=<?php echo $data['id']; ?>"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl">

Detail

</a>

<?php

}else{

echo "-";

}

?>

</td>

                </tr>

                <?php
                    }
                }else{
                ?>

                <tr>

                    <td colspan="6"
                    class="text-center p-10 text-gray-500">

                        Belum ada pasien hari ini

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <!-- RIWAYAT PEMERIKSAAN -->

<div class="bg-white rounded-3xl shadow mt-8 overflow-hidden">

<div class="p-6 border-b">

<h2 class="text-2xl font-bold text-gray-800">

Riwayat Pemeriksaan Hari Ini

</h2>

<p class="text-gray-500 mt-2">

Pasien yang telah selesai diperiksa.

</p>

</div>

<table class="w-full">

<thead class="bg-green-600 text-white">

<tr>

<th class="p-3">Antrian</th>

<th class="p-3">Nama</th>

<th class="p-3">Jam</th>

<th class="p-3">Diagnosa</th>

<th class="p-3">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($riwayat)>0){

while($r=mysqli_fetch_assoc($riwayat)){

?>

<tr class="border-b text-center hover:bg-gray-50">

<td class="p-4 font-bold text-blue-600">

<?php echo $r['nomor_antrian']; ?>

</td>

<td class="p-4">

<?php echo $r['nama']; ?>

</td>

<td class="p-4">

<?php echo date('H:i',strtotime($r['jam_periksa'])); ?>

</td>

<td class="p-4">

<?php echo $r['diagnosa']; ?>

</td>

<td class="p-4">

<a
href="detail_pemeriksaan.php?id=<?php echo $r['id'];?>"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl">

Detail

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5" class="text-center p-8 text-gray-500">

Belum ada pemeriksaan selesai.

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

