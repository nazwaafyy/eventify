<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ===========================
   HAPUS TESTIMONI
=========================== */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $cek = mysqli_query($conn, "SELECT photo FROM testimonials WHERE id=$id LIMIT 1");
    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        $foto = $data['photo'];

        // hapus file foto
        $path = "../uploads/testimoni/" . $foto;
        if($foto && file_exists($path)){
            unlink($path);
        }

        mysqli_query($conn, "DELETE FROM testimonials WHERE id=$id");
    }

    header("Location: dashboard.php");
    exit;
}

/* ===========================
   TAMBAH TESTIMONI
=========================== */
if(isset($_POST['name'], $_POST['role'], $_POST['message'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // upload foto
    $photoName = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = time() . "_" . uniqid() . "." . $ext;

        $uploadPath = "../uploads/testimoni/" . $photoName;

        if(!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)){
            header("Location: dashboard.php?msg=upload_error");
            exit;
        }
    } else {
        header("Location: dashboard.php?msg=no_photo");
        exit;
    }

    mysqli_query($conn, "INSERT INTO testimonials(name, role, message, photo) 
                         VALUES('$name','$role','$message','$photoName')");

    header("Location: dashboard.php");
    exit;
}

header("Location: dashboard.php");
exit;
