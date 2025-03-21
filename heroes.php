<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
      body {
        background-color: #f4e1d2 !important; /* Sandy beige */
      }
      /* Carousel Styles */
      .carousel-image-wrapper {
        position: relative;
        overflow: hidden;
        max-height: 100vh; /* Full viewport height */
      }
      .carousel-image-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
      }
      .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 15px;
        backdrop-filter: blur(5px);
        transition: transform 0.3s ease, opacity 0.3s ease;
      }
      .carousel-caption h5 {
        color: #ffffff; /* White */
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
      }
      .carousel-caption p {
        color: #ffffff; /* White */
        font-size: 1.2rem;
        line-height: 1.5;
      }
      .btn-warning {
        background-color: #ffca28 !important; /* Golden yellow */
        color: #3e2723 !important; /* Dark brown text */
        border: none;
        padding: 0.75rem 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        background-color: #ffd54f !important; /* Lighter yellow */
      }
      /* Custom Prev/Next Icons */
      .carousel-control-prev,
      .carousel-control-next {
        width: 5%;
        opacity: 0.7;
        transition: opacity 0.3s ease, transform 0.3s ease;
      }
      .carousel-control-prev:hover,
      .carousel-control-next:hover {
        opacity: 1;
        transform: scale(1.1);
      }
      .carousel-control-prev-icon,
      .carousel-control-next-icon {
        display: none; /* Hide default icons */
      }
      .carousel-control-prev::before,
      .carousel-control-next::before {
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #689f38; /* Lime green */
        font-size: 2rem;
      }
      .carousel-control-prev::before {
        content: "\f053"; /* fa-chevron-left */
      }
      .carousel-control-next::before {
        content: "\f054"; /* fa-chevron-right */
      }
      /* Responsive Adjustments */
      @media (max-width: 768px) {
        .carousel-image-wrapper {
          max-height: 60vh;
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

<!-- Carousel Structure -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <!-- Carousel Inner Content -->
    <div class="carousel-inner">
        <?php foreach ($heroes as $index => $hero): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> animate__animated animate__fadeIn">
                <div class="carousel-image-wrapper position-relative overflow-hidden">
                    <img src="./images/heroes/<?= htmlspecialchars($hero['heroImg']) ?>" class="d-block w-100 img-fluid" alt="<?= htmlspecialchars($hero['heroTitle']) ?>" style="object-fit: cover; height: 100vh;">
                    <div class="carousel-caption d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle w-100 text-center">
                        <div>
                            <h5 class="text-white"><?= htmlspecialchars($hero['heroTitle']) ?></h5>
                            <p class="text-white"><?= htmlspecialchars($hero['heroMessage']) ?></p>
                            <p><a href="hero-content.php?id=<?= base64_encode($hero['heroId']) ?>" class="btn btn-warning mt-3">Learn More</a></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Custom Prev/Next Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize the carousel
    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleIndicators'), {
      interval: 5000, // 5 seconds interval
      wrap: true // Ensure the carousel cycles continuously
    });

    // GSAP animations for carousel items
    gsap.utils.toArray('.carousel-item').forEach((item, index) => {
      if (item.classList.contains('active')) {
        gsap.from(item.querySelector('.carousel-image-wrapper img'), {
          duration: 1,
          opacity: 0,
          scale: 1.1,
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

    // GSAP hover effects for button and controls (desktop only)
    if (window.innerWidth > 991) {
      document.querySelectorAll('.btn-warning').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
          gsap.to(btn, { 
            duration: 0.3, 
            scale: 1.05, 
            ease: 'power1.out' 
          });
        });
        btn.addEventListener('mouseleave', () => {
          gsap.to(btn, { 
            duration: 0.3, 
            scale: 1, 
            ease: 'power1.out' 
          });
        });
      });
      document.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(control => {
        control.addEventListener('mouseenter', () => {
          gsap.to(control, { 
            duration: 0.3, 
            scale: 1.1, 
            opacity: 1, 
            ease: 'power1.out' 
          });
        });
        control.addEventListener('mouseleave', () => {
          gsap.to(control, { 
            duration: 0.3, 
            scale: 1, 
            opacity: 0.7, 
            ease: 'power1.out' 
          });
        });
      });
    }
  });
</script>
</body>
</html>