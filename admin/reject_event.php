<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: kelola_event.php");
    exit;
}

$id = intval($_GET['id']);

$query = "UPDATE events SET status='rejected' WHERE id=$id";
mysqli_query($conn, $query);

header("Location: kelola_event.php");
exit;
