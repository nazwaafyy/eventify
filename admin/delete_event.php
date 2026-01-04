<?php
session_start();
include "../config/koneksi.php";

// cek login admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    header("Location: dashboard.php");
    exit;
}

// ambil data event dulu untuk tahu poster
$get = mysqli_query($conn, "SELECT poster FROM events WHERE id=$id LIMIT 1");
if(mysqli_num_rows($get) == 1){
    $data = mysqli_fetch_assoc($get);
    $posterFile = "../uploads/" . $data['poster'];

    // hapus file posternya kalau ada
    if(file_exists($posterFile)){
        unlink($posterFile);
    }
}

// delete event
mysqli_query($conn, "DELETE FROM events WHERE id=$id");

header("Location: dashboard.php");
exit;
