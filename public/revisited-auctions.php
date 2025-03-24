<?php
ob_start();
session_start(); // Start the session
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();

$user_id = $_SESSION['userId'] ?? null;

$auctions = getAuctionsParticipate($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Revisited Auctions</title>
  <?php include_once("../assets/link.html"); ?>
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
    .auction-card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .auction-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    .auction-card img {
      height: 180px; /* Fixed height */
      object-fit: cover;
      width: 100%;
      border-bottom: 2px solid #689f38; /* Green accent */
      transition: transform 0.3s ease;
    }
    .auction-card:hover img {
      transform: scale(1.05); /* Subtle zoom */
    }
    .auction-card .card-body {
      padding: 15px;
    }
    .auction-card .card-title {
      color: #689f38; /* Lime green */
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 10px;
      transition: color 0.3s ease;
    }
    .auction-card:hover .card-title {
      color: #ffca28; /* Golden yellow */
    }
    .auction-card .table {
      margin-bottom: 15px;
      font-size: 0.9rem;
    }
    .auction-card .table td {
      padding: 5px 0;
      vertical-align: middle;
      color: #3e2723;
    }
    .auction-card .table i {
      color: #689f38; /* Lime green icons */
      transition: color 0.3s ease;
    }
    .auction-card:hover .table i {
      color: #ffca28; /* Golden yellow on hover */
    }
    .badge {
      background-color: #3e2723; /* Dark brown */
      color: #ffffff;
      font-size: 0.85rem;
      padding: 5px 10px;
      border-radius: 12px;
      transition: background-color 0.3s ease;
    }
    .auction-card:hover .badge {
      background-color: #ffca28; /* Golden yellow */
    }
    .btn-primary {
      background: linear-gradient(45deg, #689f38, #8bc34a); /* Green gradient */
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      color: #ffffff;
      transition: transform 0.3s ease, background 0.3s ease;
    }
    .btn-primary:hover {
      background: linear-gradient(45deg, #8bc34a, #a4d007); /* Lighter gradient */
      transform: scale(1.05);
      color: #ffffff;
    }
    .no-auctions {
      color: #3e2723;
      font-size: 1.2rem;
      text-align: center;
      padding: 20px;
    }
    @media (max-width: 767px) {
      .auction-card img {
        height: 150px; /* Smaller on mobile */
      }
      .auction-card .card-title {
        font-size: 1.1rem;
      }
      .auction-card .table {
        font-size: 0.8rem;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card card-main mb-4">
      <div class="card-header">
        <i class="fa fa-user-check"></i> Participated Auctions
      </div>
      <div class="card-body">
        <?php if (empty($auctions)): ?>
          <p class="no-auctions">No auctions participated in.</p>
        <?php else: ?>
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($auctions as $auction): ?>
              <div class="col mb-4">
                <div class="card auction-card">
                  <div class="position-relative">
                    <span class="badge position-absolute top-0 start-0 m-2">
                      <i class="bi bi-tag"></i> <?= htmlspecialchars(getCategoryById($auction["auctionCategoryId"])); ?>
                    </span>
                    <img src="../images/products/<?= htmlspecialchars($auction['auctionProductImg']) ?>" alt="Product Image" class="card-img-top">
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">
                      <?php 
                        $title = htmlspecialchars($auction['auctionTitle']);
                        echo strlen($title) > 30 ? substr($title, 0, 30) . "..." : $title; 
                      ?>
                    </h5>
                    <table class="table table-sm table-borderless">
                      <tr>
                        <td colspan="2"><i class="fa fa-coins"></i> <b>₹ </b><?= htmlspecialchars($auction['auctionStartPrice']) ?></td>
                        <td colspan="2"><i class="fa fa-line-chart"></i> <b>₹ </b><?= (getHighestBid($auction["auctionId"]) === 0) ? 'Not yet' : htmlspecialchars(getHighestBid($auction["auctionId"])); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><i class="fa fa-balance-scale"></i> <?= htmlspecialchars($auction['auctionProductQuantity']) . " " . htmlspecialchars($auction['auctionProductUnit']) ?></td>
                        <td colspan="2"><i class="fa fa-vial"></i> <?= htmlspecialchars($auction['auctionProductType']) ?></td>
                      </tr>
                      <tr>
                        <td colspan="4"><i class="fa fa-hourglass-end"></i> <?= htmlspecialchars($auction['auctionEndDate']) ?></td>
                      </tr>
                    </table>
                    <a href="bid.php?id=<?= $auction['auctionId'] ?>" class="btn btn-primary">View Winner</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
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

      // Animate individual auction cards (only if they exist)
      gsap.from('.auction-card', {
        duration: 2.5,
        opacity: 0,
        y: 30,
        stagger: 0.2,
        ease: 'power4.out',
        delay: 0.7
      });

      // Hover effect for cards (desktop only)
      if (window.innerWidth > 991) {
        document.querySelectorAll('.auction-card').forEach(card => {
          card.addEventListener('mouseenter', () => {
            gsap.to(card, {
              duration: 0.3,
              scale: 1.02,
              boxShadow: '0 6px 16px rgba(0, 0, 0, 0.15)',
              ease: 'power1.out'
            });
          });
          card.addEventListener('mouseleave', () => {
            gsap.to(card, {
              duration: 0.3,
              scale: 1,
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.1)',
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