<?php
session_start();
include "../config/koneksi.php";
include("../admin/navbar_admin.php");

// cek login + role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ambil event pending (untuk tabel)
$queryPending = "SELECT * FROM events WHERE status='pending' ORDER BY created_at DESC";
$resultPending = mysqli_query($conn, $queryPending);

// ambil event approved (untuk card)
$queryApproved = "SELECT * FROM events WHERE status='approved' ORDER BY created_at DESC";
$resultApproved = mysqli_query($conn, $queryApproved);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event - Admin Eventify</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .admin-wrapper {
            max-width: 1100px;
            margin: auto;
            padding: 40px 18px;
        }

        /* tabel pending */
        .admin-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .admin-subtitle {
            color: #6b7280;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .table-box {
            background: white;
            border: 1px solid #eee;
            border-radius: 14px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f9fafb;
            font-weight: 900;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            /* kalau layar kecil, otomatis turun */
            align-items: center;
        }

        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 110px;
            /* semua tombol minimal sama lebar */
            padding: 10px 14px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
        }

        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 110px;
            /* semua tombol minimal sama lebar */
            padding: 10px 14px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 900;
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 5px;
        }

        /* ✅ tombol aksi versi clean: putih + border biru */
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 110px;
            padding: 10px 14px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;

            background: white;
            border: 2px solid #2563eb;
            /* biru tema */
            color: #2563eb;
            transition: 0.15s;
        }

        /* hover jadi biru solid */
        .action-buttons .btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-1px);
        }

        /* ✅ tombol hapus tetap putih tapi border merah biar bahaya tetap keliatan */
        .action-buttons .btn-delete {
            border: 2px solid #ef4444;
            color: #ef4444;
        }

        .action-buttons .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        /* ✅ tombol aksi versi clean: putih + border biru */
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 110px;
            padding: 10px 14px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;

            background: white;
            border: 2px solid #2563eb;
            /* biru tema */
            color: #2563eb;
            transition: 0.15s;
        }

        /* hover jadi biru solid */
        .action-buttons .btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-1px);
        }

        /* ✅ tombol hapus tetap putih tapi border merah biar bahaya tetap keliatan */
        .action-buttons .btn-delete {
            border: 2px solid #ef4444;
            color: #ef4444;
        }

        .action-buttons .btn-delete:hover {
            background: #ef4444;
            color: white;
        }



        .badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            display: inline-block;
            text-transform: capitalize;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .poster-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        /* ✅ tambahan tombol delete di card */
        .admin-card-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .admin-card-actions a {
            flex: 1;
            text-align: center;
            font-size: 13px;
            padding: 10px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            color: white;
        }

        .btn-delete-card {
            background: #ef4444;
        }

        /* agar tombol delete card tidak ikut kebuka detail_event */
        .event-card-portrait {
            position: relative;
        }
    </style>
</head>

<body>

    <div class="admin-wrapper">

        <!-- ✅ BAGIAN 1: Event Pending (TABEL) -->
        <h1 class="admin-title">Kelola Event</h1>
        <p class="admin-subtitle">Event Pending (menunggu persetujuan).</p>

        <div class="table-box">
            <?php if (mysqli_num_rows($resultPending) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Judul Event</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($event = mysqli_fetch_assoc($resultPending)): ?>
                            <tr>
                                <td>
                                    <img class="poster-thumb" src="../uploads/<?= htmlspecialchars($event['poster']); ?>"
                                        alt="poster">
                                </td>
                                <td>
                                    <b><?= htmlspecialchars($event['title']); ?></b><br>
                                    <small><?= htmlspecialchars($event['category']); ?></small>
                                </td>
                                <td><?= date("d M Y", strtotime($event['date_event'])); ?></td>
                                <td><?= htmlspecialchars($event['location']); ?></td>

                                <td>
                                    <span class="badge badge-pending">pending</span>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a class="btn btn-view" href="detail_event_admin.php?id=<?= $event['id']; ?>">
                                            👁 Lihat
                                        </a>

                                        <a class="btn btn-approve" href="approve_event.php?id=<?= $event['id']; ?>"
                                            onclick="return confirm('Setujui event ini?')">
                                            ✅ Setujui
                                        </a>

                                        <a class="btn btn-reject" href="reject_event.php?id=<?= $event['id']; ?>"
                                            onclick="return confirm('Tolak event ini?')">
                                            ❌ Tolak
                                        </a>

                                        <a class="btn btn-delete" href="delete_event.php?id=<?= $event['id']; ?>"
                                            onclick="return confirm('Hapus event ini?')">
                                            🗑 Hapus
                                        </a>

                                    </div>
                                </td>



                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding:20px; font-weight:700; color:#6b7280; text-align:center;">
                    Tidak ada event pending 🎉
                </div>
            <?php endif; ?>
        </div>


        <!-- ✅ BAGIAN 2: Event Approved (CARD GRID seperti USER) -->
        <section class="jelajahi-wrapper" style="margin-top:50px;">
            <div class="jelajahi-category-head">
                <h2>Event yang Sudah Disetujui</h2>
                <p>Event-event yang sudah approved dan tampil untuk pengguna ✅</p>
            </div>

            <div class="jelajahi-grid-4">
                <?php if (mysqli_num_rows($resultApproved) == 0): ?>
                    <p class="empty-event">Belum ada event approved 😢</p>
                <?php endif; ?>

                <?php while ($event = mysqli_fetch_assoc($resultApproved)): ?>
                    <div class="event-card-portrait">

                        <!-- ✅ klik card menuju detail_event (kalau kamu mau) -->
                        <a href="detail_event_admin.php?id=<?= $event['id'] ?>"
                            style="text-decoration:none; color:inherit;">
                            <div class="event-thumb">
                                <img src="../uploads/<?= htmlspecialchars($event['poster']) ?>"
                                    alt="<?= htmlspecialchars($event['title']) ?>">
                                <span class="badge-category"><?= htmlspecialchars($event['category']) ?></span>
                            </div>

                            <div class="event-info">
                                <h3><?= htmlspecialchars($event['title']) ?></h3>

                                <p><?= date("l, d F Y", strtotime($event['date_event'])) ?></p>
                                <span class="event-location"><?= htmlspecialchars($event['location']) ?></span>

                                <h4>
                                    <?= ($event['price'] == 0) ? "GRATIS" : "Rp " . number_format($event['price'], 0, ",", ".") ?>
                                </h4>
                            </div>
                        </a>

                        <!-- ✅ tombol aksi admin -->
                        <!-- <div class="admin-card-actions">
                            <a class="btn-delete-card" href="delete_event.php?id=<?= $event['id']; ?>"
                                onclick="return confirm('Yakin ingin menghapus event ini?')">
                                🗑 Hapus Event
                            </a>
                        </div> -->
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

    </div>
    <?php include __DIR__ . "/../components/footer.php"; ?>

</body>

</html>