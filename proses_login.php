<?php
session_start();
include 'config/koneksi.php';

$nim_nip = $_POST['nim_nip'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE nim_nip='$nim_nip'");
$data = mysqli_fetch_assoc($query);

if($data){

    if(password_verify($password, $data['password'])){

        $_SESSION['id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        switch($data['role']){

            case 'superadmin':
                header("Location: dashboard_superadmin.php");
                exit;

            case 'admin':
                header("Location: dashboard_admin.php");
                exit;

            case 'dokter':
                header("Location: dashboard_dokter.php");
                exit;

            case 'apotek':
                header("Location: dashboard_apotek.php");
                exit;

            case 'user':
                header("Location: dashboard.php");
                exit;

            default:
                session_destroy();
                header("Location: login.php");
                exit;
        }

    }else{

        echo "<script>
        alert('Password salah!');
        window.location='login.php';
        </script>";

    }

}else{

    echo "<script>
    alert('NIM / NIP tidak ditemukan!');
    window.location='login.php';
    </script>";

}
?>