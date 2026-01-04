<?php
session_start();
include "config/koneksi.php";

// ambil id dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID event tidak valid.");
}

// query ambil event berdasarkan id
$query = "SELECT * FROM events WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Event tidak ditemukan 😢");
}

$event = mysqli_fetch_assoc($result);
// cek apakah event sudah disimpan user
$isSaved = false;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $eid = $event['id'];

    $qSaved = mysqli_query($conn, "SELECT * FROM saved_events WHERE user_id='$user_id' AND event_id='$eid'");
    $isSaved = mysqli_num_rows($qSaved) > 0;
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']); ?> - Eventify</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .detail-wrapper {
            max-width: 900px;
            margin: auto;
            padding: 40px 18px;
        }

        /* Story size banner */
        .event-banner {
            width: 100%;
            max-width: 520px;
            margin: 0 auto 26px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.10);
            background: #000;
            aspect-ratio: 9 / 16;
        }

        .event-banner img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .detail-title {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            color: #6b7280;
            font-weight: 600;
        }

        .detail-meta span {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        .detail-price {
            font-size: 28px;
            font-weight: 900;
            margin: 10px 0 25px;
            letter-spacing: -0.2px;
        }

        .detail-desc {
            font-size: 15px;
            line-height: 1.9;
            color: #374151;
            font-weight: 500;
            background: #fafafa;
            padding: 18px;
            border-radius: 14px;
            border: 1px solid #eee;
        }

        .section-title {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.2px;
            margin: 28px 0 12px;
        }

        /* social button style */
        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .social-links a {
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            background: #ede9fe;
            color: #007ACC;
            font-weight: 800;
            font-size: 14px;
        }

        .cp-box {
            margin-top: 10px;
            background: #f9fafb;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 14px;
        }

        .wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            background: #22c55e;
            color: white;
            text-decoration: none;
            font-weight: 900;
            margin-top: 8px;
        }

        .action-bar {
            margin-top: 35px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .action-bar a {
            flex: 1;
            text-align: center;
            padding: 14px 16px;
            border-radius: 14px;
            font-weight: 900;
            text-decoration: none;
        }

        .btn-register {
            background: #007ACC;
            color: white;
        }

        .btn-share {
            border: 2px solid #007ACC;
            color: #007ACC;
        }

        .btn-outline {
            border: 2px solid #6b7280;
            color: #374151;
        }
    </style>
</head>

<body>

    <?php include "components/navbar.php"; ?>

    <main class="main">
        <div class="detail-wrapper">

            <div class="event-banner">
                <img src="uploads/<?= htmlspecialchars($event['poster']); ?>"
                    alt="<?= htmlspecialchars($event['title']); ?>">
            </div>

            <h1 class="detail-title"><?= htmlspecialchars($event['title']); ?></h1>

            <div class="detail-meta">
                <span>📅 <?= date("l, d F Y", strtotime($event['date_event'])); ?></span>
                <span>📍 <?= htmlspecialchars($event['location']); ?></span>
                <span>🏷 <?= htmlspecialchars($event['category']); ?></span>
            </div>

            <div class="detail-price">
                <?= ($event['price'] == 0) ? "GRATIS" : "Rp " . number_format($event['price'], 0, ',', '.'); ?>
            </div>

            <h2 class="section-title">📌 Deskripsi Event</h2>
            <div class="detail-desc"><?= nl2br(htmlspecialchars($event['description'])); ?></div>

            <!-- ✅ INSTAGRAM PENYELENGGARA (hanya tampil kalau ada isinya) -->
            <?php if (!empty($event['instagram'])): ?>
                <h2 class="section-title">🔗 Media Sosial Penyelenggara</h2>
                <div class="social-links">
                    <a href="<?= htmlspecialchars($event['instagram']); ?>" target="_blank">📸 Instagram</a>
                </div>
            <?php endif; ?>

            <h2 class="section-title">📞 Kontak Person</h2>
            <div class="cp-box">
                <p><b>Nama:</b> <?= htmlspecialchars($event['cp_name']); ?></p>
                <p><b>WhatsApp:</b> <?= htmlspecialchars($event['cp_wa']); ?></p>

                <a class="wa-btn"
                    href="https://wa.me/<?= $event['cp_wa']; ?>?text=Halo%20kak,%20saya%20mau%20tanya%20tentang%20<?= urlencode($event['title']); ?>"
                    target="_blank">
                    💬 Chat WhatsApp
                </a>
            </div>

            <div class="action-bar">
                <a href="<?= htmlspecialchars($event['reg_link']); ?>" class="btn-register" target="_blank">Daftar
                    Sekarang</a>
                <a href="#" class="btn-share" onclick="shareEvent(event)">Bagikan Event</a>
                <a href="save_event.php?id=<?= $event['id']; ?>" class="btn-outline" id="btnSave">
                    <?= $isSaved ? "Disimpan" : "Simpan Event" ?>
                </a>

            </div>

        </div>
    </main>

    <?php include "components/footer.php"; ?>

    <script>
        function shareEvent(e) {
            e.preventDefault();
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({ title: document.title, url: url });
            } else {
                navigator.clipboard.writeText(url);
                alert("Link event berhasil disalin!");
            }
        }
    </script>

</body>

</html>