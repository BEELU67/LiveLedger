<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/config.php';

$search = trim((string) ($_GET['q'] ?? ''));
$allSql = 'SELECT s.show_date, s.artist, s.venue, s.city, u.email
           FROM shows s
           JOIN users u ON u.id = s.user_id';
$allParams = [];

if ($search !== '') {
    $allSql .= ' WHERE s.artist LIKE :q OR s.venue LIKE :q OR s.city LIKE :q OR s.show_date = :exact_date';
    $allParams[':q'] = '%' . $search . '%';
    $allParams[':exact_date'] = $search;
}

$allSql .= ' ORDER BY s.show_date DESC, s.artist ASC';
$allStmt = $pdo->prepare($allSql);
$allStmt->execute($allParams);
$allShows = $allStmt->fetchAll();

$myShows = [];
if (isLoggedIn()) {
    $myStmt = $pdo->prepare(
        'SELECT show_date, artist, venue, city
         FROM shows
         WHERE user_id = :user_id
         ORDER BY show_date DESC, artist ASC'
    );
    $myStmt->execute([':user_id' => currentUserId()]);
    $myShows = $myStmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LiveLedger - Shows</title>
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
      <?php if (isLoggedIn()): ?>
        <a href="logout.php" class="btn btn-ghost">Uitloggen</a>
        <a href="add-show.php" class="btn btn-accent">Show toevoegen</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-ghost">Inloggen</a>
        <a href="register.php" class="btn btn-accent">Registreren</a>
      <?php endif; ?>
    </div>
  </header>
  <main class="container section">
    <p class="kicker">Shows</p>
    <h1>Alle opgeslagen shows</h1>
    <form class="searchbar section" action="shows.php" method="get">
      <input type="search" name="q" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Zoek op artiest, venue, stad of datum (YYYY-MM-DD)" />
      <button type="submit" class="btn btn-accent">Zoeken</button>
    </form>
    <div class="grid shows-grid section">
      <?php if (!$allShows): ?>
        <article class="card"><p>Nog geen shows opgeslagen.</p></article>
      <?php endif; ?>
      <?php foreach ($allShows as $show): ?>
        <article class="card">
          <p class="meta"><?php echo htmlspecialchars((string) $show['show_date'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars((string) $show['venue'], ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars((string) $show['city'], ENT_QUOTES, 'UTF-8'); ?></p>
          <h3><?php echo htmlspecialchars((string) $show['artist'], ENT_QUOTES, 'UTF-8'); ?></h3>
          <p class="sub">Toegevoegd door <?php echo htmlspecialchars((string) $show['email'], ENT_QUOTES, 'UTF-8'); ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <section class="section">
      <div class="section-head"><h2>Jouw toegevoegde shows</h2></div>
      <div class="grid shows-grid">
        <?php if (!isLoggedIn()): ?>
          <article class="card"><p>Log in om je eigen lijst te zien.</p></article>
        <?php elseif (!$myShows): ?>
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
