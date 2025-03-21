<?php include("./header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="./images/logo/favicon.ico"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
      :root {
        --primary-color: #4CAF50 !important; /* Agriculture Green */
        --primary-hover-color: #45a049 !important; /* Darker green for hover */
        --secondary-color: #FFC107 !important; /* Auction Gold */
        --secondary-hover-color: #ffca28 !important; /* Lighter gold for hover */
        --dark-color: #212529 !important;
        --light-color: #F8F9FA !important;
        --card-bg-color: #e8f5e9 !important; /* Light green background for cards */
        --card-hover-bg-color: #c8e6c9 !important; /* Lighter green for hover */
        --btn-text-color: #ffffff !important;
      }
      body {
        background-color: #f4e1d2 !important; /* Sandy beige */
        color: #3e2723; /* Dark brown text */
      }
      /* Section Headers */
      .section-header h2 {
        color: #689f38; /* Lime green */
        transition: color 0.3s ease;
      }
      .section-header h2:hover {
        color: #ffca28; /* Golden yellow on hover */
      }
      .section-header p {
        color: #3e2723; /* Dark brown */
      }
      /* About Section */
      .about-img img {
        transition: transform 0.5s ease;
      }
      .about-img:hover img {
        transform: scale(1.05); /* Slight zoom on hover */
      }
      .about-text h2 {
        color: #689f38; /* Lime green */
      }
      .about-text p {
        color: #3e2723; /* Dark brown */
      }
      /* Services Section */
      .card {
        background-color: var(--card-bg-color); /* Light green */
        color: #3e2723 !important; /* Dark brown text */
        transition: transform 0.3s ease, background-color 0.3s ease;
        height: 280px; /* Fixed height for all cards */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      .card:hover {
        transform: scale(1.05); /* Slight growth */
        background-color: var(--card-hover-bg-color); /* Lighter green */
      }
      .card i {
        color: #689f38; /* Lime green */
        font-size: 2rem;
        transition: color 0.3s ease;
      }
      .card:hover i {
        color: #ffca28; /* Golden yellow on hover */
      }
      .card-title {
        color: #689f38; /* Lime green */
      }
      .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; /* Center content horizontally */
      }
      /* Review Section (Placeholder Styling) */
      .review-card {
        background-color: #ffffff;
        transition: transform 0.3s ease;
      }
      .review-card:hover {
        transform: scale(1.03);
      }
      .review-name {
        color: #689f38; /* Lime green */
        transition: color 0.3s ease;
      }
      .review-name:hover {
        color: #ffca28; /* Golden yellow */
      }
      .review-username {
        color: #3e2723; /* Dark brown */
        transition: color 0.3s ease;
      }
      .review-username:hover {
        color: #e57373; /* Coral red */
      }
      /* Contact Section */
      .contact form {
        background-color: #ffffff; /* White form background */
        transition: box-shadow 0.3s ease;
      }
      .contact form:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow on hover */
      }
      .form-control {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
      }
      .form-control:focus {
        border-color: #689f38; /* Lime green */
        box-shadow: 0 0 5px rgba(104, 159, 56, 0.5); /* Green glow */
      }
      .btn-success {
        background-color: #689f38 !important; /* Lime green */
        border-color: #689f38 !important;
        transition: background-color 0.3s ease; /* No scale */
      }
      .btn-success:hover {
        background-color: #8bc34a !important; /* Lighter green */
      }
    </style>
    <?php include("./assets/link.html"); ?>
    <link rel="stylesheet" href="./assets/css/home-style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("navbar.php"); ?>
    
    <!-- Heroes Section -->
    <?php include("heroes.php"); ?>
    
    <!-- About Section Starts -->
    <section id="about" class="about section-padding animate__animated animate__fadeInUp">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="about-img">
                        <img style="overflow:hidden;" src="./images/heroes/1.jpg" alt="eAgriAuction service image" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
                    <div class="about-text">
                        <h2>Empowering Farmers with Better Returns <br/> Through eAuctions</h2>
                        <p>eAgriAuction is a dedicated platform designed to help farmers auction their agricultural produce for better returns. By leveraging technology, we bridge the gap between farmers and buyers, ensuring transparency and competitive pricing for products such as grains, vegetables, fruits, and more. Our platform enables farmers to take control of their sales process, minimize intermediaries, and maximize profits.</p>
                        <p>Whether you're a small-scale farmer or a large producer, eAgriAuction provides an easy-to-use interface to list your products, track bids in real-time, and secure payments seamlessly. Join us in revolutionizing agricultural trade and creating a brighter future for farmers across the nation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section Ends -->

    <!-- Services Section Starts -->
    <section class="services" id="services">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header text-center pb-5 animate__animated animate__fadeIn">
                        <h2>Our Services</h2>
                        <p>Empowering farmers and wholesalers with a seamless platform <br> to auction and bid for agricultural products.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Service 1 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-center pb-2">
                        <div class="card-body">
                            <i class="bi bi-basket"></i>
                            <h3 class="card-title">Auction Your Products</h3>
                            <p class="lead">Farmers can auction grains, vegetables, and fruits directly, connecting with buyers easily.</p>
                        </div>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-center pb-2">
                        <div class="card-body">
                            <i class="bi bi-currency-rupee"></i>
                            <h3 class="card-title">Competitive Bidding</h3>
                            <p class="lead">Wholesalers can bid competitively on agricultural products, ensuring fair prices for both farmers and buyers.</p>
                        </div>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-center pb-2">
                        <div class="card-body">
                            <i class="bi bi-truck"></i>
                            <h3 class="card-title">Streamlined Logistics</h3>
                            <p class="lead">Our platform connects farmers with buyers and ensures smooth logistics for delivery of products.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section Ends -->

    <!-- Review Section (Placeholder) -->
    <?php include("review.php"); ?>

    <!-- Contact Section Starts -->
    <section id="contact" class="contact animate__animated animate__fadeInUp">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header text-center pb-2">
                        <h2>Contact Us</h2>
                        <p>Reach out to us for any inquiries or assistance.</p>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-12 p-0 pt-2 pb-4">
                    <form id="contactForm" action="contact-mail.php" method="POST" class="bg-light p-4 m-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input class="form-control" name="full_name" placeholder="Full Name" required type="text">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input class="form-control" name="email" placeholder="Email" required type="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea class="form-control" name="message" placeholder="Message" required rows="3"></textarea>
                                </div>
                            </div>
                            <button class="btn btn-success btn-lg btn-block mt-3" type="submit">Send Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section Ends -->

    <!-- Footer -->
    <?php include("./footer.php"); ?>

    <!-- Bootstrap JS (includes Popper.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>

    <!-- Animation Scripts -->
    <script>
      // GSAP animations for sections and cards
      gsap.from('.about .row', {
        duration: 1,
        y: 50,
        opacity: 0,
        ease: 'power2.out',
        delay: 0.5
      });
      gsap.from('.services .card', {
        duration: 1,
        y: 30,
        opacity: 0,
        stagger: 0.2,
        ease: 'back.out(1.7)',
        delay: 0.7
      });
      gsap.from('.reviews .review-card', {
        duration: 1,
        y: 30,
        opacity: 0,
        stagger: 0.2,
        ease: 'power2.out',
        delay: 0.7
      });
      gsap.from('.contact form', {
        duration: 1,
        y: 50,
        opacity: 0,
        ease: 'power2.out',
        delay: 0.5
      });

      // GSAP hover effects for cards and reviews (desktop only)
      if ($(window).width() > 991) {
        $('.card').hover(
          function() {
            gsap.to(this, { 
              duration: 0.3, 
              scale: 1.05, 
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
        $('.review-name').hover(
          function() {
            gsap.to(this, { 
              duration: 0.3, 
              color: '#ffca28', 
              ease: 'power1.out' 
            });
          },
          function() {
            gsap.to(this, { 
              duration: 0.3, 
              color: '#689f38', 
              ease: 'power1.out' 
            });
          }
        );
        $('.review-username').hover(
          function() {
            gsap.to(this, { 
              duration: 0.3, 
              color: '#e57373', 
              ease: 'power1.out' 
            });
          },
          function() {
            gsap.to(this, { 
              duration: 0.3, 
              color: '#3e2723', 
              ease: 'power1.out' 
            });
          }
        );
      }
    </script>
</body>
</html>