<?php

header('Content-Type: text/plain; charset=utf-8');

class Barang
{
    public $id;
    public $nama;
    public $tanggalMasuk;
    public $jumlah;
    public $harga;

    public function __construct($id, $nama, $tanggalMasuk, $jumlah, $harga)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->tanggalMasuk = $tanggalMasuk;
        $this->jumlah = $jumlah;
        $this->harga = $harga;
    }
}

function jualBarang($queueBarang, $jumlah)
{
    $barangTerjual = [];
    $totalHarga = 0;

    while (!$queueBarang->isEmpty() && $jumlah > 0) {
        $barang = $queueBarang->dequeue();

        $jumlahTerjual = min($jumlah, $barang->jumlah);

        $barangTerjual[] = [
            "id" => $barang->id,
            "nama" => $barang->nama,
            "jumlah" => $jumlahTerjual,
            "harga" => $barang->harga,
            "total_harga" => $jumlahTerjual * $barang->harga,
        ];

        $totalHarga += $jumlahTerjual * $barang->harga;

        $barang->jumlah -= $jumlahTerjual;

        // Tambahkan kembali barang ke queue jika stok tersisa
        if ($barang->jumlah > 0) {
            $queueBarang->unshift($barang);
        }

        $jumlah -= $jumlahTerjual;
    }

    return [
        "barang_terjual" => $barangTerjual,
        "total_harga" => $totalHarga,
    ];
}

$queueBarang = new SplQueue();

// Simulasi data barang
$barang1 = new Barang(1, "Biskuit", new DateTime("2024-03-01"), 10, 10000);
$barang2 = new Barang(1, "Biskuit", new DateTime("2024-03-10"), 15, 12000);

$queueBarang->enqueue($barang1);
$queueBarang->enqueue($barang2);

$penjualan = jualBarang($queueBarang, 8);

echo "Barang Terjual:\n";
print_r($penjualan);

echo "\n";

echo "Sisa Stok Barang:\n";
for ($queueBarang->rewind(); $queueBarang->valid(); $queueBarang->next()) {
    print_r($queueBarang->current());
}

//===

$penjualan = jualBarang($queueBarang, 3);

echo "Barang Terjual:\n";
print_r($penjualan);

echo "\n";

echo "Sisa Stok Barang:\n";
for ($queueBarang->rewind(); $queueBarang->valid(); $queueBarang->next()) {
    print_r($queueBarang->current());
}

