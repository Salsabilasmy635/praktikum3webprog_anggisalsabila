<?php

require 'connection.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $sql = "UPDATE mahasiswa SET npm = ?, nama = ?, jurusan = ? WHERE id
= ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$npm, $nama, $jurusan, $id])) {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0e1a; --surface: #111827; --surface2: #1a2236;
            --border: #1e2d45; --accent: #f59e0b; --accent2: #fbbf24;
            --blue: #3b82f6; --text: #e2e8f0; --muted: #64748b; --dim: #94a3b8;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh;
            background-image: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(245,158,11,0.09), transparent);
        }
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(10,14,26,0.9); backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem; height: 58px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; display: flex; align-items: center; gap: 9px; }
        .brand-icon { width: 30px; height: 30px; background: linear-gradient(135deg, var(--accent), #fb923c); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .nav-back { color: var(--muted); text-decoration: none; font-size: 0.82rem; transition: color 0.2s; }
        .nav-back:hover { color: var(--text); }

        .wrap { max-width: 520px; margin: 2.5rem auto; padding: 0 1.25rem; }
        .crumb { font-size: 0.75rem; color: var(--muted); margin-bottom: 0.5rem; }
        .crumb a { color: var(--blue); text-decoration: none; }

        /* Preview data lama */
        .preview {
            background: rgba(245,158,11,0.07); border: 1px solid rgba(245,158,11,0.2);
            border-radius: 12px; padding: 0.9rem 1.1rem; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 12px;
        }
        .preview-ava { width: 38px; height: 38px; border-radius: 9px; background: linear-gradient(135deg, var(--accent), #fb923c); display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
        .preview-name { font-weight: 600; color: var(--text); font-size: 0.88rem; }
        .preview-nim { color: var(--accent); font-size: 0.76rem; margin-top: 2px; }

        /* h2 ASLI */
        h2 { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 1.25rem; }
        h2 span { background: linear-gradient(90deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 1.75rem; }

        /* FORM — label+input ASLI */
        label { display: block; font-size: 0.75rem; font-weight: 500; color: var(--dim); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.45rem; margin-top: 1.1rem; }
        label:first-of-type { margin-top: 0; }
        input[type="text"] {
            display: block; width: 100%;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 10px; padding: 0.7rem 1rem;
            color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.88rem;
            outline: none; transition: all 0.2s;
        }
        input[type="text"]:focus { border-color: var(--accent); background: rgba(245,158,11,0.05); box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }

        .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }
        .form-actions { display: flex; gap: 0.7rem; justify-content: flex-end; }

        /* Button ASLI */
        button[type="submit"] {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.6rem 1.4rem; border-radius: 9px;
            background: linear-gradient(135deg, var(--accent), #d97706);
            border: none; color: white; font-size: 0.83rem;
            font-family: 'DM Sans', sans-serif; font-weight: 500; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 4px 14px rgba(245,158,11,0.3);
        }
        button[type="submit"]:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.45); }

        /* Link Batal ASLI */
        a.btn-batal {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.6rem 1.2rem; border-radius: 9px;
            background: var(--surface2); border: 1px solid var(--border);
            color: var(--dim); text-decoration: none; font-size: 0.83rem;
            font-family: 'DM Sans', sans-serif; transition: all 0.15s;
        }
        a.btn-batal:hover { border-color: var(--muted); color: var(--text); }

        @media(max-width:480px){ .wrap{padding:0 1rem; margin-top:1.5rem;} .card{padding:1.25rem;} .form-actions{flex-direction:column-reverse;} .form-actions a, .form-actions button{width:100%;justify-content:center;} }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="brand"><div class="brand-icon">✏️</div> SiAkademik</div>
    <a href="index.php" class="nav-back">← Kembali ke Daftar</a>
</nav>

<div class="wrap">
    <div class="crumb"><a href="index.php">Beranda</a> › Edit Mahasiswa</div>

    <!-- Preview data yang sedang diedit -->
    <div class="preview">
        <div class="preview-ava">👤</div>
        <div>
            <div class="preview-name"><?= htmlspecialchars($data['nama']); ?></div>
            <div class="preview-nim">NIM: <?= htmlspecialchars($data['npm']); ?> · <?= htmlspecialchars($data['jurusan']); ?></div>
        </div>
    </div>

    <!-- h2 ASLI -->
    <h2>Edit <span>Mahasiswa</span></h2>

    <div class="card">
        <!-- FORM ASLI — method, action, name, value, required tidak diubah -->
        <form method="POST" action="">
            <label>NIM:</label>
            <input type="text" name="npm" value="<?= $data['npm']; ?>" required>

            <label>Nama:</label>
            <input type="text" name="nama" value="<?= $data['nama']; ?>" required>

            <label>Jurusan:</label>
            <input type="text" name="jurusan" value="<?= $data['jurusan']; ?>" required>

            <div class="divider"></div>

            <div class="form-actions">
                <!-- Link Batal ASLI -->
                <a href="index.php" class="btn-batal">✕ Batal</a>
                <!-- Button ASLI -->
                <button type="submit">✔ Update Data</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>