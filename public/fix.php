<?php
$pdo = new PDO('mysql:host=storage-lab-jpb9jz;dbname=db_digitalisasi', 'digitalisasi_user', 'digital-lab-kimia');

// Cek dan tambah google_id
$cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'google_id'")->fetchAll();
if (empty($cols)) {
    $pdo->exec("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL");
    echo "google_id added!<br>";
} else {
    echo "google_id already exists<br>";
}

// Cek dan tambah avatar
$cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'avatar'")->fetchAll();
if (empty($cols)) {
    $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL");
    echo "avatar added!<br>";
} else {
    echo "avatar already exists<br>";
}

echo "Done!";