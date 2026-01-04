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

// cek role admin (optional, tapi bagus)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}
?>

<nav class="navbar">
    <div class="nav-container">
        <a href="dashboard.php" class="logo">
            <span class="logo-icon">🎫</span>
            <span class="logo-text">Eventify</span>
        </a>

        <ul class="nav-links">
            <a href="dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">Dashboard</a>
            <a href="kelola_event.php" class="<?= ($currentPage == 'kelola_event.php') ? 'active' : '' ?>">Kelola
                Event</a>
            <a href="lihat_event.php" class="<?= ($currentPage == 'lihat_event.php') ? 'active' : '' ?>">Lihat Event</a>

        </ul>

        <div class="nav-actions">
            <a href="../logout.php" class="btn btn-outline" onclick="return confirmLogout(event)">Logout</a>
        </div>

        <button class="nav-toggle" onclick="toggleMenu()">☰</button>
    </div>

    <!-- ✅ Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="dashboard.php" class="<?= isActive('dashboard.php'); ?>">Dashboard Admin</a>
        <a href="kelola_event.php" class="<?= isActive('kelola_event.php'); ?>">Kelola Event</a>
        <a href="../jelajahi.php" class="<?= isActive('jelajahi.php'); ?>">Jelajahi</a>

        <div class="mobile-actions">
            <a href="../logout.php" class="btn btn-outline" onclick="return confirmLogout(event)">Logout</a>
        </div>
    </div>
</nav>

<script>
    function confirmLogout(e) {
        e.preventDefault();

        const keluar = confirm("Yakin mau keluar dari akun admin?");
        if (keluar) {
            window.location.href = "../logout.php";
        }
    }

    function toggleMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        mobileMenu.classList.toggle("show");
    }
</script>