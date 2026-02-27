<?php
require 'connection.php';
$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC");
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0e1a;
            --surface: #111827;
            --surface2: #1a2236;
            --border: #1e2d45;
            --accent: #3b82f6;
            --accent2: #06b6d4;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --text: #e2e8f0;
            --muted: #64748b;
            --dim: #94a3b8;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg); color: var(--text); min-height: 100vh;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59,130,246,0.13), transparent),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(6,182,212,0.07), transparent);
        }
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(10,14,26,0.9); backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem; height: 58px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; display: flex; align-items: center; gap: 9px; }
        .brand-icon { width: 30px; height: 30px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .db-status { display: flex; align-items: center; gap: 6px; font-size: 0.74rem; color: var(--success); }
        .dot { width: 7px; height: 7px; background: var(--success); border-radius: 50%; animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.35} }

        .wrap { max-width: 900px; margin: 0 auto; padding: 2rem 1.25rem; }

        .page-top { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }

        /* h2 ASLI — hanya ditambah styling */
        h2 { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1; }
        h2 span { background: linear-gradient(90deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sub { color: var(--muted); font-size: 0.8rem; margin-top: 5px; }

        /* Link "Tambah Data Baru" ASLI */
        a.btn-tambah {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: #fff !important; text-decoration: none;
            padding: 0.55rem 1.15rem; border-radius: 10px;
            font-size: 0.82rem; font-weight: 500;
            box-shadow: 0 4px 14px rgba(59,130,246,0.3);
            transition: all 0.2s; white-space: nowrap;
        }
        a.btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.45); }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.9rem; }
        .badge { background: rgba(59,130,246,0.15); color: var(--accent); font-size: 0.68rem; padding: 2px 8px; border-radius: 20px; }
        .tbl-wrap { width: 100%; overflow-x: auto; }

        /* TABLE ASLI — border="1" di tag diganti via CSS */
        table { width: 100%; border-collapse: collapse; min-width: 480px; }
        table th { background: var(--surface2); padding: 0.72rem 1.25rem; text-align: left; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); white-space: nowrap; border: none; }
        table td { padding: 0.85rem 1.25rem; font-size: 0.85rem; color: var(--dim); border: none; border-top: 1px solid var(--border); }
        tbody tr:hover { background: var(--surface2); }

        /* Styling konten cell */
        td .no { display: inline-flex; width: 24px; height: 24px; align-items: center; justify-content: center; background: var(--surface2); border-radius: 5px; font-size: 0.72rem; color: var(--muted); }
        td .nim { font-family: 'Syne', sans-serif; font-size: 0.78rem; font-weight: 700; color: var(--accent2); background: rgba(6,182,212,0.08); padding: 3px 8px; border-radius: 5px; }
        td .nama { color: var(--text); font-weight: 500; }
        td .jur { background: rgba(59,130,246,0.1); color: var(--accent); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
        .acts { display: flex; gap: 5px; }

        /* Link Edit & Hapus ASLI — href tidak diubah */
        td a { text-decoration: none; display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 7px; font-size: 0.75rem; font-weight: 500; border: 1px solid transparent; transition: all 0.15s; white-space: nowrap; }
        td a:first-child { background: rgba(245,158,11,0.1); color: var(--warning); border-color: rgba(245,158,11,0.2); }
        td a:first-child:hover { background: rgba(245,158,11,0.2); border-color: var(--warning); }
        td a:last-child { background: rgba(239,68,68,0.1); color: var(--danger); border-color: rgba(239,68,68,0.2); }
        td a:last-child:hover { background: rgba(239,68,68,0.2); border-color: var(--danger); }

        .empty { text-align: center; padding: 3.5rem 2rem; }
        .empty-ico { font-size: 2.5rem; opacity: 0.35; margin-bottom: 0.75rem; }

        @media(max-width:600px){ .wrap{padding:1.25rem 1rem;} h2{font-size:1.4rem;} }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="brand"><div class="brand-icon">🎓</div> SiAkademik</div>
    <div class="db-status"><div class="dot"></div> Database Terhubung</div>
</nav>

<div class="wrap">
    <div class="page-top">
        <div>
            <!-- h2 ASLI -->
            <h2>Daftar <span>Mahasiswa</span></h2>
            <p class="sub">Sistem Informasi Akademik</p>
        </div>
        <!-- Link ASLI, hanya tambah class -->
        <a href="tambah.php" class="btn-tambah">＋ Tambah Data Baru</a>
    </div>

    <div class="card">
        <div class="card-head">
            Data Mahasiswa
            <span class="badge"><?= count($mahasiswa) ?> data</span>
        </div>

        <div class="tbl-wrap">
            <!-- TABLE ASLI — persis sama strukturnya -->
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            <?php $no = 1; foreach($mahasiswa as $row): ?>
            <tr>
                <td><span class="no"><?= $no++; ?></span></td>
                <td><span class="nim"><?= htmlspecialchars($row['npm']); ?></span></td>
                <td><span class="nama"><?= htmlspecialchars($row['nama']); ?></span></td>
                <td><span class="jur"><?= htmlspecialchars($row['jurusan']); ?></span></td>
                <td>
                    <div class="acts">
                        <a href="edit.php?id=<?= $row['id']; ?>">✏️ Edit</a>
                        <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️ Hapus</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </table>
        </div>

        <?php if (count($mahasiswa) === 0): ?>
        <div class="empty">
            <div class="empty-ico">📭</div>
            <p style="color:#64748b;font-size:0.85rem;">Belum ada data. Klik "Tambah Data Baru" untuk mulai.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>