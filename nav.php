<style>
*, html {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: poppins;
}

body {
    background-color: white;
    padding-top: 60px;
}

.head {
    background-color: #5a1212; 
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.4); 
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo img {
    height: 40px;
}

.logo h1 {
    color: white; 
}

.nav ul {
    display: flex;
    list-style-type: none;
    gap: 30px;
}

.nav ul li {
    position: relative;
}

.nav ul li a {
    text-decoration: none;
    color: white; 
    font-size: 14px;
    font-weight: 600;
    transition: all 300ms;
    border-bottom: 2px solid transparent;
    display: flex;
    align-items: center;
    gap: 5px;
}

.nav ul li a:hover {
    border-bottom: 2px solid white;
}

.nav ul li .icon-sidebar {
    display: none;
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1001;
    border-radius: 5px;
    top: 100%;
    left: 0;
    border: 1px solid #ddd;
}

.dropdown-content a {
    color: black !important;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-size: 13px !important;
    font-weight: 500 !important;
    border-bottom: none !important;
    transition: background-color 0.3s;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
    border-bottom: none !important;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown > a::after {
    content: "▼";
    font-size: 10px;
    margin-left: 5px;
    transition: transform 0.3s;
    color: white; 
}

.dropdown:hover > a::after {
    transform: rotate(180deg);
}

.icon {
    display: none;
    font-size: 30px;
    cursor: pointer;
    color: white; 
}

.user-icon {
    font-size: 30px;
    cursor: pointer;
    color: white; 
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

@media only screen and (max-width : 768px) {
    .head {
        background-color: #5a1212; 
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
        color: white; 
    }
    
    .user-icon {
        order: 3;
        font-size: 24px;
        margin-left: 10px;
        color: white; 
    }

    .nav {
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        background: white; 
        border-radius: 0 0 10px 10px;
        display: none;
        z-index: 1000;
        text-align: left;
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
        padding: 0;
        display: block;
        position: relative;
    }

    .nav ul li > a {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        color: black; 
        font-weight: 500;
        text-decoration: none;
        border-bottom: none !important;
        padding: 15px 20px;
        width: 100%;
        gap: 10px;
    }

    .nav ul li:hover {
        background-color: rgba(0, 0, 0, 0.105);
    }

    .nav ul li .icon-sidebar {
        display: inline-block;
        font-size: 18px;
        color: black; 
    }

    .dropdown-content {
        position: relative;
        display: none;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        border: none;
        background-color: #f5f5f5;
        margin-top: 5px;
        border-radius: 5px;
        width: 100%;
        left: 0;
    }

    .dropdown.active .dropdown-content {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 200px;
        }
    }

    .dropdown > a::after {
        margin-left: auto;
        transition: transform 0.3s;
        color: black; 
    }

    .dropdown.active > a::after {
        transform: rotate(180deg);
    }

    .dropdown-content a {
        padding: 12px 16px 12px 40px !important;
        font-size: 13px !important;
        border-left: 3px solid transparent;
        display: block !important;
        color: #555 !important;
        font-weight: 400 !important;
    }

    .dropdown-content a:hover {
        border-left: 3px solid #007bff;
        background-color: #e9ecef;
        color: #007bff !important;
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
    <img src="aset/logo.png" alt="Logo">
    <h1>Desa Selorejo</h1>
  </div>

  <nav class="nav">
    <ul id="menu-list">
      <li><a href="index.php"><i class="ph ph-house icon-sidebar"></i>Home</a></li>
      <li><a href="profil-desa.php"><i class="ph ph-map-trifold icon-sidebar"></i>Profil Desa</a></li>
      <li><a href="berita.php"><i class="ph ph-notebook icon-sidebar"></i>Berita</a></li>
      <li><a href="struktur.php"><i class="ph ph-browser icon-sidebar"></i>Struktur</a></li>
      <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown-toggle">
          <i class="ph ph-browser icon-sidebar"></i>Lembaga Masyarakat
        </a>
        <div class="dropdown-content">
          <a href="lembaga-lpmd.php">LPMD (Lembaga Pemberdayaan Masyarakat Desa)</a>
          <a href="lembaga-pkk.php">PKK (Pemberdayaan Kesejahteraan Keluarga)</a>
          <a href="lembaga-rt-rw.php">RT/RW</a>
          <a href="lembaga-karang-taruna.php">Karang Taruna</a>
        </div>
      </li>
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

  document.addEventListener("DOMContentLoaded", function() {
    const dropdownToggle = document.querySelector(".dropdown-toggle");
    const dropdown = document.querySelector(".dropdown");

    if (dropdownToggle && dropdown) {
      dropdownToggle.addEventListener("click", function(e) {
        e.preventDefault();
        if (window.innerWidth <= 768) {
          dropdown.classList.toggle("active");
          e.stopPropagation();
        }
      });
    }

    document.addEventListener("click", function(e) {
      if (window.innerWidth <= 768 && dropdown) {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove("active");
        }
      }
    });
  });

  window.addEventListener("resize", function() {
    const dropdown = document.querySelector(".dropdown");
    if (window.innerWidth > 768) {
      dropdown.classList.remove("active");
    }
  });
</script>