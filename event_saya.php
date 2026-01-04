<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];

$query = "SELECT * FROM events WHERE organizer_id = $organizer_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Saya - Eventify</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .event-saya-wrapper {
            max-width: 1100px;
            margin: auto;
            padding: 50px 18px;
        }

        .page-title {
            font-size: 40px;
            font-weight: 900;
            text-align: center;
            margin-bottom: 6px;
        }

        .page-subtitle {
            text-align: center;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 35px;
        }

        .success-box {
            background: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
            padding: 12px 16px;
            border-radius: 12px;
            margin: 0 auto 24px;
            font-weight: 700;
            max-width: 760px;
            text-align: center;
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .event-card {
            border-radius: 18px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .event-card:hover {
            transform: translateY(-4px);
        }

        .poster-wrap {
            position: relative;
            height: 330px;
            background: #f3f4f6;
        }

        .poster-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-category {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(0, 122, 204, 0.92);
            color: white;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 12px;
            border-radius: 999px;
        }

        .event-content {
            padding: 16px 18px 20px;
        }

        .event-title {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .event-info {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 12px;
        }

        .event-price {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
        }

        .status-badge {
            margin-top: 12px;
            font-size: 13px;
            font-weight: 800;
            padding: 10px 12px;
            border-radius: 12px;
            text-align: center;
        }

        .status-pending {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fdba74;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .empty-box {
            max-width: 680px;
            margin: auto;
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            text-align: center;
            font-weight: 700;
            color: #475569;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>

    <?php include "components/navbar.php"; ?>

    <div class="event-saya-wrapper">

        <h1 class="page-title">Event Saya</h1>
        <p class="page-subtitle">Pantau status event yang kamu daftarkan di Eventify.</p>

        <?php if (isset($_GET['submit']) && $_GET['submit'] == "success"): ?>
            <div class="success-box">
                ✅ Event berhasil didaftarkan! Tunggu persetujuan admin ya.
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="empty-box">
                📌 Kamu belum mendaftarkan event apapun.
            </div>
        <?php else: ?>

            <?php if (isset($_GET['cancel']) && $_GET['cancel'] == "success"): ?>
                <div class="success-box" style="background:#fee2e2;border:1px solid #ef4444;color:#991b1b;">
                    ✅ Pengajuan event berhasil dibatalkan.
                </div>
            <?php endif; ?>


            <div class="event-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <a href="detail_event_status.php?id=<?= $row['id']; ?>" class="event-card">

                        <div class="poster-wrap">
                            <img src="uploads/<?= htmlspecialchars($row['poster']); ?>"
                                alt="<?= htmlspecialchars($row['title']); ?>">
                            <div class="badge-category"><?= htmlspecialchars($row['category']); ?></div>
                        </div>

                        <div class="event-content">
                            <div class="event-title"><?= htmlspecialchars($row['title']); ?></div>

                            <div class="event-info">
                                <span>📅 <?= date("l, d F Y", strtotime($row['date_event'])); ?></span>
                                <span>📍 <?= htmlspecialchars($row['location']); ?></span>
                            </div>

                            <div class="event-price">
                                <?= ($row['price'] == 0) ? "GRATIS" : "Rp " . number_format($row['price'], 0, ',', '.'); ?>
                            </div>

                            <?php if ($row['status'] == "pending"): ?>
                                <div class="status-badge status-pending">⏳ Menunggu Persetujuan Admin</div>
                            <?php else: ?>
                                <div class="status-badge status-approved">✅ Sudah Disetujui</div>
                            <?php endif; ?>

                        </div>
                    </a>

                <?php endwhile; ?>
            </div>

        <?php endif; ?>

    </div>

    <?php include "components/footer.php"; ?>
</body>

</html>