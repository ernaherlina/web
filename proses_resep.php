<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config/koneksi.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != "apotek") {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("ID resep tidak ditemukan.");
}

$id = intval($_GET['id']);

$update = mysqli_query($conn,"
UPDATE resep
SET status='Sedang Disiapkan'
WHERE id='$id'
");

if($update){

    // Ambil ID User Pasien
    $q = mysqli_query($conn,"
    SELECT pd.id_user
    FROM resep r
    JOIN pemeriksaan pm
        ON r.id_pemeriksaan = pm.id
    JOIN pendaftaran pd
        ON pm.id_pendaftaran = pd.id
    WHERE r.id='$id'
    ");

    $pasien = mysqli_fetch_assoc($q);

    if($pasien){

        mysqli_query($conn,"
        INSERT INTO notifikasi
        (
            id_user,
            pesan,
            status_baca,
            created_at
        )
        VALUES
        (
            '".$pasien['id_user']."',
            '💊 Resep Anda sedang disiapkan oleh petugas apotek.',
            'Belum Dibaca',
            NOW()
        )
        ");

    }

    $_SESSION['success']="Resep sedang diproses.";

}else{

    $_SESSION['error']="Gagal memproses resep.";

}

if($update){

    $_SESSION['success']="Resep sedang diproses.";

}else{

    $_SESSION['error']="Gagal memproses resep.";

}

header("Location: dashboard_apotek.php");
exit;