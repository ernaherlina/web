<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location: login.php");
    exit;
}

$id=(int)$_GET['id'];

$q=mysqli_query($conn,"
SELECT foto
FROM dokter
WHERE id='$id'
");

if(mysqli_num_rows($q)==0){

    header("Location: kelola_dokter.php");
    exit;

}

$d=mysqli_fetch_assoc($q);

if($d['foto']!=""){

    if(file_exists("uploads/".$d['foto'])){

        unlink("uploads/".$d['foto']);

    }

}

mysqli_query($conn,"
DELETE FROM dokter
WHERE id='$id'
");

echo "

<script>

alert('Dokter berhasil dihapus');

location='kelola_dokter.php';

</script>

";
?>