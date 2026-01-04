<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ===========================
   HAPUS FAQ
=========================== */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM faqs WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}

/* ===========================
   TAMBAH FAQ
=========================== */
if(isset($_POST['question'], $_POST['answer'])){
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);

    mysqli_query($conn, "INSERT INTO faqs(question, answer) VALUES('$question','$answer')");

    header("Location: dashboard.php");
    exit;
}

header("Location: dashboard.php");
exit;
