<?php

$kode_barang   = $_POST['kode_barang'];
    $nama_barang  = $_POST['nama_barang'];
    $stok  = $_POST['stok'];
    $harga_barang  = $_POST['harga_barang'];
    $id     = $_POST['id'];
    $gambar_barang = $_FILES["gambar_barang"]["name"];

// echo $gambar_barang;
$barangData = array(
    'kode_barang'  => $kode_barang,
    'nama_barang' => $nama_barang,
    'stok' => $stok,
    'harga_barang' => $harga_barang,
    'gambar_barang' => $gambar_barang,
   
);

foreach($barangData as $key => $value)
{
    echo $key . ":" .$value;
    echo "<br>";
}

?>