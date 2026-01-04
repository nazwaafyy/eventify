<?php
session_start();
include "../config/koneksi.php";
include("navbar_admin.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){
    header("Location: kelola_event.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM events WHERE id=$id LIMIT 1");
if(mysqli_num_rows($result) == 0){
    header("Location: kelola_event.php");
    exit;
}

$event = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Event</title>
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    .edit-wrapper{
      max-width: 850px;
      margin:auto;
      padding:40px 18px;
    }
    .edit-card{
      background:#fff;
      border:1px solid #eee;
      padding:24px;
      border-radius:18px;
      box-shadow:0 4px 12px rgba(0,0,0,0.06);
    }
    .form-group{
      margin-bottom:16px;
    }
    label{
      font-weight:800;
      display:block;
      margin-bottom:6px;
      font-size:14px;
    }
    input, textarea, select{
      width:100%;
      padding:12px 14px;
      border-radius:12px;
      border:1px solid #ddd;
      font-size:14px;
      font-weight:600;
      outline:none;
    }
    textarea{
      min-height:140px;
      resize:vertical;
    }
    .btn-row{
      margin-top:22px;
      display:flex;
      gap:12px;
    }
    .btn-row button, .btn-row a{
      flex:1;
      padding:12px;
      border-radius:14px;
      font-weight:900;
      text-align:center;
      border:2px solid #2563eb;
      background:white;
      color:#2563eb;
      cursor:pointer;
      text-decoration:none;
      transition:0.15s;
    }
    .btn-row button:hover, .btn-row a:hover{
      background:#2563eb;
      color:white;
    }
    .btn-cancel{
      border:2px solid #111827 !important;
      color:#111827 !important;
    }
    .btn-cancel:hover{
      background:#111827 !important;
      color:white !important;
    }
  </style>
</head>

<body>

<div class="edit-wrapper">
  <h1 style="font-weight:900; font-size:26px; margin-bottom:16px;">✏️ Edit Event</h1>

  <div class="edit-card">
    <form action="proses_edit_event.php" method="POST">
      
      <input type="hidden" name="id" value="<?= $event['id']; ?>">

      <div class="form-group">
        <label>Judul Event</label>
        <input type="text" name="title" value="<?= htmlspecialchars($event['title']); ?>" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="category" required>
          <?php
            $cats = ["Pendidikan","Musik","Seni","Teknologi","Volunteer","Kompetisi","Olahraga"];
            foreach($cats as $c){
              $selected = ($event['category']==$c) ? "selected" : "";
              echo "<option value='$c' $selected>$c</option>";
            }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label>Tanggal Event</label>
        <input type="date" name="date_event" value="<?= $event['date_event']; ?>" required>
      </div>

      <div class="form-group">
        <label>Lokasi</label>
        <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required>
      </div>

      <div class="form-group">
        <label>Harga (isi 0 jika gratis)</label>
        <input type="number" name="price" value="<?= $event['price']; ?>" required>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" required><?= htmlspecialchars($event['description']); ?></textarea>
      </div>

      <hr style="margin:25px 0; border:1px dashed #e5e7eb;">

      <h3 style="font-weight:900; margin-bottom:12px;">Detail Pengaju</h3>

      <div class="form-group">
        <label>CP Name</label>
        <input type="text" name="cp_name" value="<?= htmlspecialchars($event['cp_name']); ?>" required>
      </div>

      <div class="form-group">
        <label>WhatsApp</label>
        <input type="text" name="cp_wa" value="<?= htmlspecialchars($event['cp_wa']); ?>" required>
      </div>

      <div class="form-group">
        <label>Instagram</label>
        <input type="text" name="instagram" value="<?= htmlspecialchars($event['instagram']); ?>">
      </div>

      <div class="form-group">
        <label>Link Pendaftaran</label>
        <input type="text" name="reg_link" value="<?= htmlspecialchars($event['reg_link']); ?>">
      </div>

      <div class="btn-row">
        <button type="submit">💾 Simpan Perubahan</button>
        <a href="detail_event_admin.php?id=<?= $event['id']; ?>" class="btn-cancel">↩️ Batal</a>
      </div>

    </form>
  </div>
</div>

</body>
</html>
