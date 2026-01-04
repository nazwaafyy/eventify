<?php
include "config/koneksi.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telepon = trim($_POST["telepon"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm = trim($_POST["confirm"] ?? "");

    if ($nama === "" || $email === "" || $password === "" || $confirm === "") {
        $error = "Semua field wajib diisi kecuali Nomor Telepon.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {

        // cek apakah email sudah terdaftar
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Email sudah terdaftar. Silakan login.";
        } else {

            // hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // role selalu organizer
            $role = "organizer";

            // insert ke database
            $query = "INSERT INTO users (fullname, email, phone, password, role)
                      VALUES ('$nama', '$email', '$telepon', '$hashed', '$role')";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php?success=register");
                exit;
            }


        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Eventify</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 30px 12px;
        }

        .register-card {
            width: 430px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .top-bar {
            height: 6px;
            background: #007ACC;
        }

        .content {
            padding: 26px 28px 22px;
            text-align: center;
        }

        .logo-box {
            margin: 6px auto 14px;
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: #007ACC;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0, 122, 204, 0.25);
        }

        .logo-box svg {
            width: 44px;
            height: 44px;
            fill: #fff;
        }

        h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 900;
            color: #111827;
        }

        .subtitle {
            margin: 10px 0 20px;
            color: #6b7280;
            font-size: 14.5px;
            line-height: 1.5;
        }

        .form-group {
            text-align: left;
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
            color: #111827;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            outline: none;
            font-size: 14px;
            transition: 0.2s ease;
            background: #fff;
        }

        input:focus,
        select:focus {
            border-color: #007ACC;
            box-shadow: 0 0 0 3px rgba(0, 122, 204, 0.15);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #007ACC;
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.25s ease;
            box-shadow: 0 12px 30px rgba(0, 122, 204, 0.25);
            margin-top: 6px;
        }

        .btn:hover {
            background: #0063B1;
            transform: translateY(-1px);
        }

        .divider {
            margin: 18px 0 14px;
            border-top: 1px solid #e5e7eb;
        }

        .or-text {
            margin: 12px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .google-btn {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1.5px solid rgba(0, 122, 204, 0.50);
            background: #fff;
            font-size: 14.5px;
            font-weight: 800;
            color: #007ACC;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.25s ease;
        }

        .google-btn:hover {
            background: rgba(0, 122, 204, 0.08);
        }

        .bottom-text {
            margin-top: 18px;
            font-size: 14px;
            color: #111827;
        }

        .link {
            color: #007ACC;
            font-weight: 800;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .error-box {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
            margin-bottom: 14px;
        }

        .success-box {
            background: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
            margin-bottom: 14px;
        }

        @media(max-width: 480px) {
            .register-card {
                width: 92%;
            }
        }
    </style>
</head>

<body>

    <div class="register-card">
        <div class="top-bar"></div>

        <div class="content">

            <div class="logo-box">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v13a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm12 8H5v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V10ZM6 6H5a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1h-1v1a1 1 0 1 1-2 0V6H8v1a1 1 0 1 1-2 0V6Z" />
                </svg>
            </div>

            <h1>Buat Akun Eventify</h1>
            <p class="subtitle">
                Bergabunglah untuk menemukan dan membuat event kampus dengan mudah.
            </p>

            <?php if ($error): ?>
                <div class="error-box"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-box"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan email Anda" required>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon (Opsional)</label>
                    <input type="text" name="telepon" placeholder="Contoh: 08123456789">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Buat password (minimal 6 karakter)" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm" placeholder="Konfirmasi password Anda" required>
                </div>


                <button type="submit" class="btn">Daftar</button>

            </form>

            <div class="divider"></div>

            <p class="or-text">Atau daftar dengan:</p>

            <button class="google-btn" type="button">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="Google">
                Daftar dengan Google
            </button>

            <p class="bottom-text">
                Sudah punya akun? <a href="login.php" class="link">Masuk di sini</a>
            </p>

        </div>
    </div>

</body>

</html>