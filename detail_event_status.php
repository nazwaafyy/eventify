<?php
session_start();
include "config/koneksi.php";

// wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Event tidak valid.");
}

$organizer_id = $_SESSION['user_id'];

// hanya event milik organizer ini
$query = "SELECT * FROM events WHERE id=$id AND organizer_id=$organizer_id LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Event tidak ditemukan / bukan milik kamu.");
}

$event = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']); ?> - Status Event</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .detail-wrapper {
            max-width: 900px;
            margin: auto;
            padding: 40px 18px;
        }

        .event-banner {
            width: 100%;
            max-width: 520px;
            margin: 0 auto 24px;
            border-radius: 18px;
            overflow: hidden;
            aspect-ratio: 9/16;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
        }

        .event-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-title {
            font-size: 34px;
            font-weight: 900;
            margin-bottom: 14px;
            text-align: center;
        }

        .meta-box {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-bottom: 18px;
        }

        .meta-box span {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #475569;
        }

        .status-box {
            margin: 18px auto 28px;
            max-width: 600px;
            padding: 14px 16px;
            border-radius: 14px;
            text-align: center;
            font-weight: 900;
            font-size: 15px;
        }

        .pending {
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #c2410c;
        }

        .approved {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }

        .desc-box {
            background: #fafafa;
            border: 1px solid #e5e7eb;
            padding: 18px;
            border-radius: 14px;
            line-height: 1.9;
            font-size: 15px;
            color: #334155;
        }
    </style>
</head>

<body>

    <?php include "components/navbar.php"; ?>

    <div class="detail-wrapper">

        <div class="event-banner">
            <img src="uploads/<?= htmlspecialchars($event['poster']); ?>"
                alt="<?= htmlspecialchars($event['title']); ?>">
        </div>

        <h1 class="detail-title"><?= htmlspecialchars($event['title']); ?></h1>

        <div class="meta-box">
            <span>📅 <?= date("l, d F Y", strtotime($event['date_event'])); ?></span>
            <span>📍 <?= htmlspecialchars($event['location']); ?></span>
            <span>🏷 <?= htmlspecialchars($event['category']); ?></span>
        </div>

        <?php if ($event['status'] == "pending"): ?>
            <div class="status-box pending">⏳ Event kamu masih menunggu persetujuan admin</div>
        <?php else: ?>
            <div class="status-box approved">✅ Event kamu sudah disetujui dan tayang di beranda</div>
        <?php endif; ?>

        <h2 style="font-weight:900;margin-bottom:12px;">📌 Deskripsi Event</h2>
        <div class="desc-box"><?= nl2br(htmlspecialchars($event['description'])); ?></div>

        <?php if ($event['status'] == "pending"): ?>
            <div style="margin-top:28px;text-align:center;">
                <a href="cancel_event.php?id=<?= $event['id']; ?>"
                    onclick="return confirm('Yakin mau membatalkan pengajuan event ini?')" style="display:inline-block;padding:14px 18px;border-radius:14px;
                  background:#ef4444;color:white;font-weight:900;text-decoration:none;">
                    ❌ Batalkan Pengajuan Event
                </a>
            </div>
        <?php endif; ?>


    </div>

    <?php include "components/footer.php"; ?>

</body>

</html>