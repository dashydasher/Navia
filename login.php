<!DOCTYPE html>
<html>
<head>

  <title></title>
  <link rel="stylesheet" type="text/css" href="public/css/main.css">
</head>
<body>

<header>
    <nav>
        <div class="main-wrapper">
          <div class="nav-login">
              <form>
                    <input type="text" name="uid" placeholder="username">
                    <input type="password" name="pwd" placeholder="lozinka">
                    <button type="submit" name="submit">Prijava</button>

              </form>

          </div>

        </div>

    </nav>
</header>

<section class="main-container">
  <div class="main-wrapper">
      <h2>Registracija</h2>
      <form class="signup-form" action="index.inc.php" method="post">
          <input type="text" name="first" placeholder="Ime">
          <input type="text" name="last" placeholder="Prezime">
          <input type="text" name="uid" placeholder="Username">
          <input type="password" name="pwd" placeholder="Lozinka">
          <button type="submit" name="submit">Registriraj se</button>
      </form>
  </div>
</section>

</body>




</html>
