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

// update status jadi approved
$query = "UPDATE events SET status='approved' WHERE id=$id";
mysqli_query($conn, $query);

header("Location: dashboard.php");
exit;
