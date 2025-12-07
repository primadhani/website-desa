<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
*, html {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background-color: white;
  padding-top: 60px;
}

.head {
  background-color: whitesmoke;
  display: flex;
  align-items: center;
  padding: 10px 100px;
  justify-content: space-between;
  height: 60px;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo img {
  height: 40px;
}

.nav ul {
  display: flex;
  list-style-type: none;
  gap: 30px;
}

.nav ul li a {
  text-decoration: none;
  color: black;
  font-size: 14px;
  font-weight: 600;
  transition: all 300ms;
  border-bottom: 2px solid transparent;
}

.nav ul li a:hover {
  border-bottom: 2px solid black;
}

.nav ul li .icon-sidebar {
  display: none;
}

.icon {
  display: none;
  font-size: 30px;
  cursor: pointer;
}

.user-icon {
  font-size: 30px;
  cursor: pointer;
  color: black;
  margin-left: 20px;
  display: flex;
  align-items: center;
  position: relative;
}

.profile-menu {
  display: none;
  position: absolute;
  top: 50px;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  z-index: 2000;
  min-width: 150px;
}

.profile-menu a {
  display: block;
  padding: 10px 15px;
  text-decoration: none;
  color: black;
  font-size: 14px;
}

.profile-menu a:hover {
  background-color: #f5f5f5;
}

@media only screen and (max-width: 768px) {
  .head {
    padding: 10px 20px;
  }

  .logo {
    order: 1;
  }

  .icon {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 25px;
    order: 2;
    margin-left: auto;
  }

  .user-icon {
    order: 3;
    font-size: 24px;
    margin-left: 10px;
  }

  .nav {
    position: absolute;
    top: 60px;
    right: 0;
    width: 60%;
    background: white;
    border-radius: 0 0 0 10px;
    display: none;
    z-index: 1000;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }

  .nav.show {
    display: block;
  }

  .nav ul {
    flex-direction: column;
    gap: 0;
    padding: 10px 0;
  }

  .nav ul li {
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
  }

  .nav ul li a {
    display: block;
    color: black;
    font-weight: 500;
    text-decoration: none;
    border-bottom: none !important;
  }

  .nav ul li:hover {
    background-color: rgba(0, 0, 0, 0.105);
  }

  .nav ul li .icon-sidebar {
    display: inline-block;
    font-size: 18px;
    color: black;
  }

  h1 {
    font-size: 24px;
    line-height: 1.2;
  }

  h2 {
    font-size: 22px;
    line-height: 1.2;
  }

  h3 {
    font-size: 20px;
    line-height: 1.3;
  }

  h4 {
    font-size: 18px;
    line-height: 1.4;
  }

  h5 {
    font-size: 16px;
    line-height: 1.4;
  }

  h6 {
    font-size: 14px;
    line-height: 1.5;
  }

  p {
    font-size: 14px;
    line-height: 1.5;
  }
}
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
    <link rel="icon" href="aset/favicon.png" type="image/png">


<header class="head">
  <div class="logo">
    <p style="font-weight: bold;">Admin</p>
  </div>
  <nav class="nav">
    <ul id="menu-list">
      <li><a href="manage-potensi.php"></i>Potensi</a></li>
      <li><a href="manage-berita.php"></i>Berita</a></li>
      <li><a href="manage-struktur.php"></i>Struktur</a></li>
      <li><a href="manage-lembaga.php"></i>Lembaga Masyarakat</a></li>
      <li><a href="manage-komentar.php"></i>Komentar</a></li>
      <li><a href="manage-user.php"></i>User</a></li>
      <li><a href="logout.php"></i>Logout</a></li>
    </ul>
  </nav>
  <div id="icon" class="icon">
    <i class="ph ph-list"></i>
  </div>
</header>

<script>
const menuIcon = document.getElementById("icon");
const nav = document.querySelector(".nav");

menuIcon.addEventListener("click", () => {
  nav.classList.toggle("show");
});

document.addEventListener("click", (e) => {
  if (!nav.contains(e.target) && !menuIcon.contains(e.target)) {
    nav.classList.remove("show");
  }
});
</script>
