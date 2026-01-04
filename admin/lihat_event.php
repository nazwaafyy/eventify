<?php
session_start();
include "../config/koneksi.php";
include("../admin/navbar_admin.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$query = "SELECT * FROM events WHERE status='approved'";

if ($search !== "") {
    $safeSearch = mysqli_real_escape_string($conn, strtolower($search));
    $query .= " AND (
        LOWER(title) LIKE '%$safeSearch%' OR
        LOWER(location) LIKE '%$safeSearch%' OR
        LOWER(cp_name) LIKE '%$safeSearch%' OR
        LOWER(category) LIKE '%$safeSearch%'
    )";
}

$query .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Event - Admin Eventify</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .admin-wrapper {
            max-width: 1200px;
            margin: auto;
            padding: 50px 18px;
        }

        .admin-title {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .admin-subtitle {
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 35px;
            font-size: 15px;
        }

        /* ✅ GRID: lebih lega */
        .grid-event {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
            /* lebih besar supaya ga rapet */
        }

        /* ✅ CARD lebih rapih */
        .event-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: 0.2s;
        }

        /* Hover lebih smooth */
        .event-card:hover {
            transform: translateY(-4px);
        }

        /* ✅ Poster: ukuran proporsional */
        .event-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        /* ✅ Isi card lebih longgar */
        .event-body {
            padding: 18px 16px;
        }

        .event-title {
            font-size: 17px;
            font-weight: 900;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .event-meta {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* badge lebih manis */
        .badge-approved {
            background: #dcfce7;
            color: #166534;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            display: inline-block;
            margin-bottom: 10px;
        }

        .search-box {
            margin: 25px 0 35px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid #dbeafe;
            max-width: 520px;
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-weight: 700;
            font-size: 14px;
        }

        .search-box .filter-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 900;
        }

        .search-icon {
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="admin-wrapper">
        <h1 class="admin-title">Lihat Event</h1>
        <p class="admin-subtitle">Event-event yang sudah disetujui dan tampil ke pengguna.</p>

        <form class="search-box" method="GET" action="">
            <span class="search-icon">🔍</span>

            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                placeholder="Cari event, lokasi, kategori..." />

            <button type="submit" class="filter-btn">🔎</button>
        </form>
        <?php if (mysqli_num_rows($result) > 0): ?>
            ...
        <?php else: ?>
            <div style="padding:30px; font-weight:800; color:#6b7280; text-align:center;">
                <?php if ($search != ""): ?>
                    Tidak ada event yang cocok dengan "<b><?= htmlspecialchars($search) ?></b>" 😢
                <?php else: ?>
                    Belum ada event yang disetujui 🎉
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="grid-event">
                <?php while ($event = mysqli_fetch_assoc($result)): ?>
                    <a href="detail_event_admin.php?id=<?= $event['id']; ?>" class="event-card">
                        <img src="../uploads/<?= htmlspecialchars($event['poster']); ?>" alt="poster">
                        <div class="event-body">
                            <span class="badge-approved">approved</span>
                            <div class="event-title"><?= htmlspecialchars($event['title']); ?></div>
                            <div class="event-meta"><?= date("d M Y", strtotime($event['date_event'])); ?> •
                                <?= htmlspecialchars($event['location']); ?></div>
                            <div class="event-meta">Kategori: <?= htmlspecialchars($event['category']); ?></div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . "/../components/footer.php"; ?>

</body>

</html>