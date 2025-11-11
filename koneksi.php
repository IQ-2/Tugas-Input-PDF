<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_pdf";

try {
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
  //  echo "Koneksi ke database berhasil!";

} catch (mysqli_sql_exception $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>