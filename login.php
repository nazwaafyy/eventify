<?php
session_start();
include "config/koneksi.php";

$error = "";
$successMsg = "";

// notif sukses setelah register
if(isset($_GET['success']) && $_GET['success'] === "register"){
    $successMsg = "✅ Akun berhasil didaftarkan! Silakan login.";
}

// jika sudah login, jangan balik ke login
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] === "admin"){
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: beranda.php");
        exit;
    }
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $remember = isset($_POST["remember"]);

    if($email === "" || $password === ""){
        $error = "Email dan password wajib diisi.";
    } else {

        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) === 1){

            $user = mysqli_fetch_assoc($result);

            if(password_verify($password, $user['password'])){

                // set session
                $_SESSION["user_id"]    = $user["id"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_name"]  = $user["fullname"];
                $_SESSION["role"]       = $user["role"];

                // remember me
                if($remember){
                    setcookie("remember_email", $email, time() + (86400 * 30), "/");
                } else {
                    setcookie("remember_email", "", time() - 3600, "/");
                }

                // redirect sesuai role
                if($user["role"] === "admin"){
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: beranda.php");
                    exit;
                }

            } else {
                $error = "Email atau password salah.";
            }

        } else {
            $error = "Email atau password salah.";
        }
    }
}

$rememberedEmail = $_COOKIE["remember_email"] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Eventify</title>

<style>
    * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
    body {
        margin: 0;
        background: #f3f4f6;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-card {
        width: 420px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .top-bar {
        height: 6px;
        background: #007ACC;
    }

    .content {
        padding: 28px 28px 20px;
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
        box-shadow: 0 8px 20px rgba(0, 122, 204, 0.25);
    }

    .logo-box svg {
        width: 44px;
        height: 44px;
        fill: #fff;
    }

    h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #111827;
    }

    .subtitle {
        margin: 10px 0 22px;
        color: #6b7280;
        font-size: 14.5px;
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

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 12px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        outline: none;
        font-size: 14px;
        transition: 0.2s ease;
    }

    input:focus {
        border-color: #007ACC;
        box-shadow: 0 0 0 3px rgba(0, 122, 204, 0.15);
    }

    .row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 10px 0 14px;
    }

    .remember {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #111827;
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
        box-shadow: 0 10px 25px rgba(0, 122, 204, 0.25);
    }

    .btn:hover {
        background: #0063B1;
        transform: translateY(-1px);
    }

    .link {
        color: #007ACC;
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
    }

    .link:hover {
        text-decoration: underline;
    }

    .divider {
        margin: 20px 0 14px;
        border-top: 1px solid #e5e7eb;
    }

    .or-text {
        margin: 14px 0;
        color: #6b7280;
        font-size: 14px;
    }

    .google-btn {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1.5px solid rgba(0, 122, 204, 0.45);
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

    /* notif merah */
    .alert-error {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #b91c1c;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 14px;
        text-align: left;
        margin-bottom: 14px;
    }

    /* notif hijau */
    .alert-success {
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
        .login-card {
            width: 92%;
        }
    }
</style>

</head>
<body>

<div class="login-card">
    <div class="top-bar"></div>

    <div class="content">
        <div class="logo-box">
            <svg viewBox="0 0 24 24">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                <path d="M12 11c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.4 0-2.5-1.1-2.5-2.5s1.1-2.5 2.5-2.5 2.5 1.1 2.5 2.5-1.1 2.5-2.5 2.5z"/>
            </svg>
        </div>

        <h1>Masuk ke Eventify</h1>
        <p class="subtitle">Kelola dan ikuti event dengan mudah.</p>

        <!-- ✅ notif hijau setelah register -->
        <?php if($successMsg !== ""): ?>
            <div class="alert-success"><?= htmlspecialchars($successMsg) ?></div>
            <script>
                window.history.replaceState({}, document.title, "login.php");
            </script>
        <?php endif; ?>

        <!-- ✅ notif merah kalau error login -->
        <?php if($error !== ""): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email Anda"
                       value="<?= htmlspecialchars($rememberedEmail) ?>" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password Anda" required>
            </div>

            <div class="row">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    Ingat Saya
                </label>
            </div>

            <button class="btn" type="submit">Masuk</button>

            <div style="margin-top: 12px;">
                <a class="link" href="forgot_password.php">Lupa Password?</a>
            </div>

            <div class="divider"></div>

            <div class="or-text">Atau masuk dengan:</div>

            <button class="google-btn" type="button" onclick="window.location.href='google_login.php'">
                Masuk dengan Google
            </button>

            <div class="bottom-text">
                Belum punya akun? <a class="link" href="register.php">Daftar Sekarang</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
