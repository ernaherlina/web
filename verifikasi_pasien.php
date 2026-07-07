<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);

mysqli_query($conn, "
UPDATE pendaftaran
SET status='Menunggu'
WHERE id='$id'
");

header("Location: dashboard_admin.php");
exit;
?>

$id = intval($_GET['id']);

// Ambil data pendaftaran
$cek = mysqli_query($conn, "
SELECT *
FROM pendaftaran
WHERE id='$id'
");

if(mysqli_num_rows($cek) == 0){

    echo "<script>
    alert('Data tidak ditemukan');
    window.location='dashboard_admin.php';
    </script>";

    exit;
}

$data = mysqli_fetch_assoc($cek);


/* =====================================
   CEK NOMOR REKAM MEDIS
===================================== */

$qUser = mysqli_query($conn,"
SELECT
    id_user
FROM pendaftaran
WHERE id='$id'
");

$user = mysqli_fetch_assoc($qUser);

$id_user = $user['id_user'];

$cekRM = mysqli_query($conn,"
SELECT
    no_rm
FROM users
WHERE id='$id_user'
");

$dataRM = mysqli_fetch_assoc($cekRM);

if(empty($dataRM['no_rm'])){

    $ambilRM = mysqli_query($conn,"
    SELECT no_rm
    FROM users
    WHERE no_rm IS NOT NULL
    ORDER BY id DESC
    LIMIT 1
    ");

    if(mysqli_num_rows($ambilRM)>0){

        $last = mysqli_fetch_assoc($ambilRM);

        $nomor = intval(substr($last['no_rm'],2))+1;

    }else{

        $nomor = 1;

    }

    $noRM = "RM".str_pad($nomor,6,"0",STR_PAD_LEFT);

    mysqli_query($conn,"
    UPDATE users
    SET no_rm='$noRM'
    WHERE id='$id_user'
    ");

}

// Ubah status menjadi Menunggu
$update = mysqli_query($conn,"
UPDATE pendaftaran
SET status='Menunggu'
WHERE id='$id'
");

if($update){

    // Tambahkan notifikasi
    mysqli_query($conn,"
    INSERT INTO notifikasi
    (
        id_user,
        pesan,
        status_baca
    )
    VALUES
    (
        '".$data['id_user']."',
        'Pendaftaran Anda telah diverifikasi Admin. Silakan datang sesuai jadwal pemeriksaan.',
        'Belum Dibaca'
    )
    ");

    echo "<script>
    alert('Pasien berhasil diverifikasi');
    window.location='dashboard_admin.php';
    </script>";

}else{

    echo "<script>
    alert('Verifikasi gagal');
    history.back();
    </script>";

}
?>