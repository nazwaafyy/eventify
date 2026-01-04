<?php
session_start();
include "config/koneksi.php";

// wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=disimpan.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ambil event yang disimpan user
$query = "
    SELECT events.*
    FROM saved_events 
    JOIN events ON saved_events.event_id = events.id
    WHERE saved_events.user_id = '$user_id'
    ORDER BY saved_events.created_at DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Disimpan - Eventify</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include "components/navbar.php"; ?>

    <section class="jelajahi-wrapper" style="margin-top:30px;">
        <div class="jelajahi-category-head">
            <h2>Event yang Kamu Simpan</h2>
            <p>Event favorit kamu yang bisa dibuka kapan saja ⭐</p>
        </div>

        <div class="jelajahi-grid-4">

            <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="empty-saved">
                    <p>Belum ada event yang kamu simpan 😢</p>
                </div>
            <?php endif; ?>


            <?php while ($event = mysqli_fetch_assoc($result)): ?>
                <a href="detail_event.php?id=<?= $event['id'] ?>" class="event-card-portrait">

                    <div class="event-thumb">
                        <img src="uploads/<?= htmlspecialchars($event['poster']) ?>"
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

                        <!-- tombol hapus simpan -->
                        <a href="save_event.php?id=<?= $event['id'] ?>" class="btn-outline"
                            style="margin-top:12px; display:inline-block;">
                            ❌ Hapus dari Disimpan
                        </a>

                    </div>

                </a>
            <?php endwhile; ?>

        </div>
    </section>

    <?php include "components/footer.php"; ?>

</body>

</html>