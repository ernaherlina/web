<?php
session_start();

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

<title>Profil Pasien</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family: 'Poppins', sans-serif;

    background:
    linear-gradient(
    135deg,
    #eff6ff,
    #f8fafc,
    #ecfeff
    );
}

</style>

</head>

<body class="min-h-screen flex justify-center items-center p-6">

<div class="w-full max-w-2xl bg-white rounded-[35px] shadow-xl p-8">

    <!-- TITLE -->
    <div class="text-center mb-8">

        <h1 class="text-4xl font-bold text-blue-600">
            Profil Pasien
        </h1>

        <p class="text-slate-500 mt-2">
            Informasi data diri pasien Klinik BTH
        </p>

    </div>

    <!-- FOTO -->
    <div class="flex justify-center mb-8">

        <div class="w-28 h-28 rounded-full
        bg-blue-500 text-white
        flex items-center justify-center
        text-5xl font-bold shadow-lg">

            <?php
            echo strtoupper(substr($_SESSION['nama'],0,1));
            ?>

        </div>

    </div>

    <!-- FORM -->
    <form class="space-y-5">

        <!-- NAMA -->
        <div>

            <label class="font-medium text-slate-700">
                Nama Lengkap
            </label>

            <input
            type="text"
            value="<?php echo $_SESSION['nama']; ?>"

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4 bg-slate-100">

        </div>

        <!-- NIM -->
        <div>

            <label class="font-medium text-slate-700">
                NIM / NIP
            </label>

            <input
            type="text"
            value="<?php echo $_SESSION['nim_nip']; ?>"

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4 bg-slate-100">

        </div>

        <!-- STATUS -->
        <div>

            <label class="font-medium text-slate-700">
                Status
            </label>

            <input
            type="text"
            value="Pasien Klinik"

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4 bg-slate-100">

        </div>

        <!-- BUTTON -->
        <a href="dashboard.php"

        class="block text-center
        bg-gradient-to-r
        from-blue-500 to-blue-700
        hover:scale-[1.01]
        transition
        text-white font-semibold
        py-4 rounded-2xl shadow-lg">

            Kembali ke Dashboard

        </a>

    </form>

</div>

</body>
</html>