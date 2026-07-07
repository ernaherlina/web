<?php
session_start();
include "config/koneksi.php";

date_default_timezone_set("Asia/Jakarta");

/*=========================================
LOGIN PASIEN
=========================================*/

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = intval($_SESSION['id']);

/*=========================================
AMBIL RESEP TERBARU PASIEN
=========================================*/

$query = mysqli_query($conn,"
SELECT

r.id AS id_resep,
r.status,
r.tanggal,

pm.id AS id_pemeriksaan,
pm.diagnosa,
pm.tindakan,
pm.instruksi,
pm.catatan,
pm.tanggal_pemeriksaan,

pd.nomor_antrian,
pd.tanggal_periksa,
pd.jam_periksa,

u.nama,
u.no_rm,
u.jenis_kelamin,
u.no_hp,

d.nama_dokter

FROM resep r

INNER JOIN pemeriksaan pm
ON r.id_pemeriksaan = pm.id

INNER JOIN pendaftaran pd
ON pm.id_pendaftaran = pd.id

INNER JOIN users u
ON pd.id_user = u.id

INNER JOIN dokter d
ON pm.id_dokter = d.id

WHERE pd.id_user='$id_user'

ORDER BY r.id DESC

LIMIT 1
");

if(!$query){
    die(mysqli_error($conn));
}

if(mysqli_num_rows($query)==0){
    die("Anda belum memiliki resep.");
}

$data = mysqli_fetch_assoc($query);

/*=========================================
AMBIL DAFTAR OBAT
=========================================*/

$obat = mysqli_query($conn,"
SELECT

ro.id,
ro.id_resep,
ro.id_obat,
ro.jumlah,
ro.aturan_pakai,

o.nama_obat,
o.satuan,
o.harga

FROM resep_obat ro

INNER JOIN obat o
ON ro.id_obat = o.id

WHERE ro.id_resep='".$data['id_resep']."'

ORDER BY ro.id ASC
");

if(!$obat){
    die(mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Resep Saya</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{

background:#f4f7fb;
font-family:'Poppins',sans-serif;

}

.card{

background:#fff;
border-radius:24px;
padding:24px;
box-shadow:0 10px 25px rgba(0,0,0,.06);

}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-4">

<h1 class="text-4xl font-bold text-blue-700">

💊 Resep Saya

</h1>

<p class="text-gray-500 mt-2">

Informasi resep yang diberikan dokter dan sedang diproses oleh apotek.

</p>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

    <!-- DATA PASIEN -->

    <div class="card">

        <h2 class="text-2xl font-bold text-blue-700 mb-6">

            👤 Data Pasien

        </h2>

        <table class="w-full">

            <tr>

                <td class="py-3 text-gray-500 w-1/3">

                    Nama

                </td>

                <td class="font-semibold">

                    <?= $data['nama']; ?>

                </td>

            </tr>

            <tr>

                <td class="py-3 text-gray-500">

                    No. Rekam Medis

                </td>

                <td>

                    <?= $data['no_rm']; ?>

                </td>

            </tr>

            <tr>

                <td class="py-3 text-gray-500">

                    Jenis Kelamin

                </td>

                <td>

                    <?= $data['jenis_kelamin']; ?>

                </td>

            </tr>

            <tr>

                <td class="py-3 text-gray-500">

                    No. HP

                </td>

                <td>

                    <?= $data['no_hp']; ?>

                </td>

            </tr>

            <tr>

                <td class="py-3 text-gray-500">

                    Dokter

                </td>

                <td>

                    <?= $data['nama_dokter']; ?>

                </td>

            </tr>

            <tr>

                <td class="py-3 text-gray-500">

                    Nomor Antrian

                </td>

                <td>

                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-bold">

                        <?= $data['nomor_antrian']; ?>

                    </span>

                </td>

            </tr>

        </table>

    </div>

    <!-- STATUS RESEP -->

    <div class="card">

        <h2 class="text-2xl font-bold text-green-700 mb-6">

            📦 Status Resep

        </h2>

        <div class="space-y-5">

            <div>

                <div class="text-gray-500 text-sm">

                    Tanggal Pemeriksaan

                </div>

                <div class="font-semibold text-lg">

                    <?= date('d-m-Y H:i',strtotime($data['tanggal_pemeriksaan'])); ?>

                </div>

            </div>

            <div>

                <div class="text-gray-500 text-sm">

                    Status Resep

                </div>

                <?php

                $warna="bg-yellow-100 text-yellow-700";

                if($data['status']=="Sedang Disiapkan"){

                    $warna="bg-blue-100 text-blue-700";

                }

                if($data['status']=="Siap Diambil"){

                    $warna="bg-green-100 text-green-700";

                }

                if($data['status']=="Sudah Diambil"){

                    $warna="bg-gray-200 text-gray-700";

                }

                ?>

                <span class="<?= $warna; ?> px-5 py-3 rounded-full font-bold inline-block mt-2">

                    <?= $data['status']; ?>

                </span>

            </div>

            <div>

                <div class="text-gray-500 text-sm">

                    Diagnosa

                </div>

                <div class="mt-2 bg-blue-50 rounded-xl p-4">

                    <?= nl2br($data['diagnosa']); ?>

                </div>

            </div>

            <div>

                <div class="text-gray-500 text-sm">

                    Instruksi Dokter

                </div>

                <div class="mt-2 bg-green-50 rounded-xl p-4">

                    <?= nl2br($data['instruksi']); ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

$total_obat = 0;

?>

<div class="card mt-6">

    <div class="flex justify-between items-center mb-3">

        <h2 class="text-1xl font-bold text-blue-700">

            💊 Daftar Obat

        </h2>

        <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-semibold">

            <?= mysqli_num_rows($obat); ?> Obat

        </span>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="bg-gray-100">

                    <th class="py-3">No</th>

                    <th class="text-left">Nama Obat</th>

                    <th>Jumlah</th>

                    <th>Satuan</th>

                    <th>Aturan Pakai</th>

                    <th>Harga</th>

                    <th>Subtotal</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while($o=mysqli_fetch_assoc($obat)){

                $subtotal = $o['harga'] * $o['jumlah'];

                $total_obat += $subtotal;

            ?>

                <tr class="border-b hover:bg-gray-50">

                    <td class="text-center py-2">

                        <?= $no++; ?>

                    </td>

                    <td>

                        <div class="font-semibold">

                            <?= $o['nama_obat']; ?>

                        </div>

                    </td>

                    <td class="text-center">

                        <?= $o['jumlah']; ?>

                    </td>

                    <td class="text-center">

                        <?= $o['satuan']; ?>

                    </td>

                    <td class="text-center">

                        <?= $o['aturan_pakai']; ?>

                    </td>

                    <td class="text-center">

                        Rp <?= number_format($o['harga'],0,',','.'); ?>

                    </td>

                    <td class="text-center font-bold text-blue-700">

                        Rp <?= number_format($subtotal,0,',','.'); ?>

                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

<div class="card mt-3">

    <div class="flex justify-between items-center">

        <div>

            <div class="text-gray-500">

                Total Biaya Obat

            </div>

            <div class="text-2xl font-bold text-green-600 mt-1">

                Rp <?= number_format($total_obat,0,',','.'); ?>

            </div>

        </div>

        <div class="text-3xl">

            💰

        </div>

    </div>

</div>

<div class="card mt-3">

    <?php

    $biaya_pemeriksaan = 25000;
    $total_tagihan = $biaya_pemeriksaan + $total_obat;

    ?>

    <h2 class="text-1xl font-bold text-green-400 mb-6">

        💰 Rincian Biaya

    </h2>

    <table class="w-full">

        <tr class="border-b">

            <td class="py-3">

                Biaya Pemeriksaan

            </td>

            <td class="text-right font-semibold">

                Rp <?= number_format($biaya_pemeriksaan,0,',','.'); ?>

            </td>

        </tr>

        <tr class="border-b">

            <td class="py-4">

                Biaya Obat

            </td>

            <td class="text-right font-semibold">

                Rp <?= number_format($total_obat,0,',','.'); ?>

            </td>

        </tr>

        <tr>

            <td class="pt-3 text-1xl font-bold">

                TOTAL TAGIHAN

            </td>

            <td class="pt-3 text-right text-1xl font-bold text-green-600">

                Rp <?= number_format($total_tagihan,0,',','.'); ?>

            </td>

        </tr>

    </table>

</div>

<div class="card mt-3 bg-yellow-50 border border-yellow-300">

    <h2 class="text-xl font-bold text-yellow-700">

        📢 Status Pembayaran

    </h2>

    <p class="mt-4 text-gray-700 leading-8">

        Pembayaran dilakukan langsung di loket Klinik BTH.

        Sistem ini hanya menampilkan estimasi biaya pelayanan.

        Pasien melakukan pembayaran kepada petugas administrasi saat mengambil obat.

    </p>

</div>

<div class="flex justify-between mt-8">

    <a href="javascript:history.back()"
    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl">
    
    ← Kembali
</a>

    <a
    href="cetak_resep.php?id=<?= $data['id_resep']; ?>"
    target="_blank"
    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-xl">

        🖨 Cetak Resep

    </a>

</div>

</div>

</body>

</html>