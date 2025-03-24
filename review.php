<?php
ob_start();
$reviews = getReviews();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f4e1d2 !important; /* Sandy beige */
            font-family: 'Montserrat', sans-serif;
            color: #3e2723; /* Dark brown */
        }
        .section-header {
            text-align: center;
            color: #689f38; /* Lime green */
            margin-bottom: 40px;
            transition: color 0.3s ease;
        }
        .section-header:hover {
            color: #ffca28; /* Golden yellow on hover */
        }
        .section-header::after {
            content: '';
            display: block;
            width: 0;
            height: 3px;
            background: #689f38; /* Lime green */
            margin: 10px auto 0;
            transition: width 0.5s ease;
        }
        .section-header:hover::after {
            width: 100px; /* Animate underline */
        }
        .custom-carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #e3f2fd; /* Light green */
            border-radius: 10px;
            transition: box-shadow 0.3s ease;
        }
        .custom-carousel:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }
        .custom-carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .custom-carousel-item {
            min-width: 100%;
            box-sizing: border-box;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .custom-carousel-item img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #689f38; /* Lime green */
            margin-bottom: 15px;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }
        .custom-carousel-item img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Shadow on hover */
        }
        .bio h2 {
            font-size: 20px;
            color: #689f38; /* Lime green */
            transition: color 0.3s ease;
        }
        .bio h2:hover {
            color: #ffca28; /* Golden yellow on hover */
        }
        .bio h5 {
            color: #3e2723; /* Dark brown */
            transition: color 0.3s ease;
        }
        .bio h5:hover {
            color: #e57373; /* Coral red from previous review hover */
        }
        .content {
            overflow: hidden;
        }
        .content p {
            font-size: 16px;
            color: #3e2723; /* Dark brown */
            text-align: justify;
        }
        .content .fa-quote-left {
            color: #689f38; /* Lime green */
            font-size: 24px;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        .content p:hover .fa-quote-left {
            color: #ffca28; /* Golden yellow on hover */
        }
        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .carousel-controls button {
            background: transparent;
            color: #689f38; /* Lime green */
            border: none;
            border-radius: 50%;
            padding: 15px;
            cursor: pointer;
            font-size: 24px;
            transition: color 0.3s ease, transform 0.3s ease, opacity 0.3s ease;
        }
        .carousel-controls button:hover {
            color: #ffca28; /* Golden yellow */
            transform: scale(1.2); /* Slight zoom */
            opacity: 0.8;
        }
        .carousel-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            color: #689f38; /* Revert to lime green when disabled */
        }
    </style>
</head>
<body>
<section id="testimonials" class="testimonials section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header text-center pb-5 animate__animated animate__fadeIn">
                    <h2>Customer Reviews</h2>
                    <p>Here's what our customers are saying about us.</p>
                </div>
            </div>
        </div>
        <div class="custom-carousel">
            <div class="custom-carousel-inner">
                <?php
                // Dynamically generate carousel items
                foreach ($reviews as $review) {
                    ?>
                    <div class="custom-carousel-item animate__animated animate__fadeInUp">
                        <img src="./images/profiles/<?= htmlspecialchars(getUserImage($review['reviewUserId'])); ?>" alt="User Image">
                        <div class="bio">
                            <h2 class="text-center"><?= getUserFullName($review['reviewUserId']) ?></h2>
                            <h5 class="text-center text-secondary">@<?= getUserName($review['reviewUserId']) ?></h5>
                            <div class="content">
                                <p class="text-center"><i class="fa fa-quote-left"></i><?php echo htmlspecialchars($review['reviewMessage']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- Custom carousel controls -->
            <div class="carousel-controls">
                <button id="prevBtn">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button id="nextBtn">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    const carouselInner = document.querySelector('.custom-carousel-inner');
    const carouselItems = document.querySelectorAll('.custom-carousel-item');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentIndex = 0;

    function updateCarousel() {
        const offset = -currentIndex * 100;
        carouselInner.style.transform = `translateX(${offset}%)`;
    }

    function loopCarousel() {
        if (currentIndex >= carouselItems.length) {
            carouselInner.style.transition = 'none';
            currentIndex = 0;
            updateCarousel();
            setTimeout(() => {
                carouselInner.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }
        if (currentIndex < 0) {
            carouselInner.style.transition = 'none';
            currentIndex = carouselItems.length - 1;
            updateCarousel();
            setTimeout(() => {
                carouselInner.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }
    }

    nextBtn.addEventListener('click', () => {
        currentIndex++;
        updateCarousel();
        loopCarousel();
    });

    prevBtn.addEventListener('click', () => {
        currentIndex--;
        updateCarousel();
        loopCarousel();
    });

    // Automatic slide every 5 seconds
    setInterval(() => {
        currentIndex++;
        updateCarousel();
        loopCarousel();
    }, 5000);

    // Initialize carousel
    updateCarousel();

    // GSAP animations
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from('.section-header', {
            duration: 1,
            opacity: 0,
            y: -30,
            ease: 'power2.out',
            delay: 0.5
        });
        gsap.from('.custom-carousel-item', {
            duration: 1,
            opacity: 0,
            y: 30,
            stagger: 0.2,
            ease: 'power2.out',
            delay: 0.7,
            onComplete: () => {
                // Ensure animations only run once on load
                gsap.set('.custom-carousel-item', { clearProps: 'all' });
            }
        });
        gsap.from('.carousel-controls button', {
            duration: 1,
            opacity: 0,
            scale: 0.8,
            stagger: 0.2,
            ease: 'back.out(1.7)',
            delay: 1
        });

        // GSAP hover effects (desktop only)
        if (window.innerWidth > 991) {
            document.querySelectorAll('.custom-carousel-item img').forEach(img => {
                img.addEventListener('mouseenter', () => {
                    gsap.to(img, { 
                        duration: 0.5, 
                        scale: 1.1, 
                        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.3)', 
                        ease: 'power1.out' 
                    });
                });
                img.addEventListener('mouseleave', () => {
                    gsap.to(img, { 
                        duration: 0.5, 
                        scale: 1, 
                        boxShadow: 'none', 
                        ease: 'power1.out' 
                    });
                });
            });
            document.querySelectorAll('.carousel-controls button').forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    gsap.to(btn, { 
                        duration: 0.3, 
                        scale: 1.2, 
                        color: '#ffca28', 
                        ease: 'power1.out' 
                    });
                });
                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, { 
                        duration: 0.3, 
                        scale: 1, 
                        color: '#689f38', 
                        ease: 'power1.out' 
                    });
                });
            });
        }
    });
</script>
</body>
</html>
<?php ob_end_flush(); ?>