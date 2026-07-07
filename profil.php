<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
"));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Profil Saya</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">

<div class="max-w-3xl mx-auto mt-10">

    <div class="bg-white rounded-3xl shadow p-8">

<form action="update_profil.php"
method="POST"
enctype="multipart/form-data">

<input type="hidden"
name="id"
value="<?php echo $data['id']; ?>">

<div class="flex flex-col items-center mb-8">

    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-500">

        <?php if(!empty($data['foto'])){ ?>

            <img
            src="uploads/<?php echo $data['foto']; ?>"
            class="w-full h-full object-cover">

        <?php } else { ?>

            <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white text-5xl font-bold">

                <?php echo strtoupper(substr($data['nama'],0,1)); ?>

            </div>

        <?php } ?>

    </div>

    <label class="mt-4 text-sm text-gray-600">
        Upload Foto Profil
    </label>

    <input
    type="file"
    name="foto"
    class="mt-2">

</div>

<?php if(!empty($data['foto'])){ ?>

<a href="hapus_foto.php"
class="mt-3 inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm"
onclick="return confirm('Yakin ingin menghapus foto profil?')">

    Hapus Foto

</a>

<?php } ?>

<div class="space-y-4">

    <div>

        <label class="font-semibold">
            Nama Lengkap
        </label>

        <input
        type="text"
        name="nama"
        value="<?php echo $data['nama']; ?>"
        class="w-full border rounded-xl p-3 mt-1">

    </div>

    <div>

        <label class="font-semibold">
            NIM / NIP
        </label>

        <input
        type="text"
        name="nim_nip"
        value="<?php echo $data['nim_nip']; ?>"
        class="w-full border rounded-xl p-3 mt-1">

    </div>

    <div>

        <label class="font-semibold">
            No HP
        </label>

        <input
        type="text"
        name="no_hp"
        value="<?php echo $data['no_hp']; ?>"
        class="w-full border rounded-xl p-3 mt-1">

    </div>

    <div>

        <label class="font-semibold">
            Jenis Kelamin
        </label>

        <select
        name="jenis_kelamin"
        class="w-full border rounded-xl p-3 mt-1">

            <option value="L"
            <?php if($data['jenis_kelamin']=='L') echo 'selected'; ?>>
                Laki-Laki
            </option>

            <option value="P"
            <?php if($data['jenis_kelamin']=='P') echo 'selected'; ?>>
                Perempuan
            </option>

        </select>

    </div>

</div>

<div class="mt-8 flex gap-3">

    <button
    type="submit"
    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

        Simpan Perubahan

    </button>

    <a href="dashboard.php"
    class="bg-gray-200 px-6 py-3 rounded-xl">

        Kembali

    </a>

</div>

</form>

</div>
        </div>

    </div>

</div>

</body>
</html>