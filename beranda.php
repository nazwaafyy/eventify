<?php
session_start();
include "config/koneksi.php";

/* ==============================
   Ambil nama user jika login
================================ */
$namaUser = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $qUser = mysqli_query($conn, "SELECT fullname FROM users WHERE id='$user_id' LIMIT 1");
    if (mysqli_num_rows($qUser) > 0) {
        $userData = mysqli_fetch_assoc($qUser);
        $namaUser = $userData['fullname'];
        $_SESSION['fullname'] = $namaUser;
    }
}

/* ==============================
   1) Rekomendasi Event (Featured) - dari dashboard admin
================================ */
$qFeatured = mysqli_query($conn, "
    SELECT e.*
    FROM featured_events f
    JOIN events e ON f.event_id = e.id
    WHERE e.status='approved'
    ORDER BY f.position ASC
    LIMIT 3
");

$featuredEvents = [];
while ($row = mysqli_fetch_assoc($qFeatured)) {
    $featuredEvents[] = $row;
}

/* ==============================
   2) Event Populer (Popular) - dari dashboard admin
================================ */
$qPopular = mysqli_query($conn, "
    SELECT e.*
    FROM popular_events p
    JOIN events e ON p.event_id = e.id
    WHERE e.status='approved'
    ORDER BY p.position ASC
    LIMIT 3
");

$popularEvents = [];
while ($row = mysqli_fetch_assoc($qPopular)) {
    $popularEvents[] = $row;
}

/* ==============================
   3) Testimoni - dari dashboard admin
================================ */
$qTesti = mysqli_query($conn, "
    SELECT * FROM testimonials 
    ORDER BY id DESC 
    LIMIT 3
");

$testiData = [];
while ($row = mysqli_fetch_assoc($qTesti)) {
    $testiData[] = $row;
}

/* ==============================
   4) FAQ - dari dashboard admin
================================ */
$qFaq = mysqli_query($conn, "
    SELECT * FROM faqs 
    ORDER BY id DESC 
    LIMIT 10
");

$faqData = [];
while ($row = mysqli_fetch_assoc($qFaq)) {
    $faqData[] = $row;
}
?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Eventify</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <?php include "components/navbar.php"; ?>
    <section class="hero-search">
        <div class="hero-container">

            <p class="welcome-text">
                <?php if ($namaUser != ""): ?>
                <p class="welcome-text">Selamat datang, <span><?= htmlspecialchars($namaUser); ?></span> 👋</p>
            <?php else: ?>
                <p class="welcome-text">Selamat datang di Eventify 👋</p>
            <?php endif; ?>

            </p>

            <h1 class="hero-title">Mau ikut event apa hari ini?</h1>



            <!-- Search bar -->
            <form class="search-box" method="GET" action="jelajahi.php">
                <span class="search-icon">🔍</span>

                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    placeholder="Cari event, organisasi, atau kampus..." />

                <button type="submit" class="filter-btn">🔎</button>
            </form>

            <!-- Kategori -->
            <div class="category-row" method="GET" action="jelajahi.php">
                <a href="jelajahi.php#semua" class="cat-chip active">Semua</a>
                <a href="jelajahi.php#pendidikan" class="cat-chip">📚 Pendidikan</a>
                <a href="jelajahi.php#musik" class="cat-chip">🎤 Musik</a>
                <a href="jelajahi.php#seni" class="cat-chip">🎨 Seni</a>
                <a href="jelajahi.php#teknologi" class="cat-chip">💻 Teknologi</a>
                <a href="jelajahi.php#volunteer" class="cat-chip">🤝 Volunteer</a>
                <a href="jelajahi.php#kompetisi" class="cat-chip">🏆 Kompetisi</a>
                <a href="jelajahi.php#olahraga" class="cat-chip">⚽ Olahraga</a>
            </div>

        </div>
    </section>

    <main class="main">
        <div class="container">

            <h1 class="page-title">Rekomendasi Event</h1>
            <p class="page-subtitle">Temukan event kampus menarik dan ikuti sekarang.</p>

            <div class="event-grid">

                <?php if (count($featuredEvents) > 0): ?>
                    <?php foreach ($featuredEvents as $event): ?>

                        <a href="detail_event.php?id=<?= $event['id']; ?>" class="event-card">
                            <div class="event-image">
                                <span class="badge-category"><?= $event['category'] ?></span>
                                <img src="uploads/<?= htmlspecialchars($event['poster']); ?>"
                                    alt="<?= htmlspecialchars($event['title']); ?>">
                            </div>

                            <div class="event-content">
                                <h3 class="event-title"><?= $event['title']; ?></h3>
                                <p class="event-meta">
                                    <?= date("l, d F Y", strtotime($event['date_event'])); ?> | <?= $event['location']; ?>
                                </p>

                                <p class="event-price">
                                    <?= ($event['price'] == 0) ? "GRATIS" : "Rp " . number_format($event['price'], 0, ',', '.'); ?>
                                </p>

                                <div class="event-org"><?= $event['cp_name']; ?></div>
                            </div>
                        </a>

                    <?php endforeach; ?>


                <?php else: ?>
                    <p style="text-align:center; margin-top:20px;">
                        Belum ada event yang tersedia 😢
                    </p>
                <?php endif; ?>

            </div>

            </br>
            </br>

            <h1 class="page-title">Event Populer Minggu Ini</h1>
            <p class="page-subtitle">Event yang paling banyak diminati minggu ini.</p>


            <div class="event-grid">

                <?php if (count($popularEvents) > 0): ?>
                    <?php foreach ($popularEvents as $event): ?>
                        <a href="detail_event.php?id=<?= $event['id']; ?>" class="event-card">
                            <div class="event-image">
                                <span class="badge-category"><?= htmlspecialchars($event['category']); ?></span>
                                <img src="uploads/<?= htmlspecialchars($event['poster']); ?>"
                                    alt="<?= htmlspecialchars($event['title']); ?>">
                            </div>

                            <div class="event-content">
                                <h3 class="event-title"><?= htmlspecialchars($event['title']); ?></h3>
                                <p class="event-meta">
                                    <?= date("l, d F Y", strtotime($event['date_event'])); ?> |
                                    <?= htmlspecialchars($event['location']); ?>
                                </p>

                                <p class="event-price">
                                    <?= ($event['price'] == 0) ? "GRATIS" : "Rp " . number_format($event['price'], 0, ',', '.'); ?>
                                </p>

                                <div class="event-org"><?= htmlspecialchars($event['cp_name']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; margin-top:20px;">Belum ada event populer 😢</p>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- CTA SECTION -->
    <section class="cta-section-full">
        <div class="cta-box-full">
            <h2 class="cta-title-full">Tertarik Daftarkan Eventmu?</h2>
            <p class="cta-desc-full">
                Ayo segera daftarkan event kampus kamu di Eventify dan rasakan kemudahan mengelola pendaftaran
                peserta dengan cepat, aman, dan transparan.
            </p>
            <a href="<?= isset($_SESSION['user_id']) ? 'daftarkan_event.php' : 'login.php?redirect=daftarkan_event.php'; ?>"
                class="cta-button-full">
                📌 Daftarkan Eventmu Sekarang
            </a>

        </div>
    </section>

    <section class="testimoni-section">
        <div class="container">

            <!-- Title -->
            <h2 class="testimoni-title">Yang mereka katakan tentang Eventify</h2>

            <!-- Grid -->
            <div class="testimoni-grid">

                <?php if (count($testiData) > 0): ?>
                    <?php foreach ($testiData as $t): ?>
                        <div class="testimoni-card">
                            <h3>"Testimoni"</h3>
                            <p><?= htmlspecialchars($t['message']); ?></p>

                            <div class="testimoni-user">
                                <img src="uploads/testimoni/<?= htmlspecialchars($t['photo']); ?>" alt="user">
                                <div>
                                    <strong><?= htmlspecialchars($t['name']); ?></strong>
                                    <span><?= htmlspecialchars($t['role']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; font-weight:700;">Belum ada testimoni 😢</p>
                <?php endif; ?>

            </div>


        </div>
        </div>
    </section>


    <section class="faq2-section">
        <div class="faq2-container">

            <h2 class="faq2-title">Yang Sering Ditanyakan</h2>

            <div class="faq2-wrapper">

                <?php if (count($faqData) > 0): ?>
                    <?php foreach ($faqData as $index => $f): ?>
                        <div class="faq2-item <?= ($index == 0) ? "active" : "" ?>">
                            <button class="faq2-question">
                                <?= htmlspecialchars($f['question']); ?>
                                <span class="faq2-icon"><?= ($index == 0) ? "&#9650;" : "&#9660;" ?></span>
                            </button>

                            <div class="faq2-answer">
                                <p><?= nl2br(htmlspecialchars($f['answer'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; font-weight:700;">Belum ada FAQ 😢</p>
                <?php endif; ?>

            </div>

        </div>
    </section>



    <script>
        document.querySelectorAll(".faq2-question").forEach(btn => {
            btn.addEventListener("click", () => {
                const item = btn.parentElement;

                // toggle buka/tutup hanya item itu saja
                item.classList.toggle("active");

                // ubah icon sesuai kondisi item yang diklik
                const icon = item.querySelector(".faq2-icon");
                if (icon) {
                    icon.innerHTML = item.classList.contains("active") ? "&#9650;" : "&#9660;";
                }
            });
        });
    </script>



    <?php include "components/footer.php"; ?>

</body>

</html>