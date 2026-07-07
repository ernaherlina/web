<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: kelola_user.php");
    exit;
}

$id = (int)$_GET['id'];

/* Tidak boleh menghapus akun sendiri */

if($id == $_SESSION['id']){

    echo "

    <script>

    alert('Anda tidak dapat menghapus akun yang sedang digunakan.');

    location='kelola_user.php';

    </script>

    ";

    exit;
}

/* Ambil foto */

$q = mysqli_query($conn,"
SELECT foto
FROM users
WHERE id='$id'
");

if(mysqli_num_rows($q)==0){

    header("Location: kelola_user.php");
    exit;
}

$user = mysqli_fetch_assoc($q);

/* Hapus foto */

if(!empty($user['foto']) && file_exists("uploads/".$user['foto'])){

    unlink("uploads/".$user['foto']);

}

/* Hapus data */

mysqli_query($conn,"
DELETE FROM users
WHERE id='$id'
");

echo "

<script>

alert('User berhasil dihapus.');

location='kelola_user.php';

</script>

";
?>