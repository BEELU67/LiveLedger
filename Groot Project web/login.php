<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email !== '' && $password !== '') {
        $_SESSION['user_email'] = $email;
        header('Location: profile.php');
        exit;
    }

    header('Location: login.php?error=1');
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
      <a href="shows.html">Recente shows</a>
      <a href="artist.html">Artiesten</a>
      <a href="venues.html">Venues</a>
      <a href="community.html">Community</a>
      <a href="profile.php">Profiel</a>
    </nav>
  </header>
  <main class="container section">
    <p class="kicker">Account</p>
    <h1>Inloggen</h1>
    <?php if (isset($_GET['error'])): ?>
      <p class="sub">Inloggen mislukt of vereist. Vul e-mail en wachtwoord in.</p>
    <?php endif; ?>
    <form class="searchbar section" method="post" action="login.php">
      <input type="email" name="email" placeholder="E-mail" required />
      <input type="password" name="password" placeholder="Wachtwoord" required />
      <button class="btn btn-accent" type="submit">Login</button>
    </form>
  </main>
</body>
</html>
