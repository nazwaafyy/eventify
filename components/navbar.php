<?php
$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($page)
{
    global $currentPage;
    return ($currentPage === $page) ? "active" : "";
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>


<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-text">Eventify</span>
        </a>

        <ul class="nav-links">
            <li><a href="beranda.php" class="<?= isActive('beranda.php'); ?>">Beranda</a></li>
            <li><a href="jelajahi.php" class="<?= isActive('jelajahi.php'); ?>">Jelajahi</a></li>

            <!-- Daftarkan Event tetap ada, tapi kalau admin login diarahkan ke dashboard -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === "admin"): ?>
                <li><a href="admin/dashboard.php">Dashboard Admin</a></li>
            <?php else: ?>
                <li>
                    <a href="<?= isset($_SESSION['user_id']) ? 'daftarkan_event.php' : 'login.php?redirect=daftarkan_event.php'; ?>"
                        class="<?= isActive('daftarkan_event.php'); ?>">
                        Daftarkan Event
                    </a>
                </li>
            <?php endif; ?>

            <li><a href="event_saya.php" class="<?= isActive('event_saya.php'); ?>">Event Saya</a></li>
            <li><a href="disimpan.php" class="<?= isActive('disimpan.php'); ?>">Disimpan</a></li>
        </ul>

        <div class="nav-actions">

            <!-- ✅ Kalau belum login -->
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-outline">Masuk</a>
                <a href="register.php" class="btn btn-solid">Daftar</a>

                <!-- ✅ Kalau sudah login -->
            <?php else: ?>
                <a href="logout.php" class="btn btn-outline" onclick="return confirmLogout(event)">Logout</a>
            <?php endif; ?>
        </div>

        <button class="nav-toggle" onclick="toggleMenu()">☰</button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
        <a href="index.php">Beranda</a>
        <a href="#">Event Saya</a>
        <a href="#">Tiket Saya</a>
        <a href="#">Riwayat</a>

        <div class="mobile-actions">
            <!-- ✅ Kalau belum login -->
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-outline">Masuk</a>
                <a href="register.php" class="btn btn-solid">Daftar</a>

                <!-- ✅ Kalau sudah login -->
            <?php else: ?>
                <a href="logout.php" class="btn btn-outline" onclick="return confirmLogout(event)">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<script>
    function confirmLogout(e) {
        e.preventDefault();

        const keluar = confirm("Yakin mau keluar dari akun kamu?");

        if (keluar) {
            window.location.href = "logout.php";
        }
    }
</script>

</script>