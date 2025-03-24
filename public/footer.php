<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../assets/link.html"); ?>
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      background-color: #f4e1d2 !important; /* Sandy beige from navbar */
    }
    footer {
      margin-top: auto;
      background-color: #3e2723; /* Dark earthy brown */
      color: #ffffff; /* White text */
    }
    .footer-heading {
      color: #689f38 !important; /* Lime green for headings */
      transition: color 0.3s ease;
    }
    .footer-link {
      color: #ffffff !important; /* White links */
      text-decoration: none !important;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .footer-link:hover {
      color: #ffca28 !important; /* Golden yellow on hover */
      transform: scale(1.05); /* Slight growth effect */
    }
    .footer-text {
      color: #ffffff !important; /* White paragraph text */
      transition: color 0.3s ease;
    }
    .footer-copyright {
      color: #ffffff !important;
    }
    .footer-copyright a {
      color: #689f38 !important; /* Lime green for copyright link */
      transition: color 0.3s ease;
    }
    .footer-copyright a:hover {
      color: #ffca28 !important; /* Golden yellow on hover */
    }
    hr {
      border-color: rgba(255, 255, 255, 0.2); /* Subtle white line */
    }
    /* Icon styling for Contact section */
    .fas {
      color: #689f38; /* Lime green icons */
      transition: color 0.3s ease;
    }
    .footer-link:hover .fas {
      color: #ffca28; /* Golden yellow on hover */
    }
  </style>
</head>
<body>
  <div class="d-flex flex-column flex-grow-1">
    <!-- Content goes here -->
  </div>

  <!-- Footer -->
  <footer class="container-fluid pt-5 pb-4 px-5 animate__animated animate__fadeIn">
    <div class="text-left text-md-left">
      <div class="row justify-content-start">
        <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold footer-heading">e-Agri Auction</h5>
          <p class="footer-text">E-Agri Auction is an online platform that connects farmers directly with wholesalers through e-auctions. This helps farmers get better prices for their produce by bypassing middlemen, while wholesalers can easily access fresh, quality products.</p>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold footer-heading">Products</h5>
          <p><a href="./auctions.php" class="footer-link">Auctions</a></p>
          <p><a href="./add-auction.php" class="footer-link">Add Auctions</a></p>
          <p><a href="./manage-auction.php" class="footer-link">Manage Auction</a></p>
        </div>
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold footer-heading">Useful links</h5>
          <p><a href="./profile.php" class="footer-link">Your Account</a></p>
          <p><a href="./revisited-auctions.php" class="footer-link">Your Participated Auctions</a></p>
          <p><a href="#" class="footer-link">Help</a></p>
        </div>
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold footer-heading">Contact</h5>
          <p><i class="fas fa-home mr-3"></i> <a class="footer-link" href="https://www.google.com/maps/dir/?api=1&destination=M6H6%2B5F9%2C+Somavarapatti%2C+Tamil+Nadu">Tiruppur, Tamil Nadu, India - 642205</a></p>
          <p><i class="fas fa-envelope mr-3"></i> <a class="footer-link" href="mailto:nishanthpechimuthu@gmail.com">nishanthpechimuthu@gmail.com</a></p>
          <p><i class="fas fa-phone mr-3"></i> <a class="footer-link" href="tel:+918015864344">+91 8015864344</a></p>
        </div>
      </div>
      <hr class="mb-4">
      <div class="row justify-content-start align-items-center">
        <div class="col-md-7 col-lg-8">
          <p class="footer-copyright">Copyright © 2024 - 2025 All rights reserved by:
            <a href="mailto:nishanthpechimuthu@gmail.com" class="footer-link">
              <strong>Nishanth Pechimuthu</strong>
            </a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Footer Animation Scripts -->
  <script>
    // 1. GSAP animation for footer sections on page load
    gsap.from('.footer-heading', {
      duration: 1,
      y: 20,
      opacity: 0,
      stagger: 0.2,
      ease: 'power2.out',
      delay: 0.5
    });
    gsap.from('.footer-link, .footer-text', {
      duration: 1,
      y: 20,
      opacity: 0,
      stagger: 0.1,
      ease: 'power2.out',
      delay: 0.7
    });

    // 2. GSAP hover effect for footer links (desktop only)
    if (window.innerWidth > 991) { // Only on desktop (above lg breakpoint)
      document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
          gsap.to(link, { 
            duration: 0.3, 
            scale: 1.05, 
            color: '#ffca28', 
            ease: 'power1.out' 
          });
        });
        link.addEventListener('mouseleave', () => {
          gsap.to(link, { 
            duration: 0.3, 
            scale: 1, 
            color: '#ffffff', 
            ease: 'power1.out' 
          });
        });
      });
    }
  </script>
</body>
</html>