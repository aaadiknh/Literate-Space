<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION['showMenu'])) {
    $_SESSION['showMenu'] = false;
}

if (isset($_POST['toggle_profile'])) {
    $_SESSION['showMenu'] = !$_SESSION['showMenu']; 
}

if (isset($_POST['logout'])) {
    session_destroy(); 
    header('Location: index.php'); 
    exit();
}
?>

<nav id="menu">
  <div class="nav-container">
    <div class="logo-container">
      <img id="logoLiterateSpace" src="assets/images/logo LiterateSpace.png" alt="Literate Space Logo">
    </div>
    <div class="right-nav">
      <?php if (isset($_SESSION['username'])): ?>

        <form method="POST" style="display: inline;">
          <button type="submit" name="toggle_profile" class="profile-icon-btn">
            <img class="profile-icon" src="assets/images/account.png" alt="User Icon">
          </button>
        </form>
        <?php if ($_SESSION['showMenu']): ?>
          <div class="profile-menu">
            <div class="profile-details">
              <img class="profile-img" src="assets/images/account.png" alt="User Icon">
              <div>
                <p class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <?php if (isset($_SESSION['email'])): ?> 
                  <p class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <?php else: ?>
                  <p class="email">Email tidak tersedia</p>
                <?php endif; ?>
              </div>
            </div>
            <form method="POST" style="width: 100%;">
              <button type="submit" name="logout" class="sign-out-btn"><b>SIGN OUT</b></button>
            </form>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <a href="login.php" class="login-btn">LOGIN/REGISTER</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
