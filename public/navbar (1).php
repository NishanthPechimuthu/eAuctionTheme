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
    /* Remove default body margin and add padding to account for fixed navbar */
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
      margin: 0 !important; /* Remove default margin to prevent navbar offset */
      padding-top: 54px !important; /* Add padding to prevent content from being hidden under navbar */
    }

    /* Navbar styling */
    .navbar {
      background-color: #f4e1d2 !important; /* Sandy beige */
      border-bottom: 2px solid #3e2723 !important; /* Brown bottom border */
      transition: box-shadow 0.5s ease-in-out; /* Transition for shadow */
      z-index: 1050 !important; /* Higher than most elements */
    }
    .navbar.scrolled {
      background-color: #f4e1d2 !important; /* Same background when scrolled */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important; /* Add shadow when scrolled */
    }

    /* Nav links styling with underline animation on hover */
    .nav-link {
      color: #3e2723 !important; /* Dark brown */
      position: relative; /* For underline effect */
      transition: color 0.4s ease, transform 0.4s ease; /* Smoother transition */
    }
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: #f59e0b; /* Mustard yellow underline */
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: 100%; /* Underline grows on hover */
    }
    .nav-link:hover {
      color: #f59e0b !important; /* Mustard yellow on hover */
      transform: scale(1.05); /* Subtle scale */
    }

    /* Navbar brand (logo) styling */
    .navbar-brand img {
      transition: transform 0.4s ease;
    }
    .navbar-brand:hover img {
      transform: rotate(10deg) scale(1.1); /* Subtle rotation and scale */
    }

    /* Buttons styling */
    .btn-primary {
      background-color: #2f855a !important; /* Forest green */
      border-color: #2f855a !important;
      transition: background-color 0.4s ease, transform 0.4s ease, box-shadow 0.4s ease;
    }
    .btn-primary:hover {
      background-color: #38a169 !important; /* Lighter forest green */
      transform: scale(1.03); /* Subtle scale */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Shadow on hover */
    }
    .btn-warning {
      background-color: #c05621 !important; /* Terracotta */
      color: #ffffff !important; /* White text */
      border-color: #c05621 !important;
      transition: background-color 0.4s ease, transform 0.4s ease, box-shadow 0.4s ease;
    }
    .btn-warning:hover {
      background-color: #d97706 !important; /* Lighter terracotta */
      transform: scale(1.03); /* Subtle scale */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Shadow on hover */
    }

    /* Smooth collapse transition */
    .navbar-collapse {
      overflow: visible !important; /* Allow dropdowns to overflow */
      position: relative; /* Ensure dropdowns position relative to this */
    }
    .navbar-collapse.collapsing {
      transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
    }
    .navbar-collapse.show {
      opacity: 1;
      transform: scale(1);
    }

    /* Ensure navbar stays at the top */
    .navbar.fixed-top {
      top: 0 !important;
      position: fixed;
      width: 100%;
      z-index: 1050 !important; /* High z-index */
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      background-color: #f3e8d6 !important; /* Light beige */
      border: 1px solid #3e2723; /* Dark brown border */
      z-index: 1060 !important; /* Higher than navbar to ensure visibility */
      position: absolute; /* Ensure it pops out */
      top: 100%; /* Below the toggle */
      left: 0;
      min-width: 12rem; /* Slightly wider */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Deeper shadow */
      border-radius: 8px; /* Softer corners */
      opacity: 0; /* Start hidden for animation */
      transform: translateY(-10px); /* Start slightly above */
      transition: opacity 0.3s ease, transform 0.3s ease; /* Smooth transition */
    }
    .dropdown-menu.show {
      opacity: 1; /* Fully visible */
      transform: translateY(0); /* Slide into place */
    }
    .dropdown-item {
      color: #3e2723 !important; /* Dark brown */
      transition: background-color 0.3s ease, color 0.3s ease, padding-left 0.3s ease;
    }
    .dropdown-item:hover {
      background-color: #e2d9c8 !important; /* Slightly darker beige */
      color: #f59e0b !important; /* Mustard yellow */
      padding-left: 1.5rem; /* Slight indent on hover */
    }
    .dropdown-divider {
      border-color: rgba(62, 39, 35, 0.2); /* Subtle brown */
    }
    .dropdown-toggle img {
      transition: transform 0.3s ease;
    }
    .dropdown-toggle:hover img {
      transform: scale(1.1); /* Slight growth for profile image */
    }
    /* Transparent dropdown arrow */
    .dropdown-toggle::after {
      border: none !important; /* Remove default border */
      background: transparent !important; /* Transparent background */
      content: '\f078'; /* Font Awesome chevron-down */
      font-family: "Font Awesome 6 Free"; /* Use Font Awesome */
      font-weight: 900; /* Solid icon weight */
      color: #3e2723; /* Dark brown */
      vertical-align: middle;
      margin-left: 5px;
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .dropdown-toggle:hover::after {
      color: #f59e0b; /* Mustard yellow on hover */
      transform: rotate(180deg); /* Flip arrow up */
    }
    .dropdown-toggle.show::after {
      transform: rotate(180deg); /* Flip arrow up when open */
    }

    /* Profile segment alignment on desktop */
    @media (min-width: 992px) {
      .navbar-nav.ml-auto {
        margin-left: auto !important; /* Push to the far right */
        margin-right: 20px !important; /* Add some spacing from the edge */
      }
      .navbar-nav.ml-auto .nav-item {
        margin-left: 15px; /* Add spacing between profile items if more are added */
      }
    }

    /* Mobile and Tablet View */
    @media (max-width: 991px) {
      .navbar-collapse {
        background-color: #f3e8d6 !important; /* Light beige */
        padding: 15px; /* More padding */
        border-radius: 8px; /* Softer corners */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
      }
      .navbar-nav .nav-item {
        background-color: #ede4d3 !important; /* Slightly darker beige */
        margin: 8px 0; /* Spacing between items */
        border-radius: 5px; /* Softer corners */
        transition: background-color 0.3s ease;
      }
      .navbar-nav .nav-item:hover {
        background-color: #e2d9c8 !important; /* Darker on hover */
      }
      .navbar-nav .nav-link {
        padding: 12px 20px !important; /* Larger touch targets */
        color: #3e2723 !important; /* Consistent text color */
      }
      .navbar-nav .nav-link:hover {
        color: #f59e0b !important; /* Hover effect */
      }
      .dropdown-menu {
        position: static !important; /* Full width on mobile */
        width: 100%;
        border: none;
        box-shadow: none;
        z-index: 1060 !important; /* Still high */
        background-color: #f3e8d6 !important; /* Match collapse */
        opacity: 1; /* Always visible when shown */
        transform: none; /* No transform on mobile */
        transition: none; /* No transition on mobile */
      }
      .dropdown-item {
        padding: 10px 20px; /* Consistent padding */
      }
      .dropdown-toggle::after {
        float: right; /* Align arrow to the right on mobile */
      }
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-2 py-0 fixed-top animate__animated animate__fadeInDown">
    <a class="navbar-brand" href="../public/auctions.php">
      <img width="54px" height="54px" src="../images/logo/logo1.png" alt="Logo">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>

  <!-- Navbar Animation Scripts -->
  <script>
    // 1. Navbar entrance animation
    gsap.from('.navbar', {
      duration: 0.8,
      y: -60,
      opacity: 0,
      ease: 'power3.out',
      delay: 0.2
    });

    // 2. Navbar box-shadow transition on scroll
    $(window).scroll(function() {
      if ($(this).scrollTop() > 50) {
        $('.navbar').addClass('scrolled');
      } else {
        $('.navbar').removeClass('scrolled');
      }
    });

    // 3. GSAP animation for navbar items on page load
    gsap.from('.navbar-nav .nav-item', {
      duration: 0.8,
      y: -20,
      opacity: 0,
      stagger: 0.15,
      ease: 'power3.out',
      delay: 0.6
    });

    // 4. Animate collapse menu with GSAP
    const $collapse = $('#navbarNav');
    $collapse.on('show.bs.collapse', function() {
      gsap.fromTo(this, 
        { opacity: 0, y: -30, scale: 0.95 }, 
        { 
          duration: 0.5, 
          opacity: 1, 
          y: 0, 
          scale: 1,
          ease: 'power3.out' 
        }
      );
    });
    $collapse.on('hide.bs.collapse', function() {
      gsap.to(this, { 
        duration: 0.4, 
        opacity: 0, 
        y: -30, 
        scale: 0.95,
        ease: 'power3.in' 
      });
    });

    // 5. GSAP animation for dropdown menu
    $('.dropdown').each(function() {
      const $dropdown = $(this);
      const $menu = $dropdown.find('.dropdown-menu');
      
      $dropdown.on('show.bs.dropdown', function() {
        gsap.fromTo($menu, 
          { opacity: 0, y: -10, scale: 0.95 }, 
          { 
            duration: 0.4, 
            opacity: 1, 
            y: 0, 
            scale: 1, 
            ease: 'power2.out' 
          }
        );
        gsap.from($menu.find('.dropdown-item'), {
          duration: 0.3,
          opacity: 0,
          y: 10,
          stagger: 0.1,
          ease: 'power2.out',
          delay: 0.1
        });
      });
      
      $dropdown.on('hide.bs.dropdown', function() {
        gsap.to($menu, { 
          duration: 0.3, 
          opacity: 0, 
          y: -10, 
          scale: 0.95,
          ease: 'power2.in' 
        });
      });
    });

    // 6. GSAP hover effect for nav links (desktop only)
    if (window.innerWidth > 991) {
      document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
          if (!link.classList.contains('dropdown-toggle') || !$(link).parent().hasClass('show')) {
            gsap.to(link, { 
              duration: 0.4, 
              scale: 1.05, 
              color: '#f59e0b', 
              ease: 'power2.out' 
            });
          }
        });
        link.addEventListener('mouseleave', () => {
          if (!link.classList.contains('dropdown-toggle') || !$(link).parent().hasClass('show')) {
            gsap.to(link, { 
              duration: 0.4, 
              scale: 1, 
              color: '#3e2723', 
              ease: 'power2.out' 
            });
          }
        });
      });
    }
  </script>
</body>
</html>