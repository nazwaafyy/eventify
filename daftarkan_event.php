<?php
session_start();
include "config/koneksi.php";

$message = "";

if(isset($_POST['submit'])){

    // ==========================
    // WAJIB LOGIN ORGANIZER
    // ==========================
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    $organizer_id = $_SESSION['user_id'];

    // ==========================
    // Ambil data dari form
    // ==========================
    $instansi    = mysqli_real_escape_string($conn, $_POST['instansi']);
    $wa_instansi = mysqli_real_escape_string($conn, $_POST['wa_instansi']);
    $instagram   = isset($_POST['instagram']) ? mysqli_real_escape_string($conn, $_POST['instagram']) : "";

    $title       = mysqli_real_escape_string($conn, $_POST['nama_kegiatan']);
    $category    = mysqli_real_escape_string($conn, $_POST['kategori']);
    $date_event  = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $location    = mysqli_real_escape_string($conn, $_POST['tempat']);
    $reg_link    = mysqli_real_escape_string($conn, $_POST['link_daftar']);
    $description = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // kontak person publik (gunakan instansi dan WA instansi)
    $cp_name = $instansi;
    $cp_wa   = $wa_instansi;

    // ==========================
    // Harga (HTM) -> otomatis jadi angka
    // Gratis => 0
    // Rp 25.000 => 25000
    // ==========================
    $htmRaw = strtolower($_POST['htm']);
    if(strpos($htmRaw, "gratis") !== false){
        $price = 0;
    } else {
        $price = preg_replace("/[^0-9]/", "", $htmRaw);
        $price = ($price == "") ? 0 : (int)$price;
    }

    // ==========================
    // Upload poster
    // ==========================
    $posterName = $_FILES['poster']['name'];
    $posterTmp  = $_FILES['poster']['tmp_name'];

    $posterExt = strtolower(pathinfo($posterName, PATHINFO_EXTENSION));
    $allowedExt = ["jpg","jpeg","png"];

    if(!in_array($posterExt, $allowedExt)){
        $message = "Format poster harus JPG / JPEG / PNG!";
    } else {

        $newPosterName = time() . "_" . uniqid() . "." . $posterExt;
        $folder = "uploads/";
        $pathPoster = $folder . $newPosterName;

        if(move_uploaded_file($posterTmp, $pathPoster)){

            // ==========================
            // INSERT ke database
            // organizer_id + status pending
            // ==========================
            $query = "INSERT INTO events 
            (organizer_id, title, category, date_event, location, price, reg_link, description, poster, cp_name, cp_wa, instagram, status)
            VALUES
            ('$organizer_id', '$title', '$category', '$date_event', '$location', '$price', '$reg_link', '$description', '$newPosterName', '$cp_name', '$cp_wa', '$instagram', 'pending')";

            if(mysqli_query($conn, $query)){
                header("Location: event_saya.php?submit=success");
                exit;
            } else {
                $message = "Gagal menambahkan event: " . mysqli_error($conn);
            }

        } else {
            $message = "Upload poster gagal!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftarkan Event - Eventify</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* ===================== FORM EVENT PAGE ===================== */
        .event-form-page {
            background: #f6f9ff;
            padding: 60px 0;
            min-height: 100vh;
        }

        .event-form-wrapper {
            max-width: 850px;
            margin: auto;
            background: #fff;
            border-radius: 18px;
            padding: 36px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .form-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 10px;
            color: #0f172a;
            text-align: center;
        }

        .form-subtitle {
            text-align: center;
            color: #475569;
            margin-bottom: 28px;
            font-size: 15px;
        }

        .form-section {
            margin-bottom: 28px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #ffffff;
        }

        .form-section h3 {
            margin: 0 0 16px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h3 span {
            background: #0a74da;
            color: white;
            font-size: 13px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            transition: 0.2s;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0a74da;
            box-shadow: 0 0 0 4px rgba(10, 116, 218, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .full {
            grid-column: 1 / -1;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #0a74da;
            color: white;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 10px 20px rgba(10, 116, 218, 0.18);
        }

        .btn-submit:hover {
            background: #075bb1;
        }

        .note-upload {
            font-size: 13px;
            color: #64748b;
            margin-top: -6px;
        }

        @media(max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .event-form-wrapper {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <?php include "components/navbar.php"; ?>

    <section class="event-form-page">
        <div class="event-form-wrapper">

            <h1 class="form-title">Daftarkan Event Kampusmu</h1>
            <p class="form-subtitle">
                Lengkapi data event sesuai format yang tersedia agar event kamu bisa tayang di Eventify dengan rapi ✅
            </p>

            <?php if($message != ""): ?>
                <p style="background:#fee2e2; padding:12px; border-radius:10px; color:#991b1b; font-weight:700;">
                    <?= $message; ?>
                </p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian A -->
                <div class="mou-box">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Instansi</label>
                            <input type="text" name="instansi" required placeholder="Contoh: HIMA Informatika UNSIKA">
                        </div>

                        <div class="form-group">
                            <label>Nomor WhatsApp Instansi</label>
                            <input type="text" name="wa_instansi" required placeholder="Contoh: 6281234567890">
                        </div>

                        <!-- Instagram opsional -->
                        <div class="form-group full">
                            <label>Instagram (Opsional)</label>
                            <input type="url" name="instagram" placeholder="https://instagram.com/username">
                        </div>
                    </div>
                </div>

                <!-- Bagian B -->
                <div class="mou-box">

                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" required
                                placeholder="Contoh: Seminar Teknologi & AI Kampus">
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Musik">Musik</option>
                                <option value="Seni">Seni</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Volunteer">Volunteer</option>
                                <option value="Kompetisi">Kompetisi</option>
                                <option value="Keagamaan">Keagamaan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal" required>
                        </div>

                        <div class="form-group full">
                            <label>Tempat Pelaksanaan</label>
                            <input type="text" name="tempat" required
                                placeholder="Contoh: Aula Husni Hamid / Zoom Meeting">
                        </div>
                    </div>
                </div>

                <!-- Bagian C -->
                <div class="mou-box">

                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Link Pendaftaran</label>
                            <input type="url" name="link_daftar" required placeholder="https://...">
                        </div>

                        <div class="form-group">
                            <label>HTM</label>
                            <input type="text" name="htm" required placeholder="Contoh: Gratis / Rp 25.000">
                        </div>
                    </div>
                </div>

                <!-- Bagian D -->
                <div class="mou-box">

                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Deskripsi / Caption</label>
                            <textarea name="deskripsi" required
                                placeholder="Tuliskan caption lengkap event kamu..."></textarea>
                        </div>

                        <div class="form-group full">
                            <label>Kontak Person untuk Publik</label>
                            <input type="text" name="cp_publik" required placeholder="Contoh: Kak Rizky (wa.me/628...)">
                        </div>
                    </div>
                </div>

                <!-- Bagian E -->
                <div class="mou-box">
                    <h4>Upload Poster Kegiatan</h4>

                        <p>Upload Poster (png, jpg, jpeg)</p>
                        <input type="file" name="poster" accept=".png, .jpg, .jpeg" required>
                
                </div>

                <!-- Bagian F -->
                <div class="mou-box">
                    <h4>Template MoU</h4>
                    <p>Download template MoU terlebih dahulu, isi & tanda tangani, lalu upload kembali di bawah.</p>

                    <a class="btn-mou" href="https://drive.google.com/drive/folders/1lJaJEarhPkzO3oNi7ysDeJ4T4CZQLkSH?usp=drive_link"
                        target="_blank">
                        Download Template MoU
                    </a>

                    <div class="upload-mou">
                        <label>Upload MoU (PDF)</label>
                        <input type="file" name="mou" accept=".pdf" required>
                    </div>
                </div>


                <button type="submit" name="submit" class="btn-submit">🚀 Kirim Pendaftaran Event</button>

            </form>

        </div>
    </section>

    <?php include "components/footer.php"; ?>

</body>

</html>
