<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-show.php');
    exit;
}

require __DIR__ . '/auth.php';
requireLogin();
require __DIR__ . '/config.php';

$showDate = trim($_POST['show_date'] ?? '');
$venue = trim($_POST['venue'] ?? '');
$city = trim($_POST['city'] ?? '');
$artist = trim($_POST['artist'] ?? '');

if ($showDate === '' || $venue === '' || $city === '' || $artist === '') {
    header('Location: add-show.php?error=1');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO shows (show_date, venue, city, artist) VALUES (:show_date, :venue, :city, :artist)'
    );
    $stmt->execute([
        ':show_date' => $showDate,
        ':venue' => $venue,
        ':city' => $city,
        ':artist' => $artist,
    ]);
} catch (Throwable $e) {
    header('Location: add-show.php?error=1');
    exit;
}

header('Location: add-show.php?ok=1');
exit;
