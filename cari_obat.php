<?php
require_once "config/koneksi.php";

header("Content-Type:text/html; charset=UTF-8");

$keyword = trim($_GET['keyword'] ?? '');

if(strlen($keyword) < 2){
    exit;
}

$keyword = mysqli_real_escape_string($conn,$keyword);

$query = mysqli_query($conn,"
SELECT
id,
nama_obat,
bentuk,
stok,
dosis_default
FROM obat
WHERE nama_obat LIKE '%$keyword%'
ORDER BY nama_obat ASC
LIMIT 5
");

if(mysqli_num_rows($query)==0){

echo '

<div class="text-center text-gray-500 py-6">

Tidak ada obat yang ditemukan.

</div>

';

exit;

}

while($o=mysqli_fetch_assoc($query)){

$id=$o['id'];

$nama=htmlspecialchars($o['nama_obat'] ?? '');

$bentuk=htmlspecialchars($o['bentuk'] ?? '-');

$dosis=htmlspecialchars($o['dosis_default'] ?? '');

$stok=(int)$o['stok'];

?>

<div class="flex items-center justify-between p-4 border-b hover:bg-blue-50 transition">

    <div>

        <div class="font-semibold text-lg">

            💊 <?= $nama ?>

        </div>

        <div class="text-sm text-gray-500 mt-1">

            <?= $bentuk ?>

            &nbsp;&nbsp;|&nbsp;&nbsp;

            Stok :

            <span class="<?= $stok>0 ? 'text-green-600':'text-red-600'; ?> font-semibold">

                <?= $stok ?>

            </span>

        </div>

    </div>

    <?php if($stok>0){ ?>

    <button

    type="button"

    class="tambahObat

    bg-blue-600

    hover:bg-blue-700

    text-white

    px-5

    py-2

    rounded-xl"

    data-id="<?= $id ?>"

    data-nama="<?= $nama ?>"

    data-dosis="<?= $dosis ?>">

        Tambah

    </button>

    <?php }else{ ?>

    <button

    disabled

    class="bg-gray-300 text-gray-600 px-5 py-2 rounded-xl cursor-not-allowed">

        Habis

    </button>

    <?php } ?>

</div>

<?php

}

?>