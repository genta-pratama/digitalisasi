<?php
$pdo = new PDO('mysql:host=storage-lab-jpb9jz;dbname=db_digitalisasi', 'digitalisasi_user', 'digital-lab-kimia');

$pdo->exec("ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL");
echo "password nullable done!<br>";

echo "Done!";