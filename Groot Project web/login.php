<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        header('Location: login.php?error=1');
        exit;
    }

    $stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string) $user['password_hash'])) {
        header('Location: login.php?error=1');
        exit;
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_email'] = (string) $user['email'];
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LiveLedger - Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="topbar">
    <a href="home.html" class="logo">LiveLedger</a>
    <nav class="navlinks">
      <a href="shows.php">Recente shows</a>
      <a href="artist.html">Artiesten</a>
      <a href="venues.html">Venues</a>
      <a href="community.html">Community</a>
      <a href="profile.php">Profiel</a>
    </nav>
    <div class="actions">
      <a href="register.php" class="btn btn-ghost">Registreren</a>
    </div>
  </header>
  <main class="container section">
    <p class="kicker">Account</p>
    <h1>Inloggen</h1>
    <?php if (isset($_GET['error'])): ?>
      <p class="sub">Inloggen mislukt. Controleer je gegevens.</p>
    <?php endif; ?>
    <form class="searchbar section" method="post" action="login.php">
      <input type="email" name="email" placeholder="E-mail" required />
      <input type="password" name="password" placeholder="Wachtwoord" required />
      <button class="btn btn-accent" type="submit">Login</button>
    </form>
    <p class="sub">Nog geen account? <a href="register.php">Registreer hier</a>.</p>
  </main>
</body>
</html>
