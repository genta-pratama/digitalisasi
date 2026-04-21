<?php
$pdo = new PDO('mysql:host=storage-lab-jpb9jz;dbname=db_digitalisasi', 'digitalisasi_user', 'digital-lab-kimia');
$pdo->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) NULL');
$pdo->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) NULL');
echo 'Done!';