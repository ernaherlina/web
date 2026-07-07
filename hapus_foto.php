<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT foto
FROM users
WHERE id='$id'
"));

if(!empty($user['foto'])){

    $file = 'uploads/'.$user['foto'];

    if(file_exists($file)){
        unlink($file);
    }

    mysqli_query($conn,"
    UPDATE users
    SET foto=NULL
    WHERE id='$id'
    ");
}

header("Location: profil.php");
exit;
?>