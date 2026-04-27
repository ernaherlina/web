<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Klinik BTH Tasikmalaya - Pendaftaran Online</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 py-4 shadow-sm">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="img/logo_klinik.png" alt="Logo BTH" class="h-10">
                <img src="img/logo_kemenkes.png" alt="Logo Kemenkes" class="h-10">
            </div>

            <div class="hidden md:flex flex-1 max-w-md mx-10">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Tulis yang kamu cari disini">
                </div>
            </div>

            <div class="flex items-center space-x-6 font-bold text-gray-800">
                <a href="login.html" class="text-blue-700 hover:text-blue-900">Login</a>
                <a href="registrasi.html" class="hover:text-blue-700">Registrasi</a>
            </div>
        </div>
    </header>

    <section class="container mx-auto px-6 py-12 md:py-20 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 space-y-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 leading-tight">
                Sistem Pendaftaran Online <br>
                <span class="text-blue-500">Klinik BTH</span> Tasikmalaya
            </h1>
            <p class="text-lg text-gray-600">
                Daftar berobat lebih mudah tanpa antri lama
            </p>
            <a href="registrasi.html" class="inline-block">
    <button class="bg-[#3ce65a] hover:bg-[#34ce50] text-gray-800 font-bold py-3 px-10 rounded-full text-xl shadow-lg transition duration-300">
        Daftar
    </button>
</a>
        </div>
        
        <div class="md:w-1/2 mt-12 md:mt-0 relative">
            <div class="relative z-10">
                <img src="img/gambar_utama1.png" alt="Ilustrasi" class="w-full">
            </div>
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-blue-100 rounded-full filter blur-3xl opacity-50 -z-10"></div>
        </div>
    </section>

    <section class="container mx-auto px-6 py-10 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-12">
            Layanan <span class="text-blue-500">Klinik</span>
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-gray-200 rounded-[40px] p-8 flex flex-col items-center hover:bg-gray-300 transition cursor-pointer">
                <div class="text-blue-400 text-6xl mb-4">
                    <img src="img/simbol_teleskop.png">
                </div>
                <p class="font-bold text-xl leading-tight">Pemeriksaan Umum</p>
            </div>

            <div class="bg-gray-200 rounded-[40px] p-8 flex flex-col items-center hover:bg-gray-300 transition cursor-pointer">
                <div class="text-blue-700 text-6xl mb-4">
                    <img src="img/gambar_dokter.png">
                </div>
                <p class="font-bold text-xl leading-tight">Pemeriksaan Umum</p>
            </div>

            <div class="bg-gray-200 rounded-[40px] p-8 flex flex-col items-center hover:bg-gray-300 transition cursor-pointer">
                <div class="text-gray-600 text-6xl mb-4">
                    <img src="img/simbol_lab.png">
                </div>
                <p class="font-bold text-xl leading-tight">Labolatorium</p>
            </div>

            <div class="bg-gray-200 rounded-[40px] p-8 flex flex-col items-center hover:bg-gray-300 transition cursor-pointer">
                <div class="text-blue-400 text-6xl mb-4">
                    <img src="img/simbol_obat.png">
                </div>
                <p class="font-bold text-xl leading-tight">Resep</p>
            </div>
        </div>
    </section>

    <footer class="bg-[#4196a1] py-3 px-8 mx-4 mb-4 rounded-xl flex justify-between items-center text-white font-sans">
    
    <div class="flex items-center space-x-2">
        <i class="fas fa-map-marker-alt text-lg"></i>
        <span class="text-sm md:text-base">Klinik BTH Tasikmalaya</span>
    </div>

    <div class="flex items-center space-x-2">
        <i class="fas fa-phone-alt text-lg"></i>
        <span class="text-sm md:text-base font-bold border-b-2 border-white leading-tight">
            (0265) 7524800
        </span>
    </div>

    <div class="flex items-center space-x-2">
        <i class="fas fa-globe text-lg"></i>
        <a href="https://www.klinikbth.ac.id" class="text-sm md:text-base hover:text-gray-200 transition">
            www.klinikbth.ac.id
        </a>
    </div>

</footer>

</body>
</html>