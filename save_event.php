<?php
session_start();
include "config/koneksi.php";

// ✅ wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['HTTP_REFERER']));
    exit;
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($event_id <= 0) {
    die("ID event tidak valid.");
}

// cek apakah event sudah disimpan
$check = mysqli_query($conn, "SELECT * FROM saved_events WHERE user_id='$user_id' AND event_id='$event_id'");

if (mysqli_num_rows($check) > 0) {
    // ✅ jika sudah ada → hapus (unsave)
    mysqli_query($conn, "DELETE FROM saved_events WHERE user_id='$user_id' AND event_id='$event_id'");
} else {
    // ✅ jika belum → simpan
    mysqli_query($conn, "INSERT INTO saved_events (user_id, event_id) VALUES ('$user_id','$event_id')");
}

// balik ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
