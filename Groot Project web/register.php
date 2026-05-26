<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        header('Location: register.php?error=1');
        exit;
    }

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
        );
        $stmt->execute([
            ':email' => $email,
            ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $_SESSION['user_id'] = (int) $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        header('Location: profile.php');
        exit;
    } catch (Throwable $e) {
        header('Location: register.php?error=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LiveLedger - Registreren</title>
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
      <a href="login.php" class="btn btn-ghost">Inloggen</a>
    </div>
  </header>
  <main class="container section">
    <p class="kicker">Account</p>
    <h1>Registreren</h1>
    <?php if (isset($_GET['error'])): ?>
      <p class="sub">Registratie mislukt. Gebruik een geldig e-mail en min. 6 tekens wachtwoord.</p>
    <?php endif; ?>
    <form class="searchbar section" method="post" action="register.php">
      <input type="email" name="email" placeholder="E-mail" required />
      <input type="password" name="password" placeholder="Wachtwoord (min. 6 tekens)" minlength="6" required />
      <button class="btn btn-accent" type="submit">Account maken</button>
    </form>
  </main>
</body>
</html>
