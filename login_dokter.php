<?php
session_start();

if(isset($_SESSION['id_dokter'])){
    header("Location: dashboard_dokter.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Dokter</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(
        135deg,
        #eff6ff,
        #f8fafc,
        #ecfeff
    );
}
</style>

</head>

<body class="min-h-screen flex justify-center items-center">

<div class="bg-white shadow-xl rounded-3xl p-8 w-full max-w-md">

    <div class="text-center mb-6">

        <h1 class="text-3xl font-bold text-blue-600">
            Login Dokter
        </h1>

        <p class="text-slate-500 mt-2">
            Klinik BTH Tasikmalaya
        </p>

    </div>

    <form action="proses_login_dokter.php" method="POST">

        <div class="mb-4">

            <label class="block text-sm font-medium mb-2">
                Username
            </label>

            <input
            type="text"
            name="username"
            required

            class="w-full border border-slate-300 rounded-xl p-3">

        </div>

        <div class="mb-6">

            <label class="block text-sm font-medium mb-2">
                Password
            </label>

            <input
            type="password"
            name="password"
            required

            class="w-full border border-slate-300 rounded-xl p-3">

        </div>

        <button
        type="submit"

        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl">

            Login

        </button>

    </form>

</div>

</body>
</html>