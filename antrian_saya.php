<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];

$query = mysqli_query($conn,"
SELECT *
FROM pendaftaran
WHERE id_user='$id_user'
AND status != 'Selesai'
ORDER BY tanggal_periksa DESC, jam_periksa ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Antrian Saya</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">

<div class="max-w-5xl mx-auto mt-10 bg-white rounded-3xl shadow">

    <div class="p-6 border-b">
        <h1 class="text-3xl font-bold text-blue-600">
            Antrian Saya
        </h1>
    </div>

    <table class="w-full">

        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="p-4">Antrian</th>
                <th class="p-4">Dokter</th>
                <th class="p-4">Tanggal</th>
                <th class="p-4">Jam</th>
                <th class="p-4">Status</th>
            </tr>
        </thead>

        <tbody>

        <?php
        if(mysqli_num_rows($query)>0){

            while($data=mysqli_fetch_assoc($query)){
        ?>

        <tr class="border-b text-center">

            <td class="p-4 font-bold text-blue-600">
                <?php echo $data['nomor_antrian']; ?>
            </td>

            <td class="p-4">
                <?php echo $data['dokter']; ?>
            </td>

            <td class="p-4">
                <?php echo $data['tanggal_periksa']; ?>
            </td>

            <td class="p-4">
                <?php echo date('H:i',strtotime($data['jam_periksa'])); ?>
            </td>

            <td class="p-4">
                <?php echo $data['status']; ?>
            </td>

        </tr>

        <?php
            }
        }else{
        ?>

        <tr>
            <td colspan="5" class="p-6 text-center text-gray-500">
                Tidak ada antrian aktif
            </td>
        </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</body>
</html>