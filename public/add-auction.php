<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

$categories = getCategories();
isAuthenticated();
$AccountNo = getUserAccountNo($_SESSION["userId"]);
if ($AccountNo === NULL) {
  header("Location: update-profile.php");
  exit();
}

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
      background-color: #f4e1d2 !important;
      color: #3e2723;
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      margin-top: 80px;
      padding-bottom: 40px;
      position: relative;
      z-index: 10;
    }
    .card-main {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
      position: relative;
      z-index: 20;
    }
    .card-main:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .card-header {
      background: linear-gradient(45deg, #689f38, #8bc34a);
      color: #ffffff;
      font-size: 1.5rem;
      padding: 15px;
      border-bottom: none;
    }
    .card-body {
      padding: 20px;
      position: relative;
      z-index: 30;
    }
    .form-label {
      color: #3e2723;
      font-weight: 500;
    }
    .form-control {
      border-radius: 8px;
      border: 1px solid #689f38;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      pointer-events: auto !important;
      z-index: 40;
    }
    .form-control:focus {
      border-color: #ffca28;
      box-shadow: 0 0 5px rgba(255, 202, 40, 0.5);
    }
    .form-control:hover {
      border-color: #ffca28;
    }
    .btn-primary {
      background: linear-gradient(45deg, #689f38, #8bc34a);
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      color: #ffffff;
      transition: transform 0.3s ease, background 0.3s ease;
      pointer-events: auto !important;
      z-index: 50;
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
      pointer-events: auto !important;
      z-index: 50;
    }
    .btn-secondary:hover {
      background: linear-gradient(45deg, #5d4037, #8d6e63);
      transform: scale(1.05);
    }
    .alert {
      border-radius: 8px;
      transition: opacity 0.3s ease;
      z-index: 60;
    }
    #imagePreview {
      transition: opacity 0.3s ease;
    }
    /* Modal and Cropper Styling */
    .modal {
      z-index: 99999 !important; /* Highest z-index */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      display: flex !important;
      align-items: center;
      justify-content: center;
      padding: 0;
      margin: 0;
      overflow: hidden;
    }
    .modal-backdrop {
      z-index: 99990 !important; /* Below modal but above everything else */
    }
    .modal-dialog {
      width: 100%;
      max-width: 90vw;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 99995 !important;
    }
    .modal-content {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      background-color: #ffffff;
      width: 100%;
      max-width: 800px;
      height: auto;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      z-index: 99996 !important;
    }
    .modal-header {
      z-index: 99998 !important;
    }
    .modal-body {
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 60vh;
      max-height: 80vh;
      overflow-y: auto;
      position: relative;
      z-index: 99997 !important;
    }
    #cropperContainer {
      width: 100%;
      height: 100%;
      max-width: 100%;
      max-height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      pointer-events: auto !important;
      z-index: 99998 !important;
    }
    #cropperImage {
      max-width: 100%;
      max-height: 100%;
      display: block;
      margin: auto;
    }
    .cropper-container {
      width: 100% !important;
      height: 100% !important;
      position: absolute !important;
      top: 0;
      left: 0;
      pointer-events: auto !important;
      z-index: 99999 !important;
    }
    .cropper-crop-box, .cropper-view-box {
      margin: auto;
      pointer-events: auto !important;
      z-index: 100000 !important;
    }
    .cropper-face, .cropper-line, .cropper-point {
      pointer-events: auto !important;
      z-index: 100001 !important;
      background-color: #689f38 !important; /* Agri green for handles */
      opacity: 1 !important;
      width: 10px !important;
      height: 10px !important;
    }
    .cropper-modal {
      pointer-events: none !important;
      z-index: 99994 !important;
    }
    .modal-footer {
      z-index: 99998 !important;
      position: relative;
    }
    .modal-footer .btn {
      pointer-events: auto !important;
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
      .modal-dialog {
        max-width: 95vw;
      }
      .modal-body {
        padding: 10px;
        min-height: 50vh;
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
            <select id="category" name="category" class="form-control" required>
              <option value="" disabled selected>Select a Category</option>
              <?php if (isset($categories) && is_array($categories) && count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                  <option value="<?= htmlspecialchars($category['categoryId']) ?>">
                    <?= htmlspecialchars($category['categoryName']) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" disabled>No categories available</option>
              <?php endif; ?>
            </select>
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
            <input type="file" id="productImage" name="productImage" accept="image/*" required class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" class="img-fluid rounded-1 border border-2 border-dark" style="max-width: 100%; height: auto; display: none;">
          </div>
          
          <!-- Cropper Modal -->
          <div id="cropperModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Crop Image</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div id="cropperContainer">
                    <img id="cropperImage" style="max-width: 100%;">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" id="cropButton" class="btn btn-primary">Crop & Save</button>
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
    document.addEventListener('DOMContentLoaded', function() {
      // GSAP Animations
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

      // Cropper Functionality
      const productImageInput = document.getElementById('productImage');
      const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
      const cropperImage = document.getElementById('cropperImage');
      const cropButton = document.getElementById('cropButton');
      const croppedImageInput = document.getElementById('croppedImage');
      const imagePreview = document.getElementById('imagePreview');
      let cropper = null;

      productImageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files.length > 0) {
          const reader = new FileReader();
          reader.onload = function(event) {
            cropperImage.src = event.target.result;
            cropperModal.show();
          };
          reader.readAsDataURL(e.target.files[0]);
        }
      });

      document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
        if (cropper) {
          cropper.destroy();
        }
        
        cropper = new Cropper(cropperImage, {
          aspectRatio: 1,
          viewMode: 1,
          autoCropArea: 0.8,
          responsive: true,
          scalable: true,
          zoomable: true,
          movable: true,
          cropBoxMovable: true,
          cropBoxResizable: true,
          background: true,
          center: true,
          ready: function() {
            // Ensure interactivity
            const cropperElements = document.querySelectorAll('.cropper-container, .cropper-crop-box, .cropper-face, .cropper-line, .cropper-point');
            cropperElements.forEach(el => {
              el.style.pointerEvents = 'auto';
              el.style.zIndex = '100000';
            });
            // Center crop box
            const containerData = this.cropper.getContainerData();
            const cropBoxSize = Math.min(containerData.width, containerData.height) * 0.8;
            this.cropper.setCropBoxData({
              left: (containerData.width - cropBoxSize) / 2,
              top: (containerData.height - cropBoxSize) / 2,
              width: cropBoxSize,
              height: cropBoxSize
            });
          }
        });
      });

      cropButton.addEventListener('click', function() {
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
            cropperModal.hide();
          }
        }
      });

      document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
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