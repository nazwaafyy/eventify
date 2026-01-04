<?php
session_start();
include "../config/koneksi.php";
include("../admin/navbar_admin.php");

// cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kelola_event.php");
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM events WHERE id=$id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: kelola_event.php");
    exit;
}

$event = mysqli_fetch_assoc($result);

// badge status
$status = $event['status'];
$statusLabel = ($status == "pending") ? "Pending" : (($status == "approved") ? "Approved" : "Rejected");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .detail-wrapper {
            max-width: 1100px;
            margin: auto;
            padding: 40px 18px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 25px;
            align-items: start;
        }

        .poster-box {
            background: white;
            border: 1px solid #eee;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .poster-box img {
            width: 100%;
            height: 420px;
            object-fit: cover;
        }

        .info-box {
            background: white;
            border: 1px solid #eee;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 15px;
        }

        .info-header h1 {
            font-size: 24px;
            font-weight: 900;
            margin: 0;
            line-height: 1.2;
        }

        .badge-status {
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            height: fit-content;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .meta {
            color: #6b7280;
            font-weight: 600;
            margin: 6px 0;
        }

        .price {
            font-size: 22px;
            font-weight: 900;
            margin: 12px 0;
        }

        .desc {
            margin-top: 18px;
            line-height: 1.6;
            font-weight: 600;
            color: #374151;
        }

        .divider {
            margin: 18px 0;
            border-top: 1px dashed #e5e7eb;
        }

        .detail-list {
            display: grid;
            gap: 10px;
        }

        .poster-box {
            background: #f9fafb;
            border: 1px solid #eee;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .poster-box img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 14px;
        }

        /* tombol admin jadi putih + border biru */
        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .action-row a {
            flex: 1;
            min-width: 160px;
            text-align: center;
            padding: 12px 14px;
            border-radius: 14px;
            font-weight: 900;
            text-decoration: none;
            background: white;
            border: 2px solid #2563eb;
            color: #2563eb;
            transition: 0.15s;
        }

        .action-row a:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        /* tombol hapus versi merah soft */
        .action-row .btn-delete {
            border: 2px solid #ef4444;
            color: #ef4444;
        }

        .action-row .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        /* tombol kembali versi abu */
        .action-row .btn-back {
            border: 2px solid #111827;
            color: #111827;
        }

        .action-row .btn-back:hover {
            background: #111827;
            color: white;
        }

        /* link full tampil */
        .detail-item a {
            color: #2563eb;
            font-weight: 900;
            text-decoration: none;
            word-break: break-all;
        }

        .detail-item a:hover {
            text-decoration: underline;
        }


        .detail-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            background: #f9fafb;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
        }


        @media(max-width: 900px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .poster-box img {
                height: 320px;
            }
        }
    </style>
</head>

<body>

    <div class="detail-wrapper">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == "updated"): ?>
            <div style="
     background:#dcfce7;
     padding:14px 18px;
     border-radius:14px;
     font-weight:800;
     color:#166534;
     margin-bottom:20px;
     border:1px solid #86efac;">
                ✅ Event berhasil diperbarui!
            </div>
        <?php endif; ?>

        <div class="detail-grid">

            <!-- Poster -->
            <div class="poster-box">
                <img src="../uploads/<?= htmlspecialchars($event['poster']); ?>" alt="poster">
            </div>

            <!-- Info -->
            <div class="info-box">
                <div class="info-header">
                    <h1><?= htmlspecialchars($event['title']); ?></h1>
                    <span class="badge-status <?= $event['status']; ?>">
                        <?= $statusLabel; ?>
                    </span>
                </div>

                <p class="meta">📅 <?= date("l, d F Y", strtotime($event['date_event'])); ?></p>
                <p class="meta">📍 <?= htmlspecialchars($event['location']); ?></p>
                <p class="meta">🏷️ <?= htmlspecialchars($event['category']); ?></p>

                <div class="price">
                    <?= ($event['price'] == 0) ? "GRATIS" : "Rp " . number_format($event['price'], 0, ",", "."); ?>
                </div>

                <div class="divider"></div>

                <h3 style="font-weight:900; margin-bottom:10px;">Detail Pengaju</h3>
                <div class="detail-list">
                    <div class="detail-item"><span>CP Name</span> <?= htmlspecialchars($event['cp_name']); ?></div>
                    <div class="detail-item"><span>WhatsApp</span> <?= htmlspecialchars($event['cp_wa']); ?></div>
                    <div class="detail-item">
                        <span>Instagram</span>
                        <?php if (!empty($event['instagram'])): ?>
                            <a href="<?= htmlspecialchars($event['instagram']); ?>" target="_blank">
                                <?= htmlspecialchars($event['instagram']); ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>

                    <div class="detail-item">
                        <span>Link Pendaftaran</span>
                        <?php if (!empty($event['reg_link'])): ?>
                            <a href="<?= htmlspecialchars($event['reg_link']); ?>" target="_blank">
                                <?= htmlspecialchars($event['reg_link']); ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>

                </div>

                <div class="divider"></div>

                <h3 style="font-weight:900; margin-bottom:10px;">Deskripsi</h3>
                <p class="desc"><?= nl2br(htmlspecialchars($event['description'])); ?></p>

                <!-- tombol aksi -->
                <div class="action-row">
                    <?php if ($event['status'] == "pending"): ?>
                        <a href="approve_event.php?id=<?= $event['id']; ?>" class="btn-approve"
                            onclick="return confirm('Setujui event ini?')">✅ Setujui</a>
                        <a href="reject_event.php?id=<?= $event['id']; ?>" class="btn-reject"
                            onclick="return confirm('Tolak event ini?')">❌ Tolak</a>
                    <?php endif; ?>

                    <a href="edit_event.php?id=<?= $event['id']; ?>">✏️ Edit</a>
                    <a href="delete_event.php?id=<?= $event['id']; ?>" class="btn-delete"
                        onclick="return confirm('Hapus event ini?')">🗑 Hapus</a>
                    <a href="kelola_event.php" class="btn-back">⬅ Kembali</a>
                </div>
            </div>

        </div>
    </div>

</body>

</html>