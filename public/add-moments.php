<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");

$categories = getCategories();
// Call the authentication function
isAuthenticated();
$AccountNo = getUserAccountNo($_SESSION["userId"]);
if ($AccountNo === NULL) {
  header("Location: update-profile.php");
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = htmlspecialchars(trim($_POST['title']));
  $product_type = $_POST["product_type"];
  $product_quantity = $_POST["product_quantity"];
  $product_unit = $_POST["product_unit"];
  $start_price = filter_var($_POST['start_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $start_time = $_POST['start_time'];
  $end_date = $_POST['end_date'];
  $address = $_POST['address'];
  $category_id = $_POST['category'];
  $description = $_POST['description'];

  if (!empty($_POST['cropped_image'])) {
    $croppedImageData = $_POST['cropped_image'];
    $uploadDir = '../images/products/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    $uniqueName = 'prod_' . uniqid() . '.webp';
    $targetFile = $uploadDir . $uniqueName;
    list(, $croppedImageData) = explode(',', $croppedImageData);
    $croppedImageData = base64_decode($croppedImageData);

    if (file_put_contents($targetFile, $croppedImageData)) {
      $user_id = $_SESSION["userId"];
      $result = addAuction($title, $start_price, $start_time, $end_date, $category_id, $address, $description, $uniqueName, $user_id, $product_type, $product_quantity, $product_unit);
      if (strpos($result, "Auction added successfully") !== false) {
        header("Location: manage-auction.php");
        exit();
      } else {
        echo '<p class="alert alert-danger alert-dismissible fade show" role="alert">Error: ' . $result . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></p>';
      }
    } else {
      echo '<p class="alert alert-danger alert-dismissible fade show" role="alert">Error: Failed to save the cropped image.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></p>';
    }
  } else {
    echo '<p class="alert alert-danger alert-dismissible fade show" role="alert">Error: No cropped image data received.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></p>';
  }
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Auction</title>
  <?php include_once("../assets/link.html"); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <style>
    body {
      background-color: #f4e1d2 !important; /* Sandy beige */
      color: #3e2723; /* Dark brown */
      font-family: 'Arial', sans-serif;
    }
    .container {
      margin-top: 80px;
      padding-bottom: 40px;
    }
    .card-main {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
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
    .form-label {
      color: #3e2723;
      font-weight: 500;
    }
    .form-control, .btn-outline-secondary {
      border-radius: 8px;
      border: 1px solid #689f38;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus, .btn-outline-secondary:focus {
      border-color: #ffca28;
      box-shadow: 0 0 5px rgba(255, 202, 40, 0.5);
    }
    .form-control:hover, .btn-outline-secondary:hover {
      border-color: #ffca28;
    }
    .dropdown-menu {
      background-color: #ffffff;
      border: 1px solid #689f38;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 9999;
      width: 100%;
    }
    .dropdown-item {
      color: #3e2723;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .dropdown-item:hover {
      background-color: #f4e1d2;
      color: #ffca28;
    }
    .btn-primary {
      background: linear-gradient(45deg, #689f38, #8bc34a);
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      color: #ffffff;
      transition: transform 0.3s ease, background 0.3s ease;
    }
    .btn-primary:hover {
      background: linear-gradient(45deg, #8bc34a, #a4d007);
      transform: scale(1.05);
    }
    .btn-secondary {
      background: linear-gradient(45deg, #3e2723, #5d4037);
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      color: #ffffff;
      transition: transform 0.3s ease, background 0.3s ease;
    }
    .btn-secondary:hover {
      background: linear-gradient(45deg, #5d4037, #8d6e63);
      transform: scale(1.05);
    }
    .alert {
      border-radius: 8px;
      transition: opacity 0.3s ease;
    }
    #imagePreview {
      transition: opacity 0.3s ease;
    }
    .modal {
      z-index: 1055;
    }
    .modal-backdrop {
      z-index: 1050;
    }
    .modal-content {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .modal-body {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    #cropperContainer {
      max-width: 100%;
      max-height: 70vh;
      overflow: auto;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    #cropperImage {
      max-width: 100%;
      display: block;
    }
    @media (max-width: 576px) {
      .card-header {
        font-size: 1.25rem;
      }
      .card-body {
        padding: 15px;
      }
      .btn-primary, .btn-secondary {
        padding: 6px 15px;
      }
      .modal-body {
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card card-main mb-4">
      <div class="card-header">
        <i class="fa fa-gavel"></i> Add Auction
      </div>
      <div class="card-body">
        <form id="auctionForm" action="add-auction.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Select a Category
              </button>
              <?php if (isset($categories) && is_array($categories) && count($categories) > 0): ?>
                <ul class="dropdown-menu w-100" aria-labelledby="categoryDropdown">
                  <?php foreach ($categories as $category): ?>
                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="#" data-value="<?= htmlspecialchars($category['categoryName']) ?>" data-id="<?= htmlspecialchars($category['categoryId']) ?>">
                        <?= htmlspecialchars($category['categoryName']) ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <p>No categories available.</p>
              <?php endif; ?>
              <input type="hidden" name="category" id="selectedCategory" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="product_type" class="form-label">Product Type</label>
            <select id="product_type" name="product_type" class="form-control" required>
              <option value="" disabled selected>Select</option>
              <option value="organic">ORGANIC</option>
              <option value="hybrid">HYBRID</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="product_quantity" class="form-label">Quantity</label>
            <input type="number" id="product_quantity" name="product_quantity" class="form-control">
          </div>
          <div class="mb-3">
            <label for="product_unit" class="form-label">Quantity Type</label>
            <select id="product_unit" name="product_unit" class="form-control" required>
              <option value="" disabled selected>Select</option>
              <option value="kg">Kg</option>
              <option value="ton">Ton</option>
              <option value="nos">Nos</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="start_price" class="form-label">Starting Price</label>
            <input type="number" id="start_price" name="start_price" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="datetime-local" id="end_date" name="end_date" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" required class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Product Image</label>
            <input type="file" id="productImage" name="productImage" accept="image/jpeg, image/png, image/webp" required class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" class="img-fluid rounded-1 border border-2 border-dark" style="max-width: 100%; height: auto; display: none;">
          </div>
          <!-- Cropper Modal -->
          <div id="cropperModal" class="modal fade" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cropperModalLabel">Crop Image</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div id="cropperContainer">
                    <img id="cropperImage" style="max-width: 100%; display: block;">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" id="cropButton" class="btn btn-primary">Crop</button>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <input type="submit" class="btn btn-primary" value="Add Product">
            <input type="reset" class="btn btn-secondary" value="Clear">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // GSAP animations
      gsap.from('.card-main', {
        duration: 2,
        opacity: 0,
        y: 50,
        ease: 'power4.out',
        delay: 0.5
      });
      gsap.from('.mb-3', {
        duration: 2,
        opacity: 0,
        y: 20,
        stagger: 0.1,
        ease: 'power4.out',
        delay: 0.7
      });
      gsap.from('.d-flex .btn', {
        duration: 2,
        opacity: 0,
        x: -20,
        stagger: 0.2,
        ease: 'power4.out',
        delay: 1
      });

      // Hover effects (desktop only)
      if (window.innerWidth > 991) {
        document.querySelectorAll('.form-control, .btn-outline-secondary').forEach(input => {
          input.addEventListener('mouseenter', () => {
            gsap.to(input, {
              duration: 0.3,
              scale: 1.02,
              boxShadow: '0 0 5px rgba(255, 202, 40, 0.5)',
              ease: 'power1.out'
            });
          });
          input.addEventListener('mouseleave', () => {
            gsap.to(input, {
              duration: 0.3,
              scale: 1,
              boxShadow: 'none',
              ease: 'power1.out'
            });
          });
        });
      }

      // Form logic
      const categoryDropdownButton = document.getElementById('categoryDropdown');
      const categoryItems = document.querySelectorAll('.dropdown-item');
      const selectedCategoryInput = document.getElementById('selectedCategory');
      const auctionForm = document.getElementById('auctionForm');
      const productImageInput = document.getElementById('productImage');
      const cropperModal = document.getElementById('cropperModal');
      const cropperImage = document.getElementById('cropperImage');
      const cropButton = document.getElementById('cropButton');
      const croppedImageInput = document.getElementById('croppedImage');
      const imagePreview = document.getElementById('imagePreview');

      let cropper = null;

      // Category selection
      categoryItems.forEach(item => {
        item.addEventListener('click', function (e) {
          e.preventDefault();
          const selectedCategory = this.getAttribute('data-value');
          categoryDropdownButton.textContent = selectedCategory;
          selectedCategoryInput.value = this.getAttribute('data-id');
        });
      });

      // Form submission validation
      auctionForm.addEventListener('submit', function (event) {
        if (!selectedCategoryInput.value) {
          alert('Please select a category.');
          event.preventDefault();
        }
        if (!croppedImageInput.value) {
          alert('Please upload and crop an image.');
          event.preventDefault();
        }
      });

      // Product image input change
      productImageInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            cropperImage.src = e.target.result;
            const modal = new bootstrap.Modal(cropperModal, {
              backdrop: 'static',
              keyboard: false
            });
            modal.show();

            cropperModal.addEventListener('shown.bs.modal', function () {
              if (cropper) {
                cropper.destroy();
              }
              cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                scalable: true,
                rotatable: true,
                background: false,
                center: true,
                ready() {
                  console.log('Cropper is ready');
                }
              });
            }, { once: true });
          };
          reader.readAsDataURL(this.files[0]);
        }
      });

      // Crop button action
      cropButton.addEventListener('click', function () {
        if (cropper) {
          const canvas = cropper.getCroppedCanvas({
            width: 500,
            height: 500,
            imageSmoothingQuality: 'high'
          });
          if (canvas) {
            const croppedDataUrl = canvas.toDataURL('image/webp');
            croppedImageInput.value = croppedDataUrl;
            imagePreview.src = croppedDataUrl;
            imagePreview.style.display = 'block';
            cropper.destroy();
            cropper = null;
            const modal = bootstrap.Modal.getInstance(cropperModal);
            modal.hide();
          }
        }
      });

      cropperModal.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
      });
    });
  </script>
</body>
</html>
<?php
  include_once("./auction-chatbot.php");
  include_once("./footer.php");
  ob_end_flush();
?>