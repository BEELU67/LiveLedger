<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
requireLogin();
require __DIR__ . '/config.php';

$email = (string) $_SESSION['user_email'];
$username = strstr($email, '@', true);
if ($username === false || $username === '') {
    $username = $email;
}

$stmt = $pdo->prepare(
    'SELECT show_date, artist, venue, city
     FROM shows
     WHERE user_id = :user_id
     ORDER BY show_date DESC, artist ASC'
);
$stmt->execute([':user_id' => currentUserId()]);
$myShows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LiveLedger - Profiel</title>
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
      <a href="logout.php" class="btn btn-ghost">Uitloggen</a>
      <a href="add-show.php" class="btn btn-accent">Show toevoegen</a>
    </div>
  </header>

  <main class="container section">
    <p class="kicker">Gebruiker</p>
    <h1>@<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="hero-copy"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>

    <section class="section">
      <div class="section-head"><h2>Jouw toegevoegde shows</h2></div>
      <div class="grid shows-grid">
        <?php if (!$myShows): ?>
          <article class="card"><p>Je hebt nog geen shows toegevoegd.</p></article>
        <?php else: ?>
          <?php foreach ($myShows as $show): ?>
            <article class="card">
              <p class="meta"><?php echo htmlspecialchars((string) $show['show_date'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars((string) $show['venue'], ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars((string) $show['city'], ENT_QUOTES, 'UTF-8'); ?></p>
              <h3><?php echo htmlspecialchars((string) $show['artist'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>
