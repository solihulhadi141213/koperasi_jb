<?php
ini_set('display_errors', 0);
error_reporting(0);
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require "../../_Config/Connection.php"; // Koneksi database
require "../../vendor/autoload.php"; // Autoload composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_FILES['file_barang'])) {
        echo '<div class="alert alert-danger">File tidak ditemukan.</div>';
        exit;
    }

    $file = $_FILES['file_barang'];
    $allowedTypes = ['xls', 'xlsx'];
    $maxSize = 10 * 1024 * 1024; // 10 MB

    // Validasi ekstensi file
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, $allowedTypes)) {
        echo '<div class="alert alert-danger">Format file tidak valid. Hanya diperbolehkan file Excel (.xls, .xlsx).</div>';
        exit;
    }

    // Validasi ukuran file
    if ($file['size'] > $maxSize) {
        echo '<div class="alert alert-danger">Ukuran file terlalu besar. Maksimal 10 MB.</div>';
        exit;
    }

    $filePath = $file['tmp_name'];
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (count($rows) <= 1) {
        echo '<div class="alert alert-danger">File Excel kosong atau tidak sesuai format.</div>';
        exit;
    }

    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Satuan</th><th>Stock</th><th>Harga Beli</th><th>Status</th></tr></thead>';
    echo '<tbody>';

    foreach ($rows as $index => $row) {
        if ($index == 0) continue; // Lewati baris pertama (judul kolom)

        $kode_barang = trim($row[1] ?? '');
        $nama_barang = trim($row[2] ?? '');
        $kategori_barang = trim($row[3] ?? '');
        $stok_barang = is_numeric($row[4]) ? intval($row[4]) : 0;
        $satuan_barang = trim($row[5] ?? '');
        $harga_beli = is_numeric($row[6]) ? intval($row[6]) : 0;

        // Validasi kelengkapan data
        if (empty($kode_barang) || empty($nama_barang) || empty($kategori_barang) || empty($satuan_barang)) {
            echo '<tr class="table-warning"><td colspan="8">Data pada baris '.($index+1).' tidak valid: Kode Barang, Nama Barang, Kategori, dan Satuan wajib diisi.</td></tr>';
            continue;
        }

        // Cek apakah kode barang sudah ada di database
        $stmt = $Conn->prepare("SELECT id_barang FROM barang WHERE kode_barang = ?");
        $stmt->bind_param("s", $kode_barang);
        $stmt->execute();
        $stmt->bind_result($id_barang);
        $stmt->fetch();
        $stmt->close();

        if ($id_barang) {
            // DATA SUDAH ADA - LAKUKAN UPDATE
            $stmt = $Conn->prepare("UPDATE barang SET 
                                  nama_barang = ?, 
                                  kategori_barang = ?, 
                                  satuan_barang = ?, 
                                  stok_barang = ?, 
                                  harga_beli = ? 
                                  WHERE id_barang = ?");
            $stmt->bind_param("sssiii", $nama_barang, $kategori_barang, $satuan_barang, $stok_barang, $harga_beli, $id_barang);
            
            if ($stmt->execute()) {
                // UPDATE HARGA MULTI KATEGORI
                for ($i = 7; $i < count($row); $i++) {
                    $kategori_harga = trim($sheet->getCellByColumnAndRow($i + 1, 1)->getValue());
                    $harga = is_numeric($row[$i]) ? intval($row[$i]) : 0;

                    if (!empty($kategori_harga) && $harga > 0) {
                        // Cari id_barang_kategori_harga
                        $stmt2 = $Conn->prepare("SELECT id_barang_kategori_harga FROM barang_kategori_harga WHERE kategori_harga = ?");
                        $stmt2->bind_param("s", $kategori_harga);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        $row2 = $result2->fetch_assoc();
                        $stmt2->close();

                        if ($row2) {
                            $id_barang_kategori_harga = $row2['id_barang_kategori_harga'];
                            
                            // Cek apakah harga untuk kategori ini sudah ada
                            $stmt3 = $Conn->prepare("SELECT id_barang_harga FROM barang_harga 
                                                   WHERE id_barang = ? AND id_barang_kategori_harga = ?");
                            $stmt3->bind_param("ii", $id_barang, $id_barang_kategori_harga);
                            $stmt3->execute();
                            $result3 = $stmt3->get_result();
                            $row3 = $result3->fetch_assoc();
                            $stmt3->close();

                            if ($row3) {
                                // Update harga yang sudah ada
                                $stmt4 = $Conn->prepare("UPDATE barang_harga SET harga = ? 
                                                       WHERE id_barang = ? AND id_barang_kategori_harga = ?");
                                $stmt4->bind_param("iii", $harga, $id_barang, $id_barang_kategori_harga);
                                $stmt4->execute();
                                $stmt4->close();
                            } else {
                                // Insert harga baru
                                $stmt4 = $Conn->prepare("INSERT INTO barang_harga 
                                                       (id_barang, id_barang_kategori_harga, harga) 
                                                       VALUES (?, ?, ?)");
                                $stmt4->bind_param("iii", $id_barang, $id_barang_kategori_harga, $harga);
                                $stmt4->execute();
                                $stmt4->close();
                            }
                        }
                    }
                }

                echo '<tr class="table-info">';
                echo "<td>{$row[0]}</td><td>{$kode_barang}</td><td>{$nama_barang}</td><td>{$kategori_barang}</td>";
                echo "<td>{$satuan_barang}</td><td>{$stok_barang}</td><td>{$harga_beli}</td><td>Data berhasil diupdate</td>";
                echo '</tr>';
            } else {
                echo '<tr class="table-danger"><td colspan="8">Gagal mengupdate data pada baris '.($index+1).'</td></tr>';
            }
            $stmt->close();
        } else {
            // DATA BARU - LAKUKAN INSERT (kode asli tetap dipertahankan)
            $konversi = 1;
            $stok_minimum = 0;
            $stmt = $Conn->prepare("INSERT INTO barang (kode_barang, nama_barang, kategori_barang, satuan_barang, konversi, harga_beli, stok_barang, stok_minimum) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiiii", $kode_barang, $nama_barang, $kategori_barang, $satuan_barang, $konversi, $harga_beli, $stok_barang, $stok_minimum);

            if ($stmt->execute()) {
                $id_barang = $stmt->insert_id;
                $stmt->close();

                // Simpan harga multi berdasarkan kategori
                for ($i = 7; $i < count($row); $i++) {
                    $kategori_harga = trim($sheet->getCellByColumnAndRow($i + 1, 1)->getValue());
                    $harga = is_numeric($row[$i]) ? intval($row[$i]) : 0;

                    if (!empty($kategori_harga) && $harga > 0) {
                        // Cari id_barang_kategori_harga
                        $stmt2 = $Conn->prepare("SELECT id_barang_kategori_harga FROM barang_kategori_harga WHERE kategori_harga = ?");
                        $stmt2->bind_param("s", $kategori_harga);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        $row2 = $result2->fetch_assoc();
                        $stmt2->close();

                        if ($row2) {
                            $id_barang_kategori_harga = $row2['id_barang_kategori_harga'];

                            // Simpan harga ke barang_harga
                            $stmt3 = $Conn->prepare("INSERT INTO barang_harga (id_barang, id_barang_kategori_harga, harga) VALUES (?, ?, ?)");
                            $stmt3->bind_param("iii", $id_barang, $id_barang_kategori_harga, $harga);
                            $stmt3->execute();
                            $stmt3->close();
                        }
                    }
                }

                echo '<tr class="table-success">';
                echo "<td>{$row[0]}</td><td>{$kode_barang}</td><td>{$nama_barang}</td><td>{$kategori_barang}</td>";
                echo "<td>{$satuan_barang}</td><td>{$stok_barang}</td><td>{$harga_beli}</td><td>Berhasil diimport</td>";
                echo '</tr>';
            } else {
                echo '<tr class="table-danger"><td colspan="8">Gagal mengimport data pada baris '.($index+1).'</td></tr>';
            }
        }
    }

    echo '</tbody></table>';
} else {
    echo '<div class="alert alert-danger">Metode request tidak valid.</div>';
}
?>