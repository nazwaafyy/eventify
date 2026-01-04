<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    header("Location: event_saya.php");
    exit;
}

// pastikan event milik organizer dan status masih pending
$cek = mysqli_query($conn, "SELECT * FROM events WHERE id=$id AND organizer_id=$organizer_id AND status='pending' LIMIT 1");

if(mysqli_num_rows($cek) == 0){
    header("Location: event_saya.php");
    exit;
}

// hapus event
mysqli_query($conn, "DELETE FROM events WHERE id=$id AND organizer_id=$organizer_id AND status='pending'");

header("Location: event_saya.php?cancel=success");
exit;
?>
