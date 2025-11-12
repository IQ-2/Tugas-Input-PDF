<?php
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
    $nama_file_db = null;

    if (isset($_FILES['pdf_post']) && $_FILES['pdf_post']['error'] === UPLOAD_ERR_OK) {

        $file_tmp_path = $_FILES['pdf_post']['tmp_name'];
        $file_name = $_FILES['pdf_post']['name'];
        $file_size = $_FILES['pdf_post']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['pdf'];

        if (in_array($file_ext, $allowed_exts)) {

            if ($file_size <= 10485760) {

                $upload_dir = 'pdf/';
                $nama_file_db = 'iqbal_' . time() . "_" . basename($file_name);
                $dest_path = $upload_dir . $nama_file_db;

                if (!move_uploaded_file($file_tmp_path, $dest_path)) {
                    $errors[] = "Gagal memindahkan file ke folder uploads.";
                    $nama_file_db = null;
                }

            } else {
                $errors[] = "Ukuran file terlalu besar. Maksimal 10MB.";
            }
        } else {
            $errors[] = "Tipe file tidak diizinkan. Hanya PDF.";
        }

    } else {
        switch ($_FILES['pdf_post']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $errors[] = "Ukuran file melebihi batas yang diizinkan oleh server.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = "Ukuran file melebihi batas yang diizinkan oleh form.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errors[] = "File PDF wajib diunggah ☺️☺️.";
                break;
            default:
                $errors[] = "Terjadi kesalahan yang tidak diketahui saat mengunggah file.";
        }
    }

    if (empty($errors)) {

        $sql = "INSERT INTO pdf (path, nama) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);

        mysqli_stmt_bind_param($stmt, "ss", $dest_path, $file_name);

        if (mysqli_stmt_execute($stmt)) {
            echo '<h1 style="text-align:center; font-size:40px; margin-top:200px;">File Berhasil Di Upload 😁😁😁😁😁😁😁😁😁😁</h1>';
            echo '<div style="text-align:center;">';
            echo "<a href='form-tambah.html'>&laquo Kembali ke formulir</a>";
            echo '</div>';
            exit();
        } else {
            echo "Error saat menyimpan ke database: " . mysqli_error($koneksi);
        }

    } else {
            echo "<h1>Terjadi Kesalahan</h1>";
            echo "<ul style='color:red;'>";
            foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<a href='form-tambah.html'>&laquo; Kembali ke formulir</a>";
    }
} else {
    header("Location: form-tambah.html");
    exit();
}

?>