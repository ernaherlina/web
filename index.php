<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik BTH Tasikmalaya - Sistem Pendaftaran Online</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

<header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-6 py-4 flex flex-wrap justify-between items-center">

        <div class="flex items-center space-x-4">
            <img src="assets/img/logo_klinik.png" class="h-10 md:h-12">
            <img src="assets/img/logo_kemenkes.png" class="h-10 md:h-12 border-l pl-4 border-gray-200">
        </div>

        <div class="hidden md:flex flex-1 max-w-md mx-8">
            <div class="relative w-full">
                <input type="text" placeholder="Tulis yang kamu cari disini"
                    class="w-full bg-gray-100 rounded-full py-2 px-6">
            </div>
        </div>

        <nav>
            <!-- LOGIN (SUDAH BENAR) -->
            <a href="login.php" class="text-blue-600 font-semibold hover:text-blue-800">
                Login
            </a>
        </nav>

    </div>
</header>

<section class="container mx-auto px-6 py-12 md:py-20 flex flex-col md:flex-row items-center">

    <div class="md:w-1/2 space-y-6 text-center md:text-left">

        <h1 class="text-3xl md:text-5xl font-bold leading-tight">
            Sistem Pendaftaran Online <br>
            <span class="text-blue-600">Klinik BTH Tasikmalaya</span>
        </h1>

        <p class="text-lg text-slate-600">
            Daftar berobat lebih mudah tanpa antri lama.
        </p>

        
        <a href="pendaftaran.php"
           class="bg-green-500 hover:bg-green-600 text-white text-xl font-bold py-3 px-10 rounded-full shadow-lg transition transform hover:scale-105 inline-block">
            Daftar
        </a>

    </div>

    <div class="md:w-1/2 mt-10 md:mt-0">
        <img src="assets/img/gambar_utama1.png" class="w-full max-w-lg mx-auto">
    </div>

</section>

<!-- LAYANAN -->
<div class="py-16 text-center">
    <h2 class="text-2xl font-bold mb-10">
        Layanan <span class="text-blue-600">Klinik</span>
    </h2>

    <div class="grid grid-cols-4 gap-6 px-20">

        <div class="bg-gray-200 rounded-2xl p-6 hover:shadow-lg transition">
            <img src="assets/img/simbol_teleskop.png" class="w-16 mx-auto mb-4">
            <h3 class="font-semibold">Pemeriksaan Umum</h3>
        </div>

        <div class="bg-gray-200 rounded-2xl p-6 hover:shadow-lg transition">
            <img src="assets/img/gambar_dokter.png" class="w-16 mx-auto mb-4">
            <h3 class="font-semibold">Jadwal Dokter</h3>
        </div>

        <div class="bg-gray-200 rounded-2xl p-6 hover:shadow-lg transition">
            <img src="assets/img/simbol_lab.png" class="w-16 mx-auto mb-4">
            <h3 class="font-semibold">Laboratorium</h3>
        </div>

        <div class="bg-gray-200 rounded-2xl p-6 hover:shadow-lg transition">
            <img src="assets/img/simbol_obat.png" class="w-16 mx-auto mb-4">
            <h3 class="font-semibold">Apotek</h3>
        </div>

    </div>
</div>

<!-- FOOTER -->
<div class="bg-teal-600 text-white py-5 flex justify-around">
    <p>📍 Klinik BTH Tasikmalaya</p>
    <p>📞 (0265) 7524800</p>
    <p>🌐 www.klinikbth.ac.id</p>
</div>

</body>
</html>