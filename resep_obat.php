<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['id_dokter'])) {
    header("Location: login_dokter.php");
    exit;
}

$id_dokter = $_SESSION['id_dokter'];

$query = mysqli_query($conn,"
SELECT

r.id,
r.tanggal,
r.status,

pm.diagnosa,

p.nomor_antrian,
p.tanggal_periksa,
p.jam_periksa,

u.nama

FROM resep r

INNER JOIN pemeriksaan pm
ON pm.id = r.id_pemeriksaan

INNER JOIN pendaftaran p
ON p.id = pm.id_pendaftaran

INNER JOIN users u
ON u.id = p.id_user

WHERE
pm.id_dokter='$id_dokter'

ORDER BY
r.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Resep Obat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;
background:#f4f7fb;

}

.card{

background:white;
border-radius:20px;
box-shadow:0 8px 20px rgba(0,0,0,.08);

}

</style>

</head>

<body>

<div class="flex min-h-screen">

<!-- Sidebar -->

<div class="w-64 bg-white shadow-lg p-6">

    <h1 class="text-4xl font-bold text-blue-600">
        Klinik BTH
    </h1>

    <p class="text-gray-500">
        Dashboard Dokter
    </p>

    <div class="mt-10 space-y-2">

        <a href="dashboard_dokter.php"
        class="block px-4 py-3 rounded-xl hover:bg-blue-50">
            Dashboard
        </a>

        <a href="data_pasien.php"
        class="block px-4 py-3 rounded-xl hover:bg-blue-50">
            Data Pasien
        </a>

        <a href="riwayat_pasien.php"
        class="block px-4 py-3 rounded-xl hover:bg-blue-50">
            Riwayat Pasien
        </a>

        <a href="resep_obat.php"
        class="block px-4 py-3 rounded-xl bg-blue-100 text-blue-700 font-semibold">
            Resep Obat
        </a>

        <a href="logout.php"
        class="block mt-10 bg-red-500 hover:bg-red-600 text-white text-center py-3 rounded-xl">
            Logout
        </a>

    </div>

</div>

<!-- Content -->

<div class="flex-1 p-8">

    <h1 class="text-4xl font-bold">
        Resep Obat
    </h1>

    <p class="text-gray-500 mt-2">
        Daftar seluruh resep pasien yang telah dibuat
    </p>

    <div class="card mt-8 overflow-hidden">

        <table class="w-full">

            <thead class="bg-purple-600 text-white">

                <tr>

                    <th class="p-4">No</th>

                    <th>Nomor Antrian</th>

                    <th>Nama Pasien</th>

                    <th>Diagnosa</th>

                    <th>Tanggal Resep</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

            <?php

if(mysqli_num_rows($query)>0){

$no=1;

while($r=mysqli_fetch_assoc($query)){

?>

<tr class="border-b hover:bg-gray-50">

    <td class="p-4 text-center">
        <?= $no++; ?>
    </td>

    <td class="font-bold text-blue-600 text-center">
        <?= $r['nomor_antrian']; ?>
    </td>

    <td>
        <?= $r['nama']; ?>
    </td>

    <td>
        <?= $r['diagnosa']; ?>
    </td>

    <td class="text-center">
        <?= date('d-m-Y H:i',strtotime($r['tanggal'])); ?>
    </td>

    <td class="text-center">

        <?php

        if($r['status']=="Belum Diambil"){

            echo '<span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
            Belum Diambil
            </span>';

        }elseif($r['status']=="Sedang Disiapkan"){

            echo '<span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs">
            Sedang Disiapkan
            </span>';

        }elseif($r['status']=="Siap Diambil"){

            echo '<span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
            Siap Diambil
            </span>';

        }else{

            echo '<span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">'
            .$r['status'].
            '</span>';

        }

        ?>

    </td>

    <td class="text-center">

        <a href="detail_resep.php?id=<?= $r['id']; ?>"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

            Lihat Detail

        </a>

    </td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7"
class="text-center py-10 text-gray-500">

Belum ada resep yang dibuat.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>