<?php
session_start();
include "config/koneksi.php";

// =============================
// Ambil Search dari URL
// =============================
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : "";

// =============================
// Ambil Semua Event Approved (Untuk "Semua Event")
// =============================
$queryAll = "SELECT * FROM events WHERE status='approved'";

if ($search !== "") {
  $safeSearch = mysqli_real_escape_string($conn, $search);
  $queryAll .= " AND (
        LOWER(title) LIKE '%$safeSearch%' OR 
        LOWER(location) LIKE '%$safeSearch%' OR 
        LOWER(cp_name) LIKE '%$safeSearch%'
    )";
}

$namaUser = "";

if (isset($_SESSION['user_id'])) {

  $user_id = $_SESSION['user_id'];

  // ambil data user dari database
  $qUser = mysqli_query($conn, "SELECT fullname FROM users WHERE id='$user_id' LIMIT 1");

  if (mysqli_num_rows($qUser) > 0) {
    $userData = mysqli_fetch_assoc($qUser);
    $namaUser = $userData['fullname'];

    // simpan ke session biar ga query terus
    $_SESSION['fullname'] = $namaUser;
  }
}

$queryAll .= " ORDER BY date_event DESC";
$resultAll = mysqli_query($conn, $queryAll);

// =============================
// Kategori list (yang akan ditampilkan per section)
// =============================
$categories = ["Pendidikan", "Musik", "Seni", "Teknologi", "Volunteer", "Kompetisi", "Olahraga"];

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jelajahi Event - Eventify</title>
  <link rel="stylesheet" href="assets/css/style.css">
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
        <a href="#semua" class="cat-chip active">Semua</a>
        <a href="#pendidikan" class="cat-chip">📚 Pendidikan</a>
        <a href="#musik" class="cat-chip">🎤 Musik</a>
        <a href="#seni" class="cat-chip">🎨 Seni</a>
        <a href="#teknologi" class="cat-chip">💻 Teknologi</a>
        <a href="#volunteer" class="cat-chip">🤝 Volunteer</a>
        <a href="#kompetisi" class="cat-chip">🏆 Kompetisi</a>
        <a href="#olahraga" class="cat-chip">⚽ Olahraga</a>
      </div>


    </div>
  </section>

  <!-- =============================== -->
  <!-- SEMUA EVENT -->
  <!-- =============================== -->
  <section class="jelajahi-wrapper" id="semua">

    <div class="jelajahi-category-head">
      <h2>Semua Event</h2>
      <p>Event-event terbaru yang sudah disetujui admin ✅</p>
    </div>

    <div class="jelajahi-grid-4">
      <?php if (mysqli_num_rows($resultAll) == 0): ?>
        <p class="empty-event">Tidak ada event yang ditemukan 😢</p>
      <?php endif; ?>

      <?php while ($event = mysqli_fetch_assoc($resultAll)): ?>
        <a href="detail_event.php?id=<?= $event['id'] ?>" class="event-card-portrait">

          <div class="event-thumb">
            <img src="uploads/<?= htmlspecialchars($event['poster']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
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
      <?php endwhile; ?>
    </div>

  </section>


  <!-- =============================== -->
  <!-- PER KATEGORI -->
  <!-- =============================== -->
  <section class="jelajahi-wrapper">

    <?php foreach ($categories as $cat): ?>

      <?php
      $safeCat = mysqli_real_escape_string($conn, $cat);
      $queryCat = "SELECT * FROM events 
                   WHERE status='approved' 
                   AND category='$safeCat'
                   ORDER BY date_event DESC";
      $resultCat = mysqli_query($conn, $queryCat);
      ?>

      <?php
      $catId = strtolower($cat);
      ?>

      <?php if (mysqli_num_rows($resultCat) > 0): ?>
        <div class="jelajahi-category-head" id="<?= $catId ?>" style="margin-top:60px;">
          <h2><?= $cat ?></h2>
          <p>Event kategori <?= $cat ?> yang sedang populer 🔥</p>
        </div>

        <div class="jelajahi-grid-4">
          <?php while ($event = mysqli_fetch_assoc($resultCat)): ?>
            <a href="detail_event.php?id=<?= $event['id'] ?>" class="event-card-portrait">

              <div class="event-thumb">
                <img src="uploads/<?= htmlspecialchars($event['poster']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
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
          <?php endwhile; ?>
        </div>

      <?php endif; ?>

    <?php endforeach; ?>

  </section>

  <?php include "components/footer.php"; ?>

</body>

</html>