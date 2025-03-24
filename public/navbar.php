<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../images/logo/favicon.ico"> 
  <?php include_once("../assets/link.html"); ?>
  <style>
    /* Page background (sandalwood/sandy beige) */
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
    }

    /* Navbar styling */
    .navbar {
      background-color: #ffffff !important; /* White */
      transition: all 0.3s ease-in-out;
      z-index: 1030; /* Bootstrap default for fixed-top */
    }
    .navbar.scrolled {
      background-color: #bcbcbc !important; /* Light gray when scrolled */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }
    .nav-link {
      color: #3e2723 !important; /* Dark brown for agri theme */
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .nav-link:hover {
      color: #ffca28 !important; /* Golden yellow on hover */
      transform: scale(1.1); /* Slight growth effect */
    }
    .navbar-brand img {
      transition: transform 0.4s ease;
    }
    .navbar-brand:hover img {
      transform: rotate(15deg) scale(1.2); /* Logo spins and grows */
    }
    .btn-primary {
      background-color: #689f38 !important; /* Lime green */
      border-color: #689f38 !important;
      transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #8bc34a !important; /* Lighter green */
      transform: scale(1.05); /* Subtle growth */
    }
    .btn-warning {
      background-color: #e57373 !important; /* Warm coral red */
      color: #ffffff !important; /* White text for contrast */
      border-color: #e57373 !important;
      transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .btn-warning:hover {
      background-color: #ff8a80 !important; /* Lighter coral */
      transform: scale(1.05);
    }

    /* Smooth collapse transition */
    .navbar-collapse {
      overflow: visible; /* Prevent clipping of dropdowns */
    }
    .navbar-collapse.collapsing {
      transition: opacity 0.3s ease-in-out;
    }
    .navbar-collapse.show {
      opacity: 1;
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      background-color: #ffffff; /* White */
      border: 1px solid #689f38; /* Lime green border */
      z-index: 9999; /* Very high to ensure it’s on top */
      position: absolute; /* Ensure it pops out */
      top: 100%; /* Position below the toggle */
      left: 0;
      min-width: 10rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .dropdown-item {
      color: #3e2723; /* Dark brown */
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .dropdown-item:hover {
      background-color: #f4e1d2; /* Sandy beige */
      color: #ffca28; /* Golden yellow */
    }
    .dropdown-divider {
      border-color: rgba(104, 159, 56, 0.2); /* Subtle lime green */
    }
    .dropdown-toggle img {
      transition: transform 0.3s ease;
    }
    .dropdown-toggle:hover img {
      transform: scale(1.1); /* Slight growth for profile image */
    }
    /* Ensure dropdown visibility on mobile */
    @media (max-width: 991px) {
      .dropdown-menu {
        position: static !important;
        width: 100%;
        border: none;
        box-shadow: none;
        z-index: 9999; /* Still high on mobile */
      }
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-2 py-0 fixed-top animate__animated animate__fadeInDown">
    <a class="navbar-brand" href="../public/auctions.php">
      <img width="54px" height="54px" src="../images/logo/android-chrome-192x192.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="../public/auctions.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Auction
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../public/add-auction.php">Add Auctions</a>
            <a class="dropdown-item" href="../public/revisited-auctions.php">Revisited Auction</a>
            <a class="dropdown-item" href="../public/manage-auction.php">Manage Auction</a>
          </div>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="../public/add-moments.php">Add Moment</a>
        </li>
      </ul>
      <?php if (isset($_SESSION["userId"]) && $_SESSION["userId"] != null): ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <?php $userImg = isset($_SESSION["userId"]) ? getUserImg($_SESSION["userId"]) : ''; ?>
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="../images/profiles/<?= $userImg ?>" alt="Profile" class="rounded-circle border border-dark" width="30" height="30">
              <span class="text-secondary px-1"><?= $_SESSION["userName"] ?? 'Guest' ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
              <div class="d-flex align-items-center px-3 py-2">
                <div>
                  <p class="mb-0 font-weight-bold text-dark">
                    <?= $_SESSION["userName"] ?? "Guest" ?>
                  </p>
                  <p class="mb-0 text-muted">
                    <?= $_SESSION["userEmail"] ?? "guest@gmail.com" ?>
                  </p>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="profile.php">Profile</a>
              <a class="dropdown-item" href="settings.php">Settings</a>
              <div class="dropdown-divider"></div>
              <form action="logout.php" method="post">
                <button class="dropdown-item" name="logout">Logout</button>
              </form>
            </div>
          </li>
        </ul>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  <!-- Navbar Animation Scripts -->
  <script>
    // 1. Navbar background transition on scroll
    $(window).scroll(function() {
      if ($(this).scrollTop() > 50) {
        $('.navbar').addClass('scrolled');
      } else {
        $('.navbar').removeClass('scrolled');
      }
    });

    // 2. GSAP animation for navbar items on page load
    gsap.from('.navbar-nav .nav-item', {
      duration: 1,
      y: -30,
      opacity: 0,
      stagger: 0.2,
      ease: 'back.out(1.7)', // Springy, growth-like effect
      delay: 0.5
    });

    // 3. Animate collapse menu with GSAP (Mobile/Tablet Fix)
    const $collapse = $('#navbarNav');
    $collapse.on('show.bs.collapse', function() {
      gsap.fromTo(this, 
        { opacity: 0, y: -20 }, 
        { 
          duration: 0.5, 
          opacity: 1, 
          y: 0, 
          ease: 'power2.out' 
        }
      );
    });
    $collapse.on('hide.bs.collapse', function() {
      gsap.to(this, { 
        duration: 0.3, 
        opacity: 0, 
        y: -20, 
        ease: 'power2.in' 
      });
    });

    // 4. GSAP hover effect for nav links (desktop only)
    if (window.innerWidth > 991) { 
      document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
          if (!link.classList.contains('dropdown-toggle') || !$(link).parent().hasClass('show')) {
            gsap.to(link, { 
              duration: 0.3, 
              scale: 1.1, 
              color: '#ffca28', 
              ease: 'power1.out' 
            });
          }
        });
        link.addEventListener('mouseleave', () => {
          if (!link.classList.contains('dropdown-toggle') || !$(link).parent().hasClass('show')) {
            gsap.to(link, { 
              duration: 0.3, 
              scale: 1, 
              color: '#3e2723', 
              ease: 'power1.out' 
            });
          }
        });
      });
    }
  </script>
</body>
</html>