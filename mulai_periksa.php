<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

$id = intval($_GET['id']);

/* ubah status */

mysqli_query($conn,"
UPDATE pendaftaran
SET status='Diperiksa'
WHERE id='$id'
");

/* ambil id user */

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id_user, nomor_antrian
FROM pendaftaran
WHERE id='$id'
"));

/* kirim notifikasi */

mysqli_query($conn,"
INSERT INTO notifikasi(
id_user,
pesan
)
VALUES(
'{$data['id_user']}',
'Pemeriksaan telah dimulai. Silakan menuju ruang dokter. Nomor Antrian {$data['nomor_antrian']}.'
)
");

header("Location: pemeriksaan.php?id=".$id);
exit;