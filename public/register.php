<?php
ob_start();
session_start(); // Start the session
include './header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $conpassword = $_POST['conpassword'];
  if ($password == $conpassword) {
    $registrationResult = register($username, $email, $password);
  } else {
    echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert" data-bs-dismiss="alert"
           aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Password and Confirm Password do not match.
        </p>
    ';
  }

  if ($registrationResult === true) {
      // Create a hidden form and submit it via POST
      echo '<form id="redirectForm" action="activate-user.php" method="POST">
              <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
            </form>';
      
      echo '<script type="text/javascript">
              document.getElementById("redirectForm").submit();
            </script>';
  } else {
    $error = $registrationResult; // Capture the error message
  }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include_once("../assets/link.html"); ?>
  <link rel="stylesheet" href="../assets/style.css" type="text/css" media="all" />
  <title>Register</title>
  <style>
    body {
      background: #f4e1d2 !important; /* Sandy beige */
      color: #3e2723; /* Dark brown */
      overflow-y: hidden; /* Hide scrollbar initially */
    }
    .box-area {
      transition: box-shadow 0.7s ease; /* Slow transition */
      width: 100%;
      max-width: 800px; /* Fixed width */
      opacity: 0; /* Start invisible */
    }
    .box-area:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .left-box {
      background: linear-gradient(45deg, rgba(204,255,0,1) 0%, rgba(1,255,202,1) 100%);
    }
    .left-box p {
      color: #3e2723; /* Dark brown */
      transition: color 0.7s ease;
    }
    .left-box p:hover {
      color: #689f38; /* Lime green */
    }
    .left-box small {
      color: #3e2723; /* Dark brown */
    }
    .right-box .header-text h2 {
      color: #689f38; /* Lime green */
      transition: color 0.7s ease;
    }
    .right-box .header-text h2:hover {
      color: #ffca28; /* Golden yellow */
    }
    .right-box .header-text p {
      color: #3e2723; /* Dark brown */
    }
    .form-control {
      transition: border-color 0.7s ease, box-shadow 0.7s ease;
    }
    .form-control:focus {
      border-color: #689f38; /* Lime green */
      box-shadow: 0 0 5px rgba(104, 159, 56, 0.5);
    }
    .btn {
      background: linear-gradient(45deg, rgba(204,255,0,1) 0%, rgba(1,255,202,1) 100%);
      color: #3e2723; /* Dark brown text */
      transition: transform 0.7s ease, background 0.7s ease, box-shadow 0.7s ease;
    }
    .btn:hover {
      transform: scale(1.05);
      background: linear-gradient(45deg, rgba(204,255,0,1) 20%, rgba(1,255,202,1) 80%);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      color: #3e2723;
    }
    .row small a {
      color: #689f38; /* Lime green */
      transition: color 0.7s ease;
    }
    .row small a:hover {
      color: #ffca28; /* Golden yellow */
    }
    .alert {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1000;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="row border rounded-5 p-3 bg-white shadow box-area">
    <div class="col-md-6 right-box">
      <div class="row align-items-center">
        <div class="header-text mb-4">
          <h2>Welcome, You</h2>
          <p>We are happy to have you.</p>
        </div>
        <form method="post" accept-charset="utf-8">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address">
          </div>
          <div class="input-group mb-3">
            <input name="username" type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Username">
          </div>
          <div class="input-group mb-3">
            <input name="password" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
          </div>
          <div class="input-group mb-1">
            <input name="conpassword" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Confirm Password">
          </div>
          <div class="input-group mb-5 d-flex justify-content-between">
          </div>
          <div class="input-group mb-3">
            <input type="submit" value="Register" class="text-white fw-bold btn btn-lg w-100 fs-6">
          </div>
        </form>
        <div class="row">
          <small>Already have an account? <a href="login.php">Log in</a></small>
        </div>
      </div>
    </div>

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
  </div>
</div>

<script>
  // GSAP animations
  document.addEventListener('DOMContentLoaded', () => {
    // Animate container
    gsap.fromTo('.box-area', 
      { y: 50, opacity: 0 },
      { 
        duration: 2, // Very slow
        y: 0,
        opacity: 1,
        ease: 'power4.out', // Ultra-smooth
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
      ease: 'power4.out',
      delay: 0.8
    });
    gsap.from('.left-box p', {
      duration: 2.5, // Very slow
      opacity: 0,
      y: 20,
      ease: 'power4.out',
      delay: 1.1
    });
    gsap.from('.left-box small', {
      duration: 2.5, // Very slow
      opacity: 0,
      y: 20,
      ease: 'power4.out',
      delay: 1.5
    });
    gsap.from('.right-box .header-text h2', {
      duration: 2.5, // Very slow
      opacity: 0,
      y: 20,
      ease: 'power4.out',
      delay: 1
    });
    gsap.from('.right-box .header-text p', {
      duration: 2.5, // Very slow
      opacity: 0,
      y: 20,
      ease: 'power4.out',
      delay: 1.4
    });
    gsap.from('.right-box .form-control', {
      duration: 2, // Very slow
      opacity: 0,
      scale: 0.98,
      stagger: 0.3, // Larger stagger
      ease: 'power4.out',
      delay: 1.8
    });
    gsap.from('.right-box .btn', {
      duration: 2.5, // Very slow
      opacity: 0,
      scale: 0.9,
      ease: 'power4.out',
      delay: 2.4
    });
    gsap.from('.right-box .row small', {
      duration: 2.5, // Very slow
      opacity: 0,
      y: 20,
      ease: 'power4.out',
      delay: 2.8
    });

    // GSAP hover effects (desktop only)
    if (window.innerWidth > 991) {
      document.querySelector('.box-area').addEventListener('mouseenter', () => {
        gsap.to('.box-area', { 
          duration: 0.7, 
          boxShadow: '0 8px 20px rgba(0, 0, 0, 0.2)', 
          ease: 'power1.out' 
        });
      });
      document.querySelector('.box-area').addEventListener('mouseleave', () => {
        gsap.to('.box-area', { 
          duration: 0.7, 
          boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)', 
          ease: 'power1.out' 
        });
      });
      document.querySelector('.btn').addEventListener('mouseenter', () => {
        gsap.to('.btn', { 
          duration: 0.7, 
          scale: 1.05, 
          ease: 'power1.out' 
        });
      });
      document.querySelector('.btn').addEventListener('mouseleave', () => {
        gsap.to('.btn', { 
          duration: 0.7, 
          scale: 1, 
          ease: 'power1.out' 
        });
      });
    }
  });
</script>
</body>
</html>