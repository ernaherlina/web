<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik BTH Tasikmalaya</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <style>

body{
    font-family:'Poppins', sans-serif;
    overflow-x:hidden;
}

@media only screen and (max-width:768px){

    .flex{
        flex-direction:column !important;
    }

    .grid{
        grid-template-columns:1fr !important;
    }

    img{
        max-width:100% !important;
        height:auto !important;
    }

}

</style>

</head>

<body class="bg-[#f5f5f5] overflow-x-hidden">

    <!-- NAVBAR -->
    <header class="w-full px-6 py-4">

        <div class="bg-[#ececec] rounded-2xl px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- LOGO -->
            <div class="flex items-center gap-5">

                <img src="assets/img/logo_klinik.png"
                    class="w-20 border-l pl-4">

                <img src="assets/img/logo_kemenkes.png"
                    class="w-20 border-l border-gray-300 pl-4">

            </div>

            <!-- SEARCH -->
            <div class="hidden md:flex items-center bg-[#d9d9d9] rounded-full px-5 py-3 w-[380px]">

                <i class="fa-solid fa-magnifying-glass text-blue-500 text-xl"></i>

                <input
                    type="text"
                    placeholder="Tulis yang kamu cari disini"
                    class="bg-transparent outline-none ml-4 text-sm w-full">

            </div>

            <!-- MENU -->
            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 font-semibold">

                <!-- LOGIN -->
                <a href="login.php"
                    class="text-blue-600 hover:text-blue-800 transition">
                    Login

                </a>

                <!-- REGISTRASI -->
                <a href="registrasi.php"
                    class="hover:text-blue-600 transition">
                    Registrasi

                </a>


                
                <a href="login_dokter.php"
                class="text-blue-600 font-semibold hover:text-blue-800 transition">
                Login Dokter
            </a>

            </div>

        </div>

    </header>

    <!-- HERO -->
    <section class="px-8 md:px-16 pt-10 pb-16">

        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-10">

            <!-- TEXT -->
            <div>

                <h1 class="text-2xl md:text-4xl font-extrabold leading-tight text-slate-900">

                    Sistem Pendaftaran Online

                    <span class="text-blue-600 block">
                        Klinik BTH
                    </span>

                    Tasikmalaya

                </h1>

                <p class="mt-6 text-xl text-slate-700">
                    Daftar berobat lebih mudah tanpa antri lama
                </p>

                <!-- TOMBOL DAFTAR -->
                <a href="registrasi.php"
                    class="mt-10 inline-block bg-[#31d84b] hover:bg-[#23c03c] text-black font-semibold text-1xl px-14 py-4 rounded-full shadow-lg transition duration-300 hover:scale-105">

                    Daftar

                </a>

            </div>

            <!-- IMAGE -->
            <div class="flex justify-center">

                <img src="assets/img/gambar_utama1.png"
                    class="w-full max-w-xl">

            </div>

        </div>

    </section>

    <!-- LAYANAN -->
     <section class="px-6 md:px-16 -mt-24 pb-10">

    <h2 class="text-center text-2xl md:text-2xl font-extrabold mb-8">
        Layanan

        <span class="text-blue-500">
            Klinik
        </span>

    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- PEMERIKSAAN -->
    <a href="registrasi.php"
    class="bg-[#f3f1f1] rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300 p-10 flex flex-col items-center justify-center text-center">

        <img src="assets/img/simbol_teleskop.png"
        class="w-10 mb-3">

        <h2 class="text-1xl font-bold text-black">

            Pemeriksaan Umum

        </h2>

    </a>

    <!-- JADWAL -->
    <a href="jadwal_dokter.php"
    class="bg-[#f3f1f1] rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300 p-10 flex flex-col items-center justify-center text-center">

        <img src="assets/img/gambar_dokter.png"
        class="w-10 mb-3">

        <h2 class="text-1xl font-bold text-black">

            Jadwal Dokter

        </h2>

    </a>

    <!-- LAB -->
    <a href="laboratorium.php"
    class="bg-[#f3f1f1] rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300 p-10 flex flex-col items-center justify-center text-center">

        <img src="assets/img/simbol_lab.png"
        class="w-10 mb-3">

        <h2 class="text-1xl font-bold text-black">

            Laboratorium

        </h2>

    </a>

    <!-- APOTIK -->
    <a href="apotik.php"
    class="bg-[#f3f1f1] rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300 p-10 flex flex-col items-center justify-center text-center">

        <img src="assets/img/simbol_obat.png"
        class="w-10 mb-3">

        <h2 class="text-1xl font-bold text-black">

            Apotik

        </h2>

    </a>

</div>

</section>

    <!-- FOOTER -->
    <footer class="px-4 pb-4">

        <div class="bg-[#0077B6] rounded-2xl py-5 px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">

                <!-- LOKASI -->
                <div class="flex items-center justify-center gap-3">

                    <i class="fa-solid fa-location-dot text-2xl"></i>

                    <p class="font-medium">
                        Jl. Letjend Mashudi No. 20, Tasikmalaya
                    </p>

                </div>

                <!-- TELEPON -->
                <div class="flex items-center justify-center gap-3">

                    <i class="fa-solid fa-phone text-2xl"></i>

                    <p class="font-bold">
                        (0265) 7524800
                    </p>

                </div>

                <!-- WEBSITE -->
                <div class="flex items-center justify-center gap-3">

                    <i class="fa-solid fa-globe text-2xl"></i>

                    <p class="font-medium">
                        www.klinikbth.ac.id
                    </p>

                </div>

            </div>

        </div>

    </footer>

</body>
</html>