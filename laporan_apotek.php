<?php
session_start();
include 'config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

/*====================================
FILTER
====================================*/

$tgl_awal = isset($_GET['tgl_awal'])
            ? $_GET['tgl_awal']
            : date('Y-m-01');

$tgl_akhir = isset($_GET['tgl_akhir'])
             ? $_GET['tgl_akhir']
             : date('Y-m-d');

$status = isset($_GET['status'])
          ? $_GET['status']
          : '';

$cari = isset($_GET['cari'])
        ? mysqli_real_escape_string($conn,$_GET['cari'])
        : '';

$where = "DATE(r.tanggal) BETWEEN '$tgl_awal' AND '$tgl_akhir'";

if($status!=""){

    $where .= " AND r.status='$status'";

}

if($cari!=""){

    $where .= " AND (

        u.nama LIKE '%$cari%'

        OR

        u.no_rm LIKE '%$cari%'

        OR

        p.nomor_antrian LIKE '%$cari%'

    )";

}

/*====================================
STATISTIK
====================================*/

$totalResep = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
LEFT JOIN pemeriksaan pm ON pm.id=r.id_pemeriksaan
LEFT JOIN pendaftaran p ON p.id=pm.id_pendaftaran
LEFT JOIN users u ON u.id=p.id_user
WHERE $where
"))['total'];

$totalBelum = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
LEFT JOIN pemeriksaan pm ON pm.id=r.id_pemeriksaan
LEFT JOIN pendaftaran p ON p.id=pm.id_pendaftaran
LEFT JOIN users u ON u.id=p.id_user
WHERE $where
AND r.status='Belum Diambil'
"))['total'];

$totalProses = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
LEFT JOIN pemeriksaan pm ON pm.id=r.id_pemeriksaan
LEFT JOIN pendaftaran p ON p.id=pm.id_pendaftaran
LEFT JOIN users u ON u.id=p.id_user
WHERE $where
AND r.status='Sedang Disiapkan'
"))['total'];

$totalDiambil = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
LEFT JOIN pemeriksaan pm ON pm.id=r.id_pemeriksaan
LEFT JOIN pendaftaran p ON p.id=pm.id_pendaftaran
LEFT JOIN users u ON u.id=p.id_user
WHERE $where
AND r.status='Sudah Diambil'
"))['total'];

$totalPendapatan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_harga) total
FROM resep r
LEFT JOIN pemeriksaan pm ON pm.id=r.id_pemeriksaan
LEFT JOIN pendaftaran p ON p.id=pm.id_pendaftaran
LEFT JOIN users u ON u.id=p.id_user
WHERE $where
"))['total'];

if($totalPendapatan==""){

    $totalPendapatan=0;

}
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1">

<title>

Laporan Apotek

</title>

<script src="https://cdn.tailwindcss.com"></script>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

*{

font-family:'Poppins',sans-serif;

}

body{

background:#eef4fb;

}

.card{

transition:.25s;

}

.card:hover{

transform:translateY(-4px);

box-shadow:0 15px 35px rgba(0,0,0,.12);

}

.table-scroll::-webkit-scrollbar{

height:8px;

}

.table-scroll::-webkit-scrollbar-thumb{

background:#d1d5db;

border-radius:20px;

}

</style>

</head>

<body>

<div class="max-w-[1650px] mx-auto px-6 py-5">

<!-- ===========================================================
HEADER
=========================================================== -->

<div class="flex items-center justify-between mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            Laporan Apotek

        </h1>

        <p class="text-gray-500 text-sm mt-1">

            Rekapitulasi pelayanan obat Klinik BTH

        </p>

    </div>

    <div class="flex gap-3">

        <a href="dashboard_apotek.php"
        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-sm">

            Dashboard

        </a>

        <a
href="cetak_laporan.php?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>&status=<?= $status ?>"
target="_blank"
class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-semibold">
PDF
</a>
        <a href="#"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm">

            Excel

        </a>

    </div>

</div>


<!-- ===========================================================
FILTER
=========================================================== -->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">

<form method="GET">

<div class="grid lg:grid-cols-5 md:grid-cols-2 gap-4">

<div>

<label class="text-sm font-semibold text-gray-600">

Tanggal Awal

</label>

<input

type="date"

name="tgl_awal"

value="<?= $tgl_awal ?>"

class="w-full mt-2 rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500">

</div>

<div>

<label class="text-sm font-semibold text-gray-600">

Tanggal Akhir

</label>

<input

type="date"

name="tgl_akhir"

value="<?= $tgl_akhir ?>"

class="w-full mt-2 rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500">

</div>

<div>

<label class="text-sm font-semibold text-gray-600">

Status

</label>

<select

name="status"

class="w-full mt-2 rounded-xl border-gray-300">

<option value="">Semua Status</option>

<option value="Belum Diambil"

<?=($status=="Belum Diambil")?"selected":"";?>>

Belum Diambil

</option>

<option value="Sedang Disiapkan"

<?=($status=="Sedang Disiapkan")?"selected":"";?>>

Sedang Disiapkan

</option>

<option value="Sudah Diambil"

<?=($status=="Sudah Diambil")?"selected":"";?>>

Sudah Diambil

</option>

</select>

</div>

<div>

<label class="text-sm font-semibold text-gray-600">

Cari Pasien

</label>

<input

type="text"

name="cari"

value="<?= $cari ?>"

placeholder="Nama / RM / Antrian"

class="w-full mt-2 rounded-xl border-gray-300">

</div>

<div class="flex items-end">

<button

class="bg-blue-600 hover:bg-blue-700 text-white w-full py-3 rounded-xl font-semibold">

Tampilkan

</button>

</div>

</div>

</form>

</div>



<!-- ===========================================================
CARD STATISTIK
=========================================================== -->

<div class="grid xl:grid-cols-5 lg:grid-cols-3 md:grid-cols-2 gap-4 mb-6">


<!-- Total Resep -->

<div class="card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

<div class="flex justify-between items-center">

<div>

<p class="text-gray-500 text-xs uppercase">

Total Resep

</p>

<h2 class="text-3xl font-bold text-blue-600 mt-2">

<?= $totalResep ?>

</h2>

</div>

<div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

📄

</div>

</div>

</div>


<!-- Sudah -->

<div class="card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

<div class="flex justify-between items-center">

<div>

<p class="text-gray-500 text-xs uppercase">

Sudah Diambil

</p>

<h2 class="text-3xl font-bold text-green-600 mt-2">

<?= $totalDiambil ?>

</h2>

</div>

<div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

✅

</div>

</div>

</div>


<!-- Diproses -->

<div class="card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

<div class="flex justify-between items-center">

<div>

<p class="text-gray-500 text-xs uppercase">

Diproses

</p>

<h2 class="text-3xl font-bold text-orange-500 mt-2">

<?= $totalProses ?>

</h2>

</div>

<div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">

⏳

</div>

</div>

</div>



<!-- Belum -->

<div class="card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

<div class="flex justify-between items-center">

<div>

<p class="text-gray-500 text-xs uppercase">

Belum Diambil

</p>

<h2 class="text-3xl font-bold text-red-500 mt-2">

<?= $totalBelum ?>

</h2>

</div>

<div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">

❗

</div>

</div>

</div>



<!-- Pendapatan -->

<div class="card rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-sm p-4">

<p class="text-xs uppercase opacity-80">

Pendapatan

</p>

<h2 class="text-2xl font-bold mt-3">

Rp <?= number_format($totalPendapatan,0,",","."); ?>

</h2>

<p class="text-xs mt-1 opacity-80">

Total penjualan obat

</p>

</div>

</div>

<?php

/* =====================================================
   TOP 5 OBAT
===================================================== */

$topObat = mysqli_query($conn,"
SELECT
o.nama_obat,
SUM(ro.jumlah) AS total
FROM resep_obat ro
JOIN obat o ON o.id = ro.id_obat
GROUP BY ro.id_obat
ORDER BY total DESC
LIMIT 5
");

/* =====================================================
   STOK HAMPIR HABIS
===================================================== */

$stokHabis = mysqli_query($conn,"
SELECT
nama_obat,
stok
FROM obat
WHERE stok <= 10
ORDER BY stok ASC
LIMIT 5
");

?>

<div class="grid lg:grid-cols-3 gap-5 mb-6">

    <!-- ===========================
         TOP 5 OBAT
    ============================ -->

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">

        <div class="flex justify-between items-center px-5 py-4 border-b">

            <div>

                <h2 class="font-bold text-lg text-slate-700">

                    Top 5 Obat Paling Banyak Digunakan

                </h2>

                <p class="text-gray-400 text-sm">

                    Berdasarkan jumlah resep

                </p>

            </div>

        </div>

        <div class="p-5">

            <?php

            $max = 1;

            mysqli_data_seek($topObat,0);

            while($cek=mysqli_fetch_assoc($topObat)){

                if($cek['total']>$max){

                    $max=$cek['total'];

                }

            }

            mysqli_data_seek($topObat,0);

            $ranking=1;

            while($o=mysqli_fetch_assoc($topObat)){

                $persen=($o['total']/$max)*100;

            ?>

            <div class="mb-5">

                <div class="flex justify-between mb-2">

                    <div class="flex items-center gap-3">

                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">

                            <?= $ranking ?>

                        </span>

                        <span class="font-semibold text-gray-700">

                            <?= $o['nama_obat'] ?>

                        </span>

                    </div>

                    <span class="font-bold text-blue-600">

                        <?= $o['total'] ?>

                    </span>

                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">

                    <div
                    class="bg-blue-600 h-2 rounded-full"
                    style="width:<?= $persen ?>%">

                    </div>

                </div>

            </div>

            <?php

            $ranking++;

            }

            ?>

        </div>

    </div>



    <!-- ===========================
         STOK HAMPIR HABIS
    ============================ -->

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

        <div class="px-5 py-4 border-b">

            <h2 class="font-bold text-lg text-red-600">

                Obat Hampir Habis

            </h2>

            <p class="text-gray-400 text-sm">

                Stok ≤ 10

            </p>

        </div>

        <div class="p-5">

            <?php

            if(mysqli_num_rows($stokHabis)>0){

            while($s=mysqli_fetch_assoc($stokHabis)){

            ?>

            <div class="flex justify-between items-center mb-4 p-3 rounded-xl bg-red-50">

                <div>

                    <h4 class="font-semibold text-gray-700">

                        <?= $s['nama_obat'] ?>

                    </h4>

                    <small class="text-gray-500">

                        Perlu Restock

                    </small>

                </div>

                <div>

                    <span class="bg-red-600 text-white px-3 py-2 rounded-lg font-bold">

                        <?= $s['stok'] ?>

                    </span>

                </div>

            </div>

            <?php

            }

            }else{

            ?>

            <div class="text-center py-10">

                <div class="text-5xl mb-3">

                    ✅

                </div>

                <h4 class="font-semibold">

                    Semua stok masih aman

                </h4>

                <p class="text-gray-500 text-sm">

                    Tidak ada obat yang perlu direstock.

                </p>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php

/* =====================================================
   DETAIL LAPORAN
===================================================== */

$laporan = mysqli_query($conn, "
SELECT
    r.id,
    r.tanggal,
    r.status,
    r.total_harga,

    u.no_rm,
    u.nama,

    p.nomor_antrian,

    d.nama_dokter,

    COUNT(ro.id) AS jumlah_obat

FROM resep r

JOIN pemeriksaan pm
ON pm.id = r.id_pemeriksaan

JOIN pendaftaran p
ON p.id = pm.id_pendaftaran

JOIN users u
ON u.id = p.id_user

LEFT JOIN dokter d
ON d.id = pm.id_dokter

LEFT JOIN resep_obat ro
ON ro.id_resep = r.id

WHERE $where

GROUP BY r.id

ORDER BY r.tanggal DESC

LIMIT 100

");

?>

<!-- ==========================================================
DETAIL LAPORAN
=========================================================== -->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <!-- HEADER -->

    <div class="flex justify-between items-center px-6 py-4 border-b">

        <div>

            <h2 class="text-xl font-bold text-slate-700">

                Detail Laporan

            </h2>

            <p class="text-sm text-gray-500">

                Riwayat pelayanan obat pasien

            </p>

        </div>

        <input

        type="text"

        id="searchTable"

        placeholder="Cari pasien..."

        class="border rounded-xl px-4 py-2 w-72">

    </div>

    <div class="overflow-x-auto table-scroll">

    <table
    id="tabelLaporan"
    class="min-w-full">

        <thead
        class="bg-slate-100 sticky top-0">

            <tr>

                <th class="px-4 py-3 text-left">Tanggal</th>

                <th class="px-4 py-3 text-left">RM</th>

                <th class="px-4 py-3 text-left">Pasien</th>

                <th class="px-4 py-3 text-left">Antrian</th>

                <th class="px-4 py-3 text-left">Dokter</th>

                <th class="px-4 py-3 text-center">Obat</th>

                <th class="px-4 py-3 text-right">Total</th>

                <th class="px-4 py-3 text-center">Status</th>

                <th class="px-4 py-3 text-center">Aksi</th>

            </tr>

        </thead>

        <tbody>

<?php

while($r=mysqli_fetch_assoc($laporan)){

?>

<tr class="border-b hover:bg-blue-50">

<td class="px-4 py-3">

<?= date('d/m/Y',strtotime($r['tanggal'])) ?>

</td>

<td>

<?= $r['no_rm'] ?>

</td>

<td>

<div class="font-semibold">

<?= $r['nama'] ?>

</div>

</td>

<td>

<?= $r['nomor_antrian'] ?>

</td>

<td>

<?= $r['nama_dokter'] ?>

</td>

<td class="text-center">

<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

<?= $r['jumlah_obat'] ?>

</span>

</td>

<td class="text-right font-semibold">

Rp <?= number_format($r['total_harga'],0,",",".") ?>

</td>

<td class="text-center">

<?php

if($r['status']=="Sudah Diambil"){

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Sudah</span>';

}elseif($r['status']=="Sedang Disiapkan"){

echo '<span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">Proses</span>';

}else{

echo '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Belum</span>';

}

?>

</td>

<td class="text-center">

<a

href="detail_resep.php?id=<?= $r['id'] ?>"

class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm">

Detail

</a>

</td>

</tr>

<?php } ?>

        </tbody>

    </table>

    </div>

</div>

<script>

const input=document.getElementById("searchTable");

input.addEventListener("keyup",function(){

let filter=input.value.toLowerCase();

let rows=document.querySelectorAll("#tabelLaporan tbody tr");

rows.forEach(function(row){

let text=row.innerText.toLowerCase();

row.style.display=text.includes(filter)?"":"none";

});

});

</script>