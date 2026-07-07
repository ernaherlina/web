<?php
session_start();
include 'config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Obat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Poppins',sans-serif;
    background:#f5f7fb;
}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-8">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-3xl font-bold text-blue-700">
                Data Obat
            </h1>

            <p class="text-gray-500">
                Daftar seluruh obat Klinik BTH
            </p>
        </div>

        <a href="tambah_obat.php"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">

            + Tambah Obat

        </a>

    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-blue-600 text-white">

                <tr>

                    <th class="p-4">No</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Bentuk</th>
                    <th>Dosis</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Keterangan</th>
                    <th width="180">Aksi</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no=1;

            while($d=mysqli_fetch_assoc($query)){

            ?>

            <tr class="border-b hover:bg-gray-50">

                <td class="p-4 text-center"><?= $no++; ?></td>

                <td><?= $d['nama_obat']; ?></td>

                <td><?= $d['kategori']; ?></td>

                <td><?= $d['bentuk']; ?></td>

                <td><?= $d['dosis_default']; ?></td>

                <td><?= $d['satuan']; ?></td>

                <td>

                    Rp <?= number_format($d['harga'],0,',','.'); ?>

                </td>

                <td><?= $d['stok']; ?></td>

                <td><?= $d['keterangan']; ?></td>

                <td>

                    <div class="flex gap-2 justify-center">

                        <a href="edit_obat.php?id=<?= $d['id']; ?>"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">

                        Edit

                        </a>

                        <a href="hapus_obat.php?id=<?= $d['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus obat ini?')"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">

                        Hapus

                        </a>

                    </div>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>

