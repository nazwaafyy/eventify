<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {

    $organizer_id = $_SESSION['user_id'];

    $title = mysqli_real_escape_string($conn, $_POST['nama_kegiatan']);
    $category = mysqli_real_escape_string($conn, $_POST['kategori']);
    $date_event = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $location = mysqli_real_escape_string($conn, $_POST['tempat']);
    $price = mysqli_real_escape_string($conn, $_POST['htm']);
    $reg_link = mysqli_real_escape_string($conn, $_POST['link_daftar']);
    $description = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $cp_name = mysqli_real_escape_string($conn, $_POST['cp_publik']);
    $cp_wa = mysqli_real_escape_string($conn, $_POST['wa_instansi']);

    // ✅ Instagram opsional
    $instagram = trim($_POST['instagram'] ?? "");
    $instagram = mysqli_real_escape_string($conn, $instagram);

    // ✅ status otomatis pending
    $status = "pending";

    // =====================
    // upload poster
    // =====================
    $folder = "uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $posterName = $_FILES['poster']['name'];
    $posterTmp = $_FILES['poster']['tmp_name'];

    $ext = strtolower(pathinfo($posterName, PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png"];

    if (!in_array($ext, $allowed)) {
        die("❌ Poster harus JPG / JPEG / PNG!");
    }

    $newPosterName = time() . "_" . uniqid() . "." . $ext;
    $pathPoster = $folder . $newPosterName;

    if (!move_uploaded_file($posterTmp, $pathPoster)) {
        die("❌ Upload poster gagal.");
    }

    // =====================
    // insert event
    // =====================
    $query = "INSERT INTO events 
(organizer_id, title, category, date_event, location, price, reg_link, description, poster, cp_name, cp_wa, instagram, status)
VALUES 
('$organizer_id', '$title', '$category', '$date_event', '$location', '$price', '$reg_link', '$description', '$pathPoster', '$cp_name', '$cp_wa', '$instagram', 'pending')";

    if (mysqli_query($conn, $query)) {
        // ✅ redirect ke event saya + notif success
        header("Location: event_saya.php?submit=success");
        exit;
    } else {
        die("❌ Error insert: " . mysqli_error($conn));
    }
}
?>