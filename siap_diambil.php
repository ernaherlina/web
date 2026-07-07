<?php

include "config/koneksi.php";

$id = intval($_GET['id']);

/* ===========================
   UBAH STATUS RESEP
=========================== */

$update = mysqli_query($conn,"
UPDATE resep
SET status='Siap Diambil'
WHERE id='$id'
");

/* ===========================
   JIKA BERHASIL
=========================== */

if($update){

    // Ambil ID User Pasien
    $q = mysqli_query($conn,"
    SELECT
        pd.id_user
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
            '📦 Obat Anda sudah siap diambil. Silakan datang ke Klinik BTH.',
            'Belum Dibaca',
            NOW()
        )
        ");

    }

}

header("Location: dashboard_apotek.php?status=Siap Diambil");
exit;

?>