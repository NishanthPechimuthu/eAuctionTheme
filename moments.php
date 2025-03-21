<?php
// Database connection
include("header.php");
$query = "SELECT * FROM moments WHERE momentStatus = 'activate'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$moments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <?php include("navbar.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masonry Grid</title>
  <link rel="icon" type="image/x-icon" href="./images/logo/favicon.ico"> 
  <style>
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
    }
    .layout-container {
      width: min(1000px, 100%);
      margin: 0 auto;
      columns: 3 300px;
      column-gap: 1em;
    }
    img {
      display: block;
      margin-bottom: 1em;
      width: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease; /* Hover effect */
    }
    /* Adjust margin-top to clear reduced navbar */
    .container.py-5.mt-5 {
      margin-top: 60px; /* Adjusted for smaller navbar */
    }
    /* Reduce navbar height for this page */
    .navbar {
      padding: 0.25rem 1rem !important; /* Reduced padding */
      max-height: 64px !important;
      display: flex; /* Ensure flexbox for alignment */
      align-items: center; /* Center items vertically */
    }
    .nav-link {
      padding: 0.5rem 1rem !important; /* Adjusted padding */
    }
    .navbar-brand img{
      margin: 0;
    }
    .navbar-toggler {
      padding: 0.25rem 0.5rem !important; /* Smaller toggler */
    }
    /* Remove shadow from navbar */
    .navbar.shadow-sm {
      box-shadow: none !important; /* Override shadow-sm */
    }
  </style>
  <?php include("./assets/link.html"); ?>
  <link rel="stylesheet" href="./assets/css/home-style.css">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>

  <div class="container py-5 mt-5">
    <div class="layout-container">
      <?php foreach ($moments as $moment): ?>
        <img src="./images/moments/<?= htmlspecialchars($moment['momentImg']) ?>" alt="" class="animate__animated animate__fadeInUp">
      <?php endforeach; ?>
    </div>
  </div>

  <?php include("./footer.php"); ?>

  <!-- Animation Scripts -->
  <script>
    // GSAP animation for images on load
    gsap.from('.layout-container img', {
      duration: 1,
      y: 30,
      opacity: 0,
      stagger: 0.1, // Stagger each image
      ease: 'power2.out',
      delay: 0.5
    });

    // GSAP hover effect for images (desktop only)
    if ($(window).width() > 991) {
      $('.layout-container img').hover(
        function() {
          gsap.to(this, { 
            duration: 0.3, 
            scale: 1.03, 
            ease: 'power1.out' 
          });
        },
        function() {
          gsap.to(this, { 
            duration: 0.3, 
            scale: 1, 
            ease: 'power1.out' 
          });
        }
      );
    }
  </script>
</body>
</html>