<?php
date_default_timezone_set('Asia/Jakarta');
include 'config/koneksi.php';

$dokter  = $_GET['dokter'] ?? '';
$tanggal = $_GET['tanggal'] ?? '';

$slot_jam = [
    '08:00',
    '08:20',
    '08:40',
    '09:00',
    '09:20',
    '09:40',
    '10:00',
    '10:20',
    '10:40',
    '11:00',
    '11:20',
    '11:40',
    '12:00',
    '12:20',
    '12:40',
    '13:00',
    '13:20',
    '13:40'
];

// Option awal
echo '<option value="">-- Pilih Jam --</option>';

// Jika dokter atau tanggal belum dipilih
if(empty($dokter) || empty($tanggal)){
    exit;
}

$jamTerakhir = end($slot_jam);

if(
    $tanggal == date('Y-m-d')
    &&
    strtotime(date('H:i')) > strtotime($jamTerakhir)
){

    echo "
    <option value='' disabled>
        Jam praktik hari ini sudah berakhir, silakan daftar untuk besok
    </option>";

    exit;
}

// Tolak jika tanggal sudah lewat

if(strtotime($tanggal) < strtotime(date('Y-m-d'))){

    echo "
    <option value='' disabled>
        Tanggal Sudah Lewat
    </option>";

    exit;
}

// Ambil jam yang sudah dibooking
$query = mysqli_query(
    $conn,
    "SELECT jam_periksa
    FROM pendaftaran
    WHERE dokter='$dokter'
    AND tanggal_periksa='$tanggal'"
);

$jamTerpakai = [];

while($row = mysqli_fetch_assoc($query)){

    $jamTerpakai[] = substr($row['jam_periksa'], 0, 5);

}


// Tampilkan semua slot
foreach($slot_jam as $jam){

    // Jam sudah dibooking
    if(in_array($jam, $jamTerpakai)){

        echo "<option value='' disabled>
                $jam (Sudah Dibooking)
              </option>";

        continue;
    }

    // Jika tanggal yang dipilih adalah hari ini
    if($tanggal == date('Y-m-d')){

        $jamSekarang = date('H:i');

        if(strtotime($jam) <= strtotime($jamSekarang)){

            echo "<option value='' disabled>
                    $jam (Jam Terlewat)
                  </option>";

            continue;
        }
    }

    // Jam tersedia
    echo "<option value='$jam'>$jam</option>";
}