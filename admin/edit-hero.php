<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();

// Fetch Hero Details
$heroId = $_GET['heroId'];
$hero = getHeroById($heroId)[0]; // Fetch hero details from the database

$validStatuses = ['activate', 'deactivate', 'suspend']; // Define allowed status values
$successMessage = ''; // To store success messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $heroTitle = htmlspecialchars(trim($_POST['heroTitle']));
    $heroMessage = htmlspecialchars(trim($_POST['heroMessage']));
    $heroContent = $_POST['heroContent']; // Raw HTML content
    $heroStatus = $_POST['heroStatus'];

    // Validate heroStatus
    if (!in_array($heroStatus, $validStatuses)) {
        echo '<p class="alert alert-danger">Invalid status value. Allowed values are "activate", "deactivate", or "suspend".</p>';
    } else {
        $croppedImageData = $_POST['cropped_image'] ?? null;
        $uploadDir = '../images/heroes/';
        $uniqueName = $hero["heroImg"]; // Keep existing image name

        if ($croppedImageData) {
            // Ensure the directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Decode base64 and save the image
            list(, $croppedImageData) = explode(',', $croppedImageData);
            $croppedImageData = base64_decode($croppedImageData);
            $targetFile = $uploadDir . $uniqueName; // Use old image name

            if (!file_put_contents($targetFile, $croppedImageData)) {
                echo '<p class="alert alert-danger">Error: Failed to save the cropped image.</p>';
            }
        }

        // Update hero details in the database
        $result = updateHero($heroId, $heroTitle, $heroMessage, $heroContent, $heroStatus, $uniqueName, null);

        if ($result === "Hero updated successfully!") {
            $successMessage = "Hero updated successfully!";
            $hero = getHeroById($heroId)[0]; // Refresh hero details
        } else {
            echo '<p class="alert alert-danger">' . $result . '</p>';
        }
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
  <title>Edit Hero</title>
  <?php include_once("../assets/link.html"); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
</head>
<body>
  <div class="container py-5">
    <?php if (!empty($successMessage)) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-user"></i>&nbsp; Edit Hero
      </div>
      <div class="card-body">
        <form id="heroForm" action="edit-hero.php?heroId=<?= $hero["heroId"] ?>" method="POST">
          <div class="mb-3">
            <label for="heroTitle" class="form-label">Title</label>
            <input type="text" id="heroTitle" name="heroTitle" value="<?= htmlspecialchars($hero['heroTitle']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroMessage" class="form-label">Message</label>
            <input type="text" id="heroMessage" name="heroMessage" value="<?= htmlspecialchars($hero['heroMessage']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroContent" class="form-label">Content</label>
            <textarea id="heroContent" name="heroContent" class="form-control" style="height: 300px;"><?= htmlspecialchars($hero['heroContent']) ?></textarea>
          </div>
          <div class="mb-3">
            <label for="heroStatus" class="form-label">Status</label>
            <select id="heroStatus" name="heroStatus" class="form-control" required>
              <option value="activate" <?= $hero['heroStatus'] == 'activate' ? 'selected' : '' ?>>Activate</option>
              <option value="deactivate" <?= $hero['heroStatus'] == 'deactivate' ? 'selected' : '' ?>>Deactivate</option>
              <option value="suspend" <?= $hero['heroStatus'] == 'suspend' ? 'selected' : '' ?>>Suspend</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Hero Image</label>
            <input type="file" id="productImage" name="productImage" accept="image/*" class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" src="../images/heroes/<?= $hero['heroImg'] ?>" alt="Image Preview" class="img-fluid rounded-1 border border-2 border-dark" />
          </div>
          <div class="d-flex justify-content-between">
            <input type="submit" class="btn btn-primary" value="Update Hero">
            <input type="reset" class="btn btn-secondary" value="Clear">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Cropper.js Modal -->
  <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crop Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="cropperModalBody">
          <!-- Image will be inserted here dynamically -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="cropImageBtn">Crop & Save</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
  <script>
    tinymce.init({
      selector: '#heroContent',
      plugins: 'lists link image code',
      toolbar: 'undo redo | bold italic | bullist numlist | code',
    });

    let cropper;
    document.getElementById('productImage').addEventListener('change', function () {
      const reader = new FileReader();
      reader.onload = function (e) {
        const modalBody = document.getElementById('cropperModalBody');
        modalBody.innerHTML = `<img id="cropperImage" src="${e.target.result}" style="max-width: 100%;">`;
        cropper = new Cropper(document.getElementById('cropperImage'), { aspectRatio: 16 / 9 });
        new bootstrap.Modal(document.getElementById('cropperModal')).show();
      };
      reader.readAsDataURL(this.files[0]);
    });

    document.getElementById('cropImageBtn').addEventListener('click', function () {
      document.getElementById('croppedImage').value = cropper.getCroppedCanvas().toDataURL('image/webp');
      document.getElementById('imagePreview').src = cropper.getCroppedCanvas().toDataURL();
      bootstrap.Modal.getInstance(document.getElementById('cropperModal')).hide();
    });
  </script>
</body>
</html>

<?php
include_once("./footer.php");
ob_end_flush();
?>