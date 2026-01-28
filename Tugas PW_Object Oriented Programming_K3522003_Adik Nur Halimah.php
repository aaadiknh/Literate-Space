<?php
// Parent class
class Sepatu {
    // Properties
    protected $nama;
    protected $ukuran;
    protected $warna;
    protected $harga;

    // Constructor
    public function __construct($nama, $ukuran, $warna, $harga) {
        $this->nama = $nama;
        $this->ukuran = $ukuran;
        $this->warna = $warna;
        $this->harga = $harga;
    }

    // Method umum untuk semua sepatu
    public function tampilkanInfo() {
        echo "Nama: " . $this->nama . "\n";
        echo "Ukuran: " . $this->ukuran . "\n";
        echo "Warna: " . $this->warna . "\n";
        echo "Harga: Rp" . $this->harga . "\n";
    }

    public function cobaSepatu() {
        echo "Mencoba sepatu " . $this->nama . " berukuran " . $this->ukuran . "...\n";
    }
}

// Child class Sneaker (turunan dari Sepatu)
class Sneaker extends Sepatu {
    private $jenisSol;

    public function __construct($nama, $ukuran, $warna, $harga, $jenisSol) {
        parent::__construct($nama, $ukuran, $warna, $harga);
        $this->jenisSol = $jenisSol;
    }

    public function tampilkanInfo() {
        parent::tampilkanInfo();
        echo "Jenis Sol: " . $this->jenisSol . "\n";
    }

    public function lari() {
        echo "Menggunakan " . $this->nama . " untuk lari!\n";
    }
}

// Child class Boot (turunan dari Sepatu)
class Boot extends Sepatu {
    private $tinggiBoot;

    public function __construct($nama, $ukuran, $warna, $harga, $tinggiBoot) {
        parent::__construct($nama, $ukuran, $warna, $harga);
        $this->tinggiBoot = $tinggiBoot;
    }

    public function tampilkanInfo() {
        parent::tampilkanInfo();
        echo "Tinggi Boot: " . $this->tinggiBoot . " cm\n";
    }

    public function hiking() {
        echo "Menggunakan " . $this->nama . " untuk hiking!\n";
    }
}


$sneaker = new Sneaker("Sneaker XYZ", 42, "Hitam", 500000, "Sol karet");
$sneaker->tampilkanInfo();
$sneaker->lari();

echo "\n";

$boot = new Boot("Boot ABC", 43, "Coklat", 700000, 20);
$boot->tampilkanInfo();
$boot->hiking();
?>
