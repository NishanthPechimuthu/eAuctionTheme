<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
      body {
        background-color: #f4e1d2 !important;
        margin: 0; /* Ensure no default body margin */
      }
      /* Ensure the carousel and its parent containers take full height */
      .carousel.slide {
        height: 100vh; /* Full height for desktop */
        background: transparent !important; /* Prevent black background */
      }
      .carousel-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 100vh; /* Full height for desktop */
        background: transparent !important; /* Prevent black background */
        padding: 0; /* Remove any padding */
        margin: 0; /* Remove any margins */
      }
      .carousel-inner {
        position: relative;
        z-index: 1;
        height: 100%; /* Ensure it fills the carousel */
        background: transparent !important; /* Prevent black background */
      }
      /* Fixed Overlay: Reduced Opacity */
      .carousel-image-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 10%; /* Kept from previous adjustment */
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.05) 100%); /* Reduced opacity from 0.1 to 0.05 */
        z-index: 2;
      }
      .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 15px;
        transition: transform 0.3s ease, opacity 0.3s ease;
        z-index: 10;
        position: relative;
        pointer-events: auto;
      }
      .carousel-caption h5 {
        color: #ffffff;
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
      }
      .carousel-caption p {
        color: #ffffff;
        font-size: 1.2rem;
        line-height: 1.5;
      }
      .btn-container {
        z-index: 11;
        position: relative;
      }
      .btn-warning {
        background-color: #ffca28 !important;
        color: #3e2723 !important;
        border: none;
        padding: 0.75rem 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        z-index: 12;
        position: relative;
      }
      .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        background-color: #ffd54f !important;
      }
      /* Ensure Image Fills the Container */
      .carousel-item img {
        object-fit: cover;
        height: 100%; /* Fill the wrapper's height */
        width: 100%;
        z-index: 1;
        display: block; /* Ensure the image behaves as a block element */
      }
      .carousel-control-prev,
      .carousel-control-next {
        width: 5%;
        opacity: 0.7;
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 10;
      }
      .carousel-control-prev:hover,
      .carousel-control-next:hover {
        opacity: 1;
        transform: scale(1.1);
      }
      .carousel-control-prev::before,
      .carousel-control-next::before {
        font-family: "bootstrap-icons";
        color: #689f38;
        font-size: 2rem;
      }
      .carousel-control-prev::before {
        content: "\f284";
      }
      .carousel-control-next::before {
        content: "\f285";
      }
      /* Mobile View Adjustments */
      @media (max-width: 768px) {
        .carousel.slide {
          height: 100vh; /* Full height for mobile */
        }
        .carousel-image-wrapper {
          height: 100vh; /* Full height for mobile to ensure the image fills the space */
          max-height: none; /* Remove max-height constraint */
          background: transparent !important; /* Prevent black background */
        }
        .carousel-inner {
          height: 100%; /* Ensure it fills the carousel */
        }
        .carousel-item img {
          height: 100%; /* Fill the wrapper's height */
          min-height: 100vh; /* Match the wrapper's height */
        }
        .carousel-image-wrapper::after {
          height: 10%; /* Kept from previous adjustment */
          background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.025) 100%); /* Reduced opacity from 0.05 to 0.025 */
        }
        .carousel-caption {
          padding: 1rem;
          width: 90%;
        }
        .carousel-caption h5 {
          font-size: 1.5rem;
          margin-bottom: 0.5rem;
        }
        .carousel-caption p {
          font-size: 1rem;
          margin-bottom: 0.5rem;
        }
        .btn-warning {
          padding: 0.375rem 0.75rem;
          font-size: 0.875rem;
        }
      }
    </style>
</head>
<body>
<?php
include("./header.php");
$heroes = getAllHeroes();
?>

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($heroes as $index => $hero): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="carousel-image-wrapper position-relative">
                    <img src="./images/heroes/<?= htmlspecialchars($hero['heroImg']) ?>" class="d-block img-fluid" alt="<?= htmlspecialchars($hero['heroTitle']) ?>">
                    <div class="carousel-caption d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle w-100 text-center">
                        <div>
                            <h5><?= htmlspecialchars($hero['heroTitle']) ?></h5>
                            <p><?= htmlspecialchars($hero['heroMessage']) ?></p>
                            <div class="btn-container">
                                <a href="hero-content.php?id=<?= base64_encode($hero['heroId']) ?>" class="btn btn-warning mt-3">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="visually-hidden">Next</span>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleIndicators'), {
      interval: 20000,
      wrap: true
    });

    gsap.utils.toArray('.carousel-item').forEach((item, index) => {
      if (item.classList.contains('active')) {
        gsap.from(item.querySelector('.carousel-image-wrapper img'), {
          duration: 1,
          opacity: 0,
          scale: 1.05,
          ease: 'power2.out',
          delay: 0.5
        });
        gsap.from(item.querySelector('.carousel-caption h5'), {
          duration: 1,
          opacity: 0,
          y: 20,
          ease: 'power2.out',
          delay: 0.7
        });
        gsap.from(item.querySelector('.carousel-caption p'), {
          duration: 1,
          opacity: 0,
          y: 20,
          ease: 'power2.out',
          delay: 0.9
        });
        gsap.from(item.querySelector('.btn-warning'), {
          duration: 1,
          opacity: 0,
          y: 20,
          ease: 'power2.out',
          delay: 1.1
        });
      }
    });
  });
</script>
</body>
</html>