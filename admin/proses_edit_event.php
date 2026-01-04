<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

if(!isset($_POST['id'])){
    header("Location: kelola_event.php");
    exit;
}

$id = intval($_POST['id']);

$title = mysqli_real_escape_string($conn, $_POST['title']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$date_event = mysqli_real_escape_string($conn, $_POST['date_event']);
$location = mysqli_real_escape_string($conn, $_POST['location']);
$price = intval($_POST['price']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$cp_name = mysqli_real_escape_string($conn, $_POST['cp_name']);
$cp_wa = mysqli_real_escape_string($conn, $_POST['cp_wa']);
$instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
$reg_link = mysqli_real_escape_string($conn, $_POST['reg_link']);

$query = "UPDATE events SET
    title='$title',
    category='$category',
    date_event='$date_event',
    location='$location',
    price=$price,
    description='$description',
    cp_name='$cp_name',
    cp_wa='$cp_wa',
    instagram='$instagram',
    reg_link='$reg_link'
WHERE id=$id";

mysqli_query($conn, $query);

header("Location: detail_event_admin.php?id=$id&msg=updated");
exit;
