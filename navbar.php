<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./images/logo/favicon.ico"> 
  <?php include_once("./assets/link.html"); ?>
  <style>
    /* Remove default body margin and add padding to account for fixed navbar */
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
      margin: 0 !important; /* Remove default margin to prevent navbar offset */
      padding-top: 54px !important; /* Add padding to prevent content from being hidden under navbar (54px is the navbar height) */
    }

    /* Navbar styling */
    .navbar {
      background-color: #f4e1d2 !important; /* Match page background color (sandy beige) */
      border-bottom: 2px solid #3e2723 !important; /* Brown bottom border */
      transition: box-shadow 0.5s ease-in-out; /* Keep transition for box-shadow only */
    }
    .navbar.scrolled {
      background-color: #f4e1d2 !important; /* Same background color when scrolled */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important; /* Add shadow when scrolled */
    }

    /* Nav links styling with underline animation on hover */
    .nav-link {
      color: #3e2723 !important; /* Dark brown for agri theme */
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
      background-color: #f59e0b; /* Mustard yellow for underline */
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: 100%; /* Underline grows on hover */
    }
    .nav-link:hover {
      color: #f59e0b !important; /* Mustard yellow on hover */
      transform: scale(1.05); /* Slightly smaller scale for subtlety */
    }

    /* Navbar brand (logo) styling */
    .navbar-brand img {
      transition: transform 0.4s ease;
    }
    .navbar-brand:hover img {
      transform: rotate(10deg) scale(1.1); /* Reduced rotation for a subtler effect */
    }

    /* Buttons styling */
    .btn-primary {
      background-color: #2f855a !important; /* Forest green */
      border-color: #2f855a !important;
      transition: background-color 0.4s ease, transform 0.4s ease, box-shadow 0.4s ease;
    }
    .btn-primary:hover {
      background-color: #38a169 !important; /* Lighter forest green */
      transform: scale(1.03); /* Subtler scale */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Add shadow on hover */
    }
    .btn-warning {
      background-color: #c05621 !important; /* Terracotta */
      color: #ffffff !important; /* White text for contrast */
      border-color: #c05621 !important;
      transition: background-color 0.4s ease, transform 0.4s ease, box-shadow 0.4s ease;
    }
    .btn-warning:hover {
      background-color: #d97706 !important; /* Lighter terracotta */
      transform: scale(1.03); /* Subtler scale */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Add shadow on hover */
    }

    /* Smooth collapse transition */
    .navbar-collapse {
      overflow: hidden;
    }
    .navbar-collapse.collapsing {
      transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
    }
    .navbar-collapse.show {
      opacity: 1;
      transform: scale(1);
    }

    /* Ensure navbar stays at the top without offset */
    .navbar.fixed-top {
      top: 0 !important; /* Explicitly set top to 0 */
      position: fixed;
      width: 100%;
      z-index: 1030; /* Ensure it stays above other content */
    }

    /* Mobile and Tablet View: Background color for menu items */
    @media (max-width: 991px) { /* Bootstrap's lg breakpoint (navbar collapses below 992px) */
      .navbar-collapse {
        background-color: #f3e8d6 !important; /* Light beige to match page background */
        padding: 15px; /* Slightly more padding for better spacing */
        border-radius: 8px; /* Softer rounded corners */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
      }
      .navbar-nav .nav-item {
        background-color: #ede4d3 !important; /* Slightly darker beige for contrast */
        margin: 8px 0; /* More spacing between menu items */
        border-radius: 5px; /* Softer rounded corners */
        transition: background-color 0.3s ease;
      }
      .navbar-nav .nav-item:hover {
        background-color: #e2d9c8 !important; /* Slightly darker on hover */
      }
      .navbar-nav .nav-link {
        padding: 12px 20px !important; /* Larger touch targets */
        color: #3e2723 !important; /* Ensure text color remains consistent */
      }
      .navbar-nav .nav-link:hover {
        color: #f59e0b !important; /* Maintain hover effect */
      }
      /* Ensure the Login and Register buttons have proper spacing and styling */
      .navbar-nav.ml-auto .nav-item {
        background-color: transparent !important; /* Remove background for buttons */
      }
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-2 py-0 fixed-top">
    <a class="navbar-brand" href="index.php">
      <img width="54px" height="54px" src="./images/logo/logo1.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
          <a class="nav-link btn btn-primary text-white m-1 p-2 fw-bold" href="./public/login.php">Login</a>
        </li>
        <li class="nav-item">
          <a style="background-color: #c05621 !important; " class="nav-link btn btn-warning text-white m-1 p-2 fw-bold" href="./public/register.php">Register</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Include Bootstrap JS (Bootstrap 5) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    // 4. Animate collapse menu with GSAP (Mobile/Tablet Fix)
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

    // 5. GSAP hover effect for nav links (desktop only)
    if ($(window).width() > 991) {
      $('.nav-link').hover(
        function() {
          gsap.to(this, { 
            duration: 0.4, 
            scale: 1.05, 
            color: '#f59e0b', 
            ease: 'power2.out' 
          });
        },
        function() {
          gsap.to(this, { 
            duration: 0.4, 
            scale: 1, 
            color: '#3e2723', 
            ease: 'power2.out' 
          });
        }
      );
    }
  </script>
</body>
</html>