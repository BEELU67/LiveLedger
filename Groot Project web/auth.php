<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']) && !empty($_SESSION['user_email']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php?error=auth');
        exit;
    }
}

function currentUserId(): int
{
    return (int) ($_SESSION['user_id'] ?? 0);
}
