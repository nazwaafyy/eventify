<?php
include "../config/koneksi.php";

// data admin (ubah kalau mau)
$fullname = "Admin Eventify";
$email = "admin@eventify.com";
$password = "admin123"; // password login admin

// hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// cek apakah admin sudah ada
$check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");

if(mysqli_num_rows($check) > 0){
    echo "✅ Admin sudah ada <br><br>";
    echo "Silakan login dengan:<br>";
    echo "<b>Email:</b> $email <br>";
    echo "<b>Password:</b> $password <br>";
    echo "<br><a href='../login.php'>Klik untuk login</a>";
    exit;
}

// insert admin
$query = "INSERT INTO users (name, email, phone, password, role)
          VALUES ('$fullname', '$email', '', '$hashed', 'admin')";

if(mysqli_query($conn, $query)){
    echo "✅ Admin berhasil dibuat! <br><br>";
    echo "Silakan login dengan:<br>";
    echo "<b>Email:</b> $email <br>";
    echo "<b>Password:</b> $password <br>";
    echo "<br><a href='../login.php'>Klik untuk login</a>";
} else {
    echo "❌ Gagal membuat admin: " . mysqli_error($conn);
}
?>
