<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id = $_POST['id'];
$nama = $_POST['nama'];
$nim_nip = $_POST['nim_nip'];
$no_hp = $_POST['no_hp'];
$jenis_kelamin = $_POST['jenis_kelamin'];

/* UPDATE DATA USER */

mysqli_query($conn,"
UPDATE users
SET
nama='$nama',
nim_nip='$nim_nip',
no_hp='$no_hp',
jenis_kelamin='$jenis_kelamin'
WHERE id='$id'
");

/* UPLOAD FOTO JIKA ADA */

if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ''){

    $nama_foto = time().'_'.$_FILES['foto']['name'];

    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        'uploads/'.$nama_foto
    );

    mysqli_query($conn,"
    UPDATE users
    SET foto='$nama_foto'
    WHERE id='$id'
    ");
}

/* UPDATE SESSION */

$_SESSION['nama'] = $nama;

/* KEMBALI KE PROFIL */

header("Location: dashboard.php");
exit;
?>