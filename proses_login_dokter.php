<?php

session_start();
include 'config/koneksi.php';

$username = mysqli_real_escape_string(
    $conn,
    $_POST['username']
);

$password = $_POST['password'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM dokter
    WHERE username='$username'"
);

if(mysqli_num_rows($query) > 0){

    $data = mysqli_fetch_assoc($query);

    if(password_verify(
        $password,
        $data['password']
    )){

        $_SESSION['id_dokter'] =
        $data['id'];

        $_SESSION['nama_dokter'] =
        $data['nama_dokter'];

        header("Location: dashboard_dokter.php");
        exit;

    }else{

        echo "
        <script>
        alert('Password salah');
        window.location='login_dokter.php';
        </script>";
    }

}else{

    echo "
    <script>
    alert('Username tidak ditemukan');
    window.location='login_dokter.php';
    </script>";
}
?>