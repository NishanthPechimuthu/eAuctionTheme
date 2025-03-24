<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");
isAuthenticated();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php include_once("../assets/link.html"); ?>
  <title>Settings</title>
  <style>
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
      color: #3e2723; /* Dark brown */
      font-family: 'Arial', sans-serif; /* Cleaner typography */
    }
    .container {
      margin-top: 80px; /* Space for fixed navbar */
      padding-bottom: 40px;
    }
    .card-main {
      background-color: #ffffff; /* White */
      border-radius: 15px; /* Rounded corners */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden; /* Contain rounded corners */
      transition: box-shadow 0.3s ease;
    }
    .card-main:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .card-header {
      background: linear-gradient(45deg, #689f38, #8bc34a); /* Lime green gradient */
      color: #ffffff;
      font-size: 1.5rem;
      padding: 15px;
      border-bottom: none;
    }
    .card-body {
      padding: 20px;
    }
    .settings-table {
      width: 100%;
      max-width: 400px; /* Limit width for better readability */
      margin: 0 auto; /* Center table */
    }
    .settings-table tr {
      transition: background-color 0.3s ease;
    }
    .settings-table tr:hover {
      background-color: #f4e1d2; /* Sandy beige on hover */
    }
    .settings-table td {
      padding: 15px 0; /* Vertical spacing */
      border-bottom: 1px solid rgba(104, 159, 56, 0.2); /* Subtle green line */
    }
    .settings-table .d-flex {
      align-items: center;
    }
    .settings-table i {
      color: #689f38; /* Lime green icons */
      font-size: 1.2rem;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .settings-table tr:hover i {
      color: #ffca28; /* Golden yellow on hover */
      transform: scale(1.1); /* Slight growth */
    }
    .settings-table a {
      color: #3e2723; /* Dark brown */
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .settings-table a:hover {
      color: #ffca28; /* Golden yellow */
      transform: translateX(5px); /* Subtle shift right */
    }
    @media (max-width: 576px) {
      .card-header {
        font-size: 1.25rem; /* Smaller on mobile */
      }
      .settings-table td {
        padding: 10px 0; /* Reduced spacing */
      }
      .settings-table i {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card card-main mb-4">
      <div class="card-header">
        <i class="bi bi-person-circle"></i> Settings
      </div>
      <div class="card-body">
        <table class="settings-table">
          <tr>
            <td class="d-flex">
              <i class="bi bi-lock"></i>
              <a href="change-password.php" class="ms-2">Change Password</a>
            </td>
          </tr>
          <tr>
            <td class="d-flex">
              <i class="bi bi-person"></i>
              <a href="update-profile.php" class="ms-2">Update Profile</a>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
  <script>
    // GSAP animations
    document.addEventListener('DOMContentLoaded', () => {
      // Animate the main card container
      gsap.from('.card-main', {
        duration: 2,
        opacity: 0,
        y: 50,
        ease: 'power4.out',
        delay: 0.5
      });

      // Animate table rows
      gsap.from('.settings-table tr', {
        duration: 2,
        opacity: 0,
        y: 20,
        stagger: 0.2,
        ease: 'power4.out',
        delay: 0.7
      });

      // Hover effect for table rows (desktop only)
      if (window.innerWidth > 991) {
        document.querySelectorAll('.settings-table tr').forEach(row => {
          row.addEventListener('mouseenter', () => {
            gsap.to(row, {
              duration: 0.3,
              scale: 1.02,
              backgroundColor: '#f4e1d2',
              ease: 'power1.out'
            });
          });
          row.addEventListener('mouseleave', () => {
            gsap.to(row, {
              duration: 0.3,
              scale: 1,
              backgroundColor: 'transparent',
              ease: 'power1.out'
            });
          });
        });
      }
    });
  </script>
</body>
</html>
<?php
  include_once("./menu.php");
  include_once("./footer.php");
  ob_end_flush();
?>