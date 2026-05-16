<?php
session_start();
include 'koneksi.php';

$nama = $_POST['nama'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE nama='$nama'");

$data = mysqli_fetch_assoc($query);

if($data){

    // cek password hash
    if(password_verify($password, $data['password'])){

        $_SESSION['nama'] = $data['nama'];

        echo "
        <script>
            alert('Login berhasil');
            window.location='dashboard.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Password salah!');
            window.location='login.php';
        </script>
        ";

    }

}else{

    echo "
    <script>
        alert('Akun tidak ditemukan!');
        window.location='login.php';
    </script>
    ";

}
?>