<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];


mysqli_query($conn,"
UPDATE notifikasi
SET status_baca='Dibaca'
WHERE id_user='$id_user'
");

/* Ambil notifikasi */
$query = mysqli_query($conn,"
SELECT *
FROM notifikasi
WHERE id_user='$id_user'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Notifikasi</title>

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

<div class="max-w-4xl mx-auto p-8">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-4xl font-bold text-blue-600">
                🔔 Notifikasi
            </h1>

            <p class="text-gray-500 mt-2">
                Informasi terbaru dari Klinik BTH
            </p>

        </div>

        <a href="dashboard.php"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

            Kembali

        </a>

    </div>

    <?php

    if(mysqli_num_rows($query) > 0){

        while($data = mysqli_fetch_assoc($query)){

    ?>

    <div class="bg-white rounded-2xl shadow p-5 mb-4">

        <div class="flex justify-between">

            <div>

                <h3 class="font-semibold text-gray-800">

                    <?php echo $data['pesan']; ?>

                </h3>

                <p class="text-sm text-gray-400 mt-2">

                    <?php echo $data['created_at']; ?>

                </p>

            </div>

        </div>

    </div>

    <?php

        }

    }else{

    ?>

    <div class="bg-white rounded-2xl shadow p-10 text-center">

        <div class="text-6xl mb-3">
            🔔
        </div>

        <h2 class="text-xl font-semibold text-gray-700">

            Belum Ada Notifikasi

        </h2>

        <p class="text-gray-500 mt-2">

            Notifikasi dari admin dan dokter akan muncul di sini.

        </p>

    </div>

    <?php } ?>

</div>

</body>
</html>