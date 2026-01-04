<?php
session_start();
include "../config/koneksi.php";
include "../admin/navbar_admin.php";

// cek login admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

// ambil event approved untuk dropdown
$approvedEvents = mysqli_query($conn, "SELECT id, title FROM events WHERE status='approved' ORDER BY created_at DESC");

// ambil rekomendasi (featured)
$featured = mysqli_query($conn, "
    SELECT featured_events.id as fid, events.*
    FROM featured_events
    JOIN events ON featured_events.event_id = events.id
    ORDER BY featured_events.position ASC
");

// ambil populer
$popular = mysqli_query($conn, "
    SELECT popular_events.id as pid, events.*
    FROM popular_events
    JOIN events ON popular_events.event_id = events.id
    ORDER BY popular_events.position ASC
");

// ambil testimonials
$testimonials = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC");

// ambil faqs
$faqs = mysqli_query($conn, "SELECT * FROM faqs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Eventify</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .admin-wrapper{
            max-width:1100px;
            margin:auto;
            padding:40px 18px;
        }
        .dash-title{
            font-size:28px;
            font-weight:900;
            margin-bottom:6px;
        }
        .dash-sub{
            font-weight:600;
            color:#6b7280;
            margin-bottom:25px;
        }

        .admin-section{
            background:white;
            border:1px solid #e5e7eb;
            border-radius:18px;
            padding:22px;
            margin-bottom:25px;
            box-shadow:0 4px 12px rgba(0,0,0,0.04);
        }
        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            margin-bottom:14px;
        }
        .section-head h2{
            font-size:18px;
            font-weight:900;
            margin:0;
        }
        .section-head p{
            margin:0;
            font-weight:600;
            color:#6b7280;
            font-size:14px;
        }

        /* form */
        .form-row{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-top:14px;
        }
        select, input, textarea{
            width:100%;
            border:1px solid #e5e7eb;
            padding:12px 14px;
            border-radius:14px;
            outline:none;
            font-family:inherit;
            font-size:14px;
            font-weight:600;
        }
        textarea{ min-height:110px; resize:none; }

        .col-6{ flex:1; min-width:220px; }
        .col-12{ flex:100%; }

        .btn-admin{
            display:inline-block;
            padding:12px 14px;
            border-radius:14px;
            font-weight:900;
            text-decoration:none;
            border:2px solid #2563eb;
            background:white;
            color:#2563eb;
            cursor:pointer;
            transition:.15s;
        }
        .btn-admin:hover{
            background:#2563eb;
            color:white;
            transform:translateY(-1px);
        }
        .btn-danger{
            border:2px solid #ef4444;
            color:#ef4444;
        }
        .btn-danger:hover{
            background:#ef4444;
            color:white;
        }

        /* list card */
        .mini-grid{
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap:16px;
            margin-top:16px;
        }
        .mini-card{
            border:1px solid #e5e7eb;
            border-radius:16px;
            overflow:hidden;
            background:#fff;
            display:flex;
            flex-direction:column;
        }
        .mini-card img{
            width:100%;
            height:160px;
            object-fit:cover;
        }
        .mini-body{
            padding:14px;
            flex:1;
        }
        .mini-body h3{
            font-size:15px;
            margin:0 0 6px;
            font-weight:900;
        }
        .mini-body p{
            margin:0;
            font-size:13px;
            font-weight:600;
            color:#6b7280;
        }
        .mini-actions{
            padding:14px;
            border-top:1px solid #f1f5f9;
        }
        .mini-actions a{
            width:100%;
            text-align:center;
            display:block;
        }

        /* faq accordion */
        .faq-item{
            border:1px solid #e5e7eb;
            border-radius:14px;
            padding:14px;
            margin-top:12px;
            background:#f9fafb;
        }
        .faq-q{
            font-weight:900;
            margin-bottom:6px;
        }
        .faq-a{
            font-weight:600;
            color:#374151;
        }
    </style>
</head>

<body>
<div class="admin-wrapper">

    <h1 class="dash-title">Dashboard Admin</h1>
    <p class="dash-sub">Kelola konten yang tampil di Beranda pengguna.</p>


    <!-- ===========================
        1) REKOMENDASI EVENT
    ============================ -->
    <div class="admin-section">
        <div class="section-head">
            <div>
                <h2>Rekomendasi Event (maks 3)</h2>
                <p>Event yang tampil di bagian “Rekomendasi Event” pada Beranda.</p>
            </div>
        </div>

        <form action="proses_featured.php" method="POST">
            <div class="form-row">
                <div class="col-6">
                    <select name="event_id" required>
                        <option value="">Pilih event approved...</option>
                        <?php while($ev = mysqli_fetch_assoc($approvedEvents)): ?>
                            <option value="<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-6">
                    <input type="number" name="position" placeholder="Urutan tampil (1 - 3)" min="1" max="3" required>
                </div>
                <div class="col-12">
                    <button class="btn-admin" type="submit">+ Tambah Rekomendasi</button>
                </div>
            </div>
        </form>

        <div class="mini-grid">
            <?php while($e = mysqli_fetch_assoc($featured)): ?>
                <div class="mini-card">
                    <img src="../uploads/<?= htmlspecialchars($e['poster']) ?>" alt="">
                    <div class="mini-body">
                        <h3><?= htmlspecialchars($e['title']) ?></h3>
                        <p><?= htmlspecialchars($e['location']) ?> • <?= date("d M Y", strtotime($e['date_event'])) ?></p>
                    </div>
                    <div class="mini-actions">
                        <a class="btn-admin btn-danger"
                           href="proses_featured.php?delete=<?= $e['fid'] ?>"
                           onclick="return confirm('Hapus dari rekomendasi?')">
                           Hapus
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <!-- ===========================
        2) EVENT POPULER
    ============================ -->
    <div class="admin-section">
        <div class="section-head">
            <div>
                <h2>Event Populer Minggu Ini (maks 3)</h2>
                <p>Event yang tampil di bagian “Event Populer Minggu Ini”.</p>
            </div>
        </div>

        <form action="proses_populer.php" method="POST">
            <div class="form-row">
                <div class="col-6">
                    <select name="event_id" required>
                        <option value="">Pilih event approved...</option>
                        <?php
                        $approvedEvents2 = mysqli_query($conn, "SELECT id, title FROM events WHERE status='approved' ORDER BY created_at DESC");
                        while($ev = mysqli_fetch_assoc($approvedEvents2)): ?>
                            <option value="<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-6">
                    <input type="number" name="position" placeholder="Urutan tampil (1 - 3)" min="1" max="3" required>
                </div>
                <div class="col-12">
                    <button class="btn-admin" type="submit">+ Tambah Populer</button>
                </div>
            </div>
        </form>

        <div class="mini-grid">
            <?php while($e = mysqli_fetch_assoc($popular)): ?>
                <div class="mini-card">
                    <img src="../uploads/<?= htmlspecialchars($e['poster']) ?>" alt="">
                    <div class="mini-body">
                        <h3><?= htmlspecialchars($e['title']) ?></h3>
                        <p><?= htmlspecialchars($e['location']) ?> • <?= date("d M Y", strtotime($e['date_event'])) ?></p>
                    </div>
                    <div class="mini-actions">
                        <a class="btn-admin btn-danger"
                           href="proses_populer.php?delete=<?= $e['pid'] ?>"
                           onclick="return confirm('Hapus dari populer?')">
                           Hapus
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <!-- ===========================
        3) TESTIMONI
    ============================ -->
    <div class="admin-section">
        <div class="section-head">
            <div>
                <h2>Testimoni</h2>
                <p>Testimoni yang tampil di beranda user.</p>
            </div>
        </div>

        <form action="proses_testimoni.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="col-6">
                    <input type="text" name="name" placeholder="Nama" required>
                </div>
                <div class="col-6">
                    <input type="text" name="role" placeholder="Role / Jabatan" required>
                </div>
                <div class="col-12">
                    <textarea name="message" placeholder="Isi testimoni..." required></textarea>
                </div>
                <div class="col-12">
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <div class="col-12">
                    <button class="btn-admin" type="submit">+ Tambah Testimoni</button>
                </div>
            </div>
        </form>

        <div class="mini-grid">
            <?php while($t = mysqli_fetch_assoc($testimonials)): ?>
                <div class="mini-card">
                    <img src="../uploads/testimoni/<?= htmlspecialchars($t['photo']) ?>" alt="">
                    <div class="mini-body">
                        <h3><?= htmlspecialchars($t['name']) ?></h3>
                        <p><?= htmlspecialchars($t['role']) ?></p>
                        <p style="margin-top:8px;"><?= htmlspecialchars($t['message']) ?></p>
                    </div>
                    <div class="mini-actions">
                        <a class="btn-admin btn-danger"
                           href="proses_testimoni.php?delete=<?= $t['id'] ?>"
                           onclick="return confirm('Hapus testimoni ini?')">
                           Hapus
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <!-- ===========================
        4) FAQ
    ============================ -->
    <div class="admin-section">
        <div class="section-head">
            <div>
                <h2>FAQ</h2>
                <p>Pertanyaan dan jawaban yang tampil di beranda user.</p>
            </div>
        </div>

        <form action="proses_faq.php" method="POST">
            <div class="form-row">
                <div class="col-12">
                    <input type="text" name="question" placeholder="Pertanyaan..." required>
                </div>
                <div class="col-12">
                    <textarea name="answer" placeholder="Jawaban..." required></textarea>
                </div>
                <div class="col-12">
                    <button class="btn-admin" type="submit">+ Tambah FAQ</button>
                </div>
            </div>
        </form>

        <?php while($f = mysqli_fetch_assoc($faqs)): ?>
            <div class="faq-item">
                <div class="faq-q"><?= htmlspecialchars($f['question']) ?></div>
                <div class="faq-a"><?= nl2br(htmlspecialchars($f['answer'])) ?></div>
                <br>
                <a class="btn-admin btn-danger"
                   href="proses_faq.php?delete=<?= $f['id'] ?>"
                   onclick="return confirm('Hapus FAQ ini?')">
                   Hapus
                </a>
            </div>
        <?php endwhile; ?>

    </div>


</div>
<?php include __DIR__ . "/../components/footer.php"; ?>

</body>
</html>
