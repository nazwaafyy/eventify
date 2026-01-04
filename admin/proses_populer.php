<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ===========================
   HAPUS POPULAR
=========================== */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM popular_events WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}

/* ===========================
   TAMBAH POPULAR (maks 3)
=========================== */
if(isset($_POST['event_id'], $_POST['position'])){
    $event_id = intval($_POST['event_id']);
    $position = intval($_POST['position']);

    // cek jumlah popular (maks 3)
    $cek = mysqli_query($conn, "SELECT COUNT(*) as total FROM popular_events");
    $total = mysqli_fetch_assoc($cek)['total'];

    if($total >= 3){
        header("Location: dashboard.php?msg=popular_full");
        exit;
    }

    // cek posisi sudah dipakai atau belum
    $cekPos = mysqli_query($conn, "SELECT * FROM popular_events WHERE position=$position");
    if(mysqli_num_rows($cekPos) > 0){
        header("Location: dashboard.php?msg=pos_used");
        exit;
    }

    // insert
    mysqli_query($conn, "INSERT INTO popular_events(event_id, position) VALUES($event_id, $position)");

    header("Location: dashboard.php?msg=popular_added");
    exit;
}

header("Location: dashboard.php");
exit;
