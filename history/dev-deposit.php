<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
$new_query = $conn->query("SELECT * FROM deposit WHERE username = '$sess_username' ORDER BY id DESC");
if (!$new_query) {
    die("Query failed: " . $conn->error);
}

while ($data_depo = $new_query->fetch_assoc()) {
        // Menentukan label status berdasarkan nilai status
        if ($data_depo['status'] == "Pending") {
            $label = "warning";
        } else if ($data_depo['status'] == "Error") {
            $label = "danger";     
        } else if ($data_depo['status'] == "Success") {
            $label = "success";    
        }
        
        // Menampilkan informasi riwayat isi saldo
        echo "<div class='deposit-item'>";
        echo "<p>Kode Isi Saldo: " . $data_depo['kode_deposit'] . "</p>";
        echo "<p>Tanggal & Waktu: " . tanggal_indo($data_depo['date']) . ", " . $data_depo['time'] . "</p>";
        echo "<p>Tipe Pembayaran: " . $data_depo['provider'] . "</p>";
        echo "<p>Penerima: " . $data_depo['penerima'] . "</p>";
        echo "<p>Keterangan: " . $data_depo['catatan'] . "</p>";
        echo "<p>Jumlah Pembayaran: Rp " . number_format($data_depo['jumlah_transfer'], 0, ',', '.') . "</p>";
        echo "<p>Saldo Yang Di Dapatkan: Rp " . number_format($data_depo['get_saldo'], 0, ',', '.') . "</p>";
        echo "<p>Status: <span class='btn btn-" . $label . " btn-elevate btn-pill btn-elevate-air btn-sm'>" . $data_depo['status'] . "</span></p>";
        echo "<a href='" . $config['web']['url'] . "deposit-balance/invoice?kode_deposit=" . $data_depo['kode_deposit'] . "' class='btn btn-primary btn-elevate btn-circle btn-icon'><i class='flaticon-eye'></i></a>";
        echo "Loop dieksekusi!";
        echo "</div>";
    }
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

