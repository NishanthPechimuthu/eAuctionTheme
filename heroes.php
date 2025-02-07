<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
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
    });
</script>
</body>
</html>