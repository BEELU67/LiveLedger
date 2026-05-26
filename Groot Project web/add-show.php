<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LiveLedger - Show toevoegen</title>
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
    </div>
  </header>
  <main class="container section">
    <p class="kicker">Nieuwe show</p>
    <h1>Show toevoegen</h1>
    <p id="form-status" class="sub"></p>
    <form class="searchbar section" action="save_show.php" method="post">
      <input type="date" name="show_date" required />
      <input type="text" name="venue" placeholder="Venue" required />
      <input type="text" name="city" placeholder="Plaats (stad)" required />
      <input type="text" name="artist" placeholder="Artiest" required />
      <button class="btn btn-accent" type="submit">Opslaan</button>
    </form>
  </main>
  <script>
    const params = new URLSearchParams(window.location.search);
    const status = document.getElementById("form-status");
    if (params.get("ok") === "1") {
      status.textContent = "Show opgeslagen in de database.";
    }
    if (params.get("error") === "1") {
      status.textContent = "Opslaan mislukt. Controleer je databaseconfiguratie.";
    }
  </script>
</body>
</html>
