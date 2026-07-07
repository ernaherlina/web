<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location:login.php");
    exit;
}

if(trim($_SESSION['status_user'])!="Petugas Apotek"){
    header("Location:login.php");
    exit;
}

if(!isset($_GET['id']) || !isset($_GET['status'])){
    die("Data tidak lengkap.");
}

$id = intval($_GET['id']);
$status = mysqli_real_escape_string($conn,$_GET['status']);

/*=====================================
STATUS YANG DIIZINKAN
=====================================*/

$status_valid = [
    "Sedang Disiapkan",
    "Siap Diambil",
    "Sudah Diambil"
];

if(!in_array($status,$status_valid)){
    die("Status tidak valid.");
}

/*=====================================
UPDATE STATUS RESEP
=====================================*/

$update = mysqli_query($conn,"
UPDATE resep
SET status='$status'
WHERE id='$id'
");

if(!$update){

    die(mysqli_error($conn));

}

/*=====================================
KEMBALI
=====================================*/

header("Location:detail_resep_apotek.php?id=".$id);
exit;
?>