<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./images/logo/favicon.ico"> 
  <?php include_once("./assets/link.html"); ?>
  <style>
    /* Page background (sandalwood/sandy beige) */
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
    }

    /* Navbar styling */
    .navbar {
      background-color: #ffffff !important; /* White */
      transition: all 0.3s ease-in-out;
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
      background-color: #e57373 !important; /* Warm coral red (new base color) */
      color: #ffffff !important; /* White text for contrast */
      border-color: #e57373 !important;
      transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .btn-warning:hover {
      background-color: #ff8a80 !important; /* Lighter coral (hover) */
      transform: scale(1.05);
    }

    /* Smooth collapse transition */
    .navbar-collapse {
      overflow: hidden;
    }
    .navbar-collapse.collapsing {
      transition: opacity 0.3s ease-in-out;
    }
    .navbar-collapse.show {
      opacity: 1;
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-2 py-0 fixed-top animate__animated animate__fadeInDown">
    <a class="navbar-brand" href="index.php">
      <img width="54px" height="54px" src="./images/logo/logo1.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="./index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./index.php#about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./index.php#contact">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./moments.php">Moments</a>
        </li>
      </ul>
      <!-- Align Login and Register buttons to the right -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link btn btn-primary text-white m-1 p-2 fw-bold" href="./public/login.php"> Login </a>
        </li>
        <li class="nav-item">
          <a style="background: #E57373 !important; color: #ffff !important;" class="nav-link btn btn-warning text-white m-1 p-2 fw-bold" href="./public/register.php">Register</a>
        </li>
      </ul>
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
    if ($(window).width() > 991) { // Only on desktop (above lg breakpoint)
      $('.nav-link').hover(
        function() {
          gsap.to(this, { 
            duration: 0.3, 
            scale: 1.1, 
            color: '#ffca28', 
            ease: 'power1.out' 
          });
        },
        function() {
          gsap.to(this, { 
            duration: 0.3, 
            scale: 1, 
            color: '#3e2723', 
            ease: 'power1.out' 
          });
        }
      );
    }
  </script>
</body>
</html>