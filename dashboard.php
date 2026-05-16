<?php
session_start();

// jika belum login
if(!isset($_SESSION['nama'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Klinik</title>

<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<!-- Sidebar -->
<div class="w-64 h-screen bg-blue-700 text-white p-6">

<h1 class="text-2xl font-bold mb-8">
Klinik BTH
</h1>

<ul class="space-y-4">

<li>
<a href="#" class="block hover:bg-blue-600 p-2 rounded">
Dashboard
</a>
</li>

<li>
<a href="#" class="block hover:bg-blue-600 p-2 rounded">
Daftar Berobat
</a>
</li>

<li>
<a href="#" class="block hover:bg-blue-600 p-2 rounded">
Riwayat
</a>
</li>

<li>
<a href="logout.php"
class="block hover:bg-red-500 p-2 rounded">
Logout
</a>
</li>

</ul>
</div>

<!-- Content -->
<div class="flex-1 p-10">

<h1 class="text-3xl font-bold mb-4">
Selamat Datang,
<?php echo $_SESSION['nama']; ?> 👋
</h1>

<div class="bg-white p-6 rounded-2xl shadow">

<h2 class="text-xl font-semibold mb-2">
Dashboard Pasien
</h2>

<p>
Anda berhasil login ke sistem Klinik BTH.
</p>

</div>

</div>

</div>

</body>
</html>