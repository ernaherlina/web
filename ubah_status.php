<?php
include 'config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE pendaftaran
SET status='Dipanggil'
WHERE id='$id'
");

header("Location: dashboard_admin.php");
exit;
?>