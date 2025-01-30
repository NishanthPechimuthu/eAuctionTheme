<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['userId'];
    $categories = $_POST['categoryId']; // Array of selected categories
    $type = $_POST['type'];
    $keywords = $_POST['keywords'];

    try {
        global $pdo;

        // Insert interests for each selected category
        foreach ($categories as $categoryId) {
            $stmt = $pdo->prepare("INSERT INTO interests (interestUserId, interestCategoryId, interestProductType, interestKeywords) 
                                   VALUES (:userId, :categoryId, :type, :keywords)");
            $stmt->execute([
                ':userId' => $userId,
                ':categoryId' => $categoryId,
                ':type' => $type,
                ':keywords' => $keywords,
            ]);
        }

        $successMessage = "Interest(s) added successfully.";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Interest</title>
    <?php include_once("../assets/link.html"); ?>

    <!-- Include jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Floating button styles */
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background 0.3s;
        }

        .floating-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Floating Notification Button -->
<button class="floating-btn" data-bs-toggle="modal" data-bs-target="#interestModal">
    <i class="fas fa-bell"></i>
</button>

<!-- Interest Form Modal -->
<div class="modal fade" id="interestModal" tabindex="-1" aria-labelledby="interestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interestModalLabel">Add Interest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success"><?= $successMessage; ?></div>
                <?php elseif (isset($errorMessage)): ?>
                    <div class="alert alert-danger"><?= $errorMessage; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" class="form-control" name="userId" value="<?= $_SESSION["userId"]; ?>" required>

                    <div class="mb-3">
                        <label for="categoryId" class="form-label">Select Categories</label>
                        <select class="form-select select2" id="categoryId" name="categoryId[]" multiple="multiple" required>
                            <?php 
                            $categories = getCategories();
                            foreach ($categories as $category): 
                            ?>
                                <option value="<?= $category['categoryId']; ?>"><?= $category['categoryName']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Product Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="organic">Organic</option>
                            <option value="hybrid">Hybrid</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Enter keywords (optional)">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        function initializeSelect2() {
            $('#categoryId').select2({
                dropdownParent: $('#interestModal'), // Fixes Select2 inside Bootstrap modal
                placeholder: "Select categories",
                allowClear: true
            });
        }

        // Initialize Select2 on page load
        initializeSelect2();

        // Reinitialize Select2 when the modal is opened
        $('#interestModal').on('shown.bs.modal', function () {
            initializeSelect2();
        });
    });
</script>

</body>
</html>