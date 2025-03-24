<?php
include_once("header.php");
if (isset($_SESSION["userId"]) && $_SESSION["userId"] != NULL) {
  header("Location: auctions.php");
  exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    header("Location: auctions.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../images/logo/favicon.ico"> 
  <?php include_once("../assets/link.html"); ?>
  <link rel="stylesheet" href="../assets/style.css">
  <title>Login</title>
  <style>
    body {
      background: #f4e1d2 !important; /* Sandy beige */
      color: #3e2723; /* Dark brown */
      overflow-y: hidden; /* Hide vertical scrollbar initially */
    }
    .box-area {
      transition: box-shadow 0.7s ease; /* Very slow transition */
      width: 100%; /* Consistent width */
      max-width: 800px; /* Fixed max-width */
      opacity: 0; /* Start invisible */
    }
    .box-area:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    }
    .left-box {
      background: linear-gradient(45deg, rgba(1,255,202,1) 0%, rgba(204,255,0,1) 100%);
    }
    .left-box p {
      color: #3e2723; /* Dark brown */
      transition: color 0.7s ease; /* Very slow transition */
    }
    .left-box p:hover {
      color: #689f38; /* Lime green */
    }
    .left-box small {
      color: #3e2723; /* Dark brown */
    }
    .right-box .header-text h2 {
      color: #689f38; /* Lime green */
      transition: color 0.7s ease; /* Very slow transition */
    }
    .right-box .header-text h2:hover {
      color: #ffca28; /* Golden yellow */
    }
    .right-box .header-text p {
      color: #3e2723; /* Dark brown */
    }
    .form-control {
      transition: border-color 0.7s ease, box-shadow 0.7s ease; /* Very slow transition */
    }
    .form-control:focus {
      border-color: #689f38; /* Lime green */
      box-shadow: 0 0 5px rgba(104, 159, 56, 0.5); /* Green glow */
    }
    .btn {
      background: linear-gradient(45deg, rgba(204,255,0,1) 0%, rgba(1,255,202,1) 100%);
      color: #3e2723; /* Dark brown text */
      transition: transform 0.7s ease, background 0.7s ease, box-shadow 0.7s ease; /* Very slow transition */
    }
    .btn:hover {
      transform: scale(1.05);
      background: linear-gradient(45deg, rgba(204,255,0,1) 20%, rgba(1,255,202,1) 80%);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      color: #3e2723;
    }
    .forgot a, .row small a {
      color: #689f38; /* Lime green */
      transition: color 0.7s ease; /* Very slow transition */
    }
    .forgot a:hover, .row small a:hover {
      color: #ffca28; /* Golden yellow */
    }
  </style>
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-5 p-3 bg-white shadow box-area">
      <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
        <div class="featured-image mb-3">
          <img src="../images/logo/1.png" class="img-fluid" style="width: 250px;">
        </div>
        <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 800; color: #3e2723;">
          Be Verified
        </p>
        <small class="text-dark text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace;">
          Make the auction genuine.
        </small>
      </div>

      <div class="col-md-6 right-box">
        <div class="row align-items-center">
          <div class="header-text mb-4">
            <h2>Hello, Again</h2>
            <p>We are happy to have you back.</p>
          </div>
          <form method="post" accept-charset="utf-8">
            <div class="input-group mb-3">
              <input name="username" type="text" class="form-control form-control-lg bg-light fs-6" placeholder="username">
            </div>
            <div class="input-group mb-1">
              <input name="password" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
            </div>
            <div class="input-group mb-5 d-flex justify-content-between">
              <div class="form-check">
              </div>
              <div class="forgot">
                <small><a href="forgot-password.php">Forgot Password?</a></small>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="submit" value="Log in" class="text-white fw-bold btn btn-lg w-100 fs-6">
            </div>
          </form>
          <div class="row">
            <small>Don't have account? <a href="register.php">Register</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // GSAP animations
    document.addEventListener('DOMContentLoaded', () => {
      // Animate container
      gsap.fromTo('.box-area', 
        { y: 50, opacity: 0 }, // Start state
        { 
          duration: 2, // Very slow duration
          y: 0,
          opacity: 1,
          ease: 'power4.out', // Ultra-smooth easing
          delay: 0.5,
          onComplete: () => {
            document.body.style.overflowY = 'auto'; // Restore scrolling
          }
        }
      );
      gsap.from('.left-box img', {
        duration: 2, // Very slow
        opacity: 0,
        scale: 0.95,
        ease: 'power4.out', // Ultra-smooth
        delay: 0.8 // Slightly later
      });
      gsap.from('.left-box p', {
        duration: 2.5, // Very slow
        opacity: 0,
        y: 20,
        ease: 'power4.out',
        delay: 1.1 // Increased delay
      });
      gsap.from('.left-box small', {
        duration: 2.5, // Very slow
        opacity: 0,
        y: 20,
        ease: 'power4.out',
        delay: 1.5 // Increased delay
      });
      gsap.from('.right-box .header-text h2', {
        duration: 2.5, // Very slow
        opacity: 0,
        y: 20,
        ease: 'power4.out',
        delay: 1 // Slightly later
      });
      gsap.from('.right-box .header-text p', {
        duration: 2.5, // Very slow
        opacity: 0,
        y: 20,
        ease: 'power4.out',
        delay: 1.4 // Increased delay
      });
      gsap.from('.right-box .form-control', {
        duration: 2, // Very slow
        opacity: 0,
        scale: 0.98,
        stagger: 0.3, // Larger stagger for flow
        ease: 'power4.out',
        delay: 1.8 // Increased delay
      });
      gsap.from('.right-box .btn', {
        duration: 2.5, // Very slow
        opacity: 0,
        scale: 0.9,
        ease: 'power4.out',
        delay: 2.4 // Increased delay
      });
      gsap.from('.right-box .row small', {
        duration: 2.5, // Very slow
        opacity: 0,
        y: 20,
        ease: 'power4.out',
        delay: 2.8 // Increased delay
      });

      // GSAP hover effects (desktop only)
      if (window.innerWidth > 991) {
        document.querySelector('.box-area').addEventListener('mouseenter', () => {
          gsap.to('.box-area', { 
            duration: 0.7, // Very slow
            boxShadow: '0 8px 20px rgba(0, 0, 0, 0.2)', 
            ease: 'power1.out' 
          });
        });
        document.querySelector('.box-area').addEventListener('mouseleave', () => {
          gsap.to('.box-area', { 
            duration: 0.7, // Very slow
            boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)', 
            ease: 'power1.out' 
          });
        });
        document.querySelector('.btn').addEventListener('mouseenter', () => {
          gsap.to('.btn', { 
            duration: 0.7, // Very slow
            scale: 1.05, 
            ease: 'power1.out' 
          });
        });
        document.querySelector('.btn').addEventListener('mouseleave', () => {
          gsap.to('.btn', { 
            duration: 0.7, // Very slow
            scale: 1, 
            ease: 'power1.out' 
          });
        });
      }
    });
  </script>
</body>
</html>