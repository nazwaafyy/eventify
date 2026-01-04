<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ===========================
   HAPUS FEATURED
=========================== */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM featured_events WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}

/* ===========================
   TAMBAH FEATURED (maks 3)
=========================== */
if(isset($_POST['event_id'], $_POST['position'])){
    $event_id = intval($_POST['event_id']);
    $position = intval($_POST['position']);

    // cek jumlah featured (maks 3)
    $cek = mysqli_query($conn, "SELECT COUNT(*) as total FROM featured_events");
    $total = mysqli_fetch_assoc($cek)['total'];

    if($total >= 3){
        header("Location: dashboard.php?msg=featured_full");
        exit;
    }

    // insert
    mysqli_query($conn, "INSERT INTO featured_events(event_id, position) VALUES($event_id, $position)");

    header("Location: dashboard.php");
    exit;
}

header("Location: dashboard.php");
exit;
