<?php
session_start();
ob_start(); // Start output buffering
include("header.php");
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
isAuthenticated();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure user is logged in
if (!isset($_SESSION['userId'])) {
    echo "You need to be logged in to make a payment.";
    exit();
}

$user_id = $_SESSION['userId'];
$auction_id = $_GET['auction_id'] ?? null;

if (!$auction_id) {
    echo "Invalid auction ID.";
    exit();
}

// Get auction details (assuming these functions exist)
$sUserId = getHighestBidderId($auction_id);
$auction = getAuctionById($auction_id);
$sUser = getUserById($sUserId);
$rUser = getUserById($auction["auctionCreatedBy"]);
$highest_bid = getHighestBid($auction_id);
$accountNo = getUserAccountNo($auction["auctionCreatedBy"]);

// Check if user is the highest bidder
$is_highest_bidder = false;
$highest_bidder = getHighestBidder($auction_id);
if ($highest_bidder['bidUserId'] == $user_id) {
    $is_highest_bidder = true;
}

if (!$is_highest_bidder) {
    echo "You are not the highest bidder for this auction.";
    exit();
}

// Handle payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $cardNumber = $_POST['cardNumber'] ?? null;
    $expiryMonth = $_POST['expiryMonth'] ?? null;
    $expiryYear = $_POST['expiryYear'] ?? null;
    $cvv = $_POST['cvv'] ?? null;

    // Server-side validation
    if (!$username || !$cardNumber || !$expiryMonth || !$expiryYear || !$cvv) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'All fields are required.']);
        exit();
    }

    if (strlen($username) < 2) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Card owner name must be at least 2 characters.']);
        exit();
    }

    if (!preg_match('/^\d{13,19}$/', $cardNumber)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Card number must be 13-19 digits.']);
        exit();
    }

    $month = (int)$expiryMonth;
    $year = (int)$expiryYear;
    $currentDate = new DateTime();
    $currentMonth = (int)$currentDate->format('m');
    $currentYear = (int)$currentDate->format('y');

    if ($month < 1 || $month > 12 || $year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid or expired date.']);
        exit();
    }

    if (!preg_match('/^\d{3}$/', $cvv)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'CVV must be 3 digits.']);
        exit();
    }

    $transaction_tracking_id = uniqid('txn_', true);

    try {
        $query = "INSERT INTO trans 
                  (transTrackingId, transCardNo, transAccountNo, transUserId, transAmount, transAuctionId) 
                  VALUES 
                  (:transTrackingId, :transCardNo, :transAccountNo, :transUserId, :transAmount, :transAuctionId)";
        $stmt = $pdo->prepare($query);
        $success = $stmt->execute([
            ':transTrackingId' => $transaction_tracking_id,
            ':transCardNo' => $cardNumber,
            ':transAccountNo' => $accountNo,
            ':transUserId' => $user_id,
            ':transAmount' => $highest_bid,
            ':transAuctionId' => $auction_id
        ]);

        if ($success && $stmt->rowCount() > 0) {
            $mail = new PHPMailer(true);
            $trans = getInvoiceDetails($sUserId, $auction_id, $highest_bid);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eagri.ct.ws@gmail.com';
                $mail->Password = 'xnfkhjazsdjlsrsg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('eagri.ct.ws@gmail.com', 'eAgri Auction');
                $mail->addAddress($rUser["userEmail"]);
                $mail->isHTML(true);
                $mail->Subject = 'Payment Successful for your Order';
                $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; color: #333; }
                        .container { max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
                        h3 { color: #2c3e50; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background-color: #f8f8f8; }
                        .total { text-align: right; font-weight: bold; margin-top: 20px; }
                        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class="container py-5">
                        <h3>Order Confirmation</h3>
                        <p>Dear <b>' . htmlspecialchars($sUser["userFirstName"]) . ' ' . htmlspecialchars($sUser["userLastName"]) . '</b>,</p>
                        <p>Your payment has been processed successfully. Details below:</p>
                        <h3>Transaction Details</h3>
                        <table>
                            <tr>
                                <td><b>From:</b><br>Name: ' . htmlspecialchars($sUser["userName"]) . '<br>Card No: ' . htmlspecialchars($trans["transCardNo"]) . '<br>Transaction ID: ' . htmlspecialchars(explode('_', explode('.', $trans["transTrackingId"])[0])[1]) . '</td>
                                <td><b>To:</b><br>Name: ' . htmlspecialchars($rUser["userName"]) . '<br>Account No: ' . htmlspecialchars($trans["transAccountNo"]) . '<br>Invoice ID: ' . htmlspecialchars(explode('.', $trans["transTrackingId"])[1]) . '</td>
                            </tr>
                        </table>
                        <table>
                            <thead>
                                <tr><th>Item Name</th><th>Category</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>' . htmlspecialchars($auction["auctionTitle"]) . '</td>
                                    <td>' . htmlspecialchars(getCategoryById($auction["auctionCategoryId"])) . '</td>
                                    <td>₹' . htmlspecialchars($highest_bid) . '</td>
                                    <td>1</td>
                                    <td>₹' . htmlspecialchars($highest_bid) . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="total">Grand Total: ₹' . htmlspecialchars($highest_bid) . '</p>
                        <p class="footer"><h2>Thank you!</h2>We appreciate your business.</p>
                    </div>
                    <p>eAgri Auction</p>
                </body>
                </html>';
                $mail->send();
            } catch (Exception $e) {
                error_log("Seller email failed: " . $e->getMessage());
            }

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eagri.ct.ws@gmail.com';
                $mail->Password = 'xnfkhjazsdjlsrsg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('eagri.ct.ws@gmail.com', 'eAgri Auction');
                $mail->addAddress($sUser["userEmail"]);
                $mail->isHTML(true);
                $mail->Subject = 'Payment Confirmation for Auction ID: ' . $auction_id;
                $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; color: #333; }
                        .container { max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
                        h3 { color: #2c3e50; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background-color: #f8f8f8; }
                        .total { text-align: right; font-weight: bold; margin-top: 20px; }
                        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class="container py-5">
                        <h3>Auction Payment Process</h3>
                        <p>Dear <b>' . htmlspecialchars($rUser["userFirstName"]) . ' ' . htmlspecialchars($rUser["userLastName"]) . '</b>,</p>
                        <p>Payment received for auction ID ' . $auction_id . '. Details below:</p>
                        <h3>Transaction Details</h3>
                        <table>
                            <tr>
                                <td><b>From:</b><br>Name: ' . htmlspecialchars($sUser["userName"]) . '<br>Card No: ' . htmlspecialchars($trans["transCardNo"]) . '<br>Transaction ID: ' . htmlspecialchars(explode('_', explode('.', $trans["transTrackingId"])[0])[1]) . '</td>
                                <td><b>To:</b><br>Name: ' . htmlspecialchars($rUser["userName"]) . '<br>Account No: ' . htmlspecialchars($trans["transAccountNo"]) . '<br>Invoice ID: ' . htmlspecialchars(explode('.', $trans["transTrackingId"])[1]) . '</td>
                            </tr>
                        </table>
                        <table>
                            <thead>
                                <tr><th>Item Name</th><th>Category</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>' . htmlspecialchars($auction["auctionTitle"]) . '</td>
                                    <td>' . htmlspecialchars(getCategoryById($auction["auctionCategoryId"])) . '</td>
                                    <td>₹' . htmlspecialchars($highest_bid) . '</td>
                                    <td>1</td>
                                    <td>₹' . htmlspecialchars($highest_bid) . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="total">Grand Total: ₹' . htmlspecialchars($highest_bid) . '</p>
                        <p class="footer"><h2>Thank you!</h2>We appreciate your business.</p>
                    </div>
                    <p>eAgri Auction</p>
                </body>
                </html>';
                $mail->send();
            } catch (Exception $e) {
                error_log("Buyer email failed: " . $e->getMessage());
            }

            header('Content-Type: application/json');
            ob_clean(); // Clear any buffered output (e.g., from header.php)
            echo json_encode([
                'success' => true,
                'transaction_id' => htmlspecialchars(explode('_', explode('.', $transaction_tracking_id)[0])[1]),
                'amount' => $highest_bid,
                'auction_id' => $auction_id
            ]);
            exit();
        } else {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['error' => 'Database insertion failed. No rows affected.']);
            exit();
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        ob_clean();
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

// Include header and navbar only for GET requests (initial page load)
include("header.php");
include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Auction #<?php echo $auction_id; ?></title>
    <?php include("../assets/link.html"); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        h1 {
            font-size: 1.8rem;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: #28a745;
            color: #fff;
            padding: 10px 15px;
            font-weight: bold;
            text-align: center;
        }
        .card-body {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        label {
            display: block;
            font-size: 0.9rem;
            color: #34495e;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #dcdcdc;
            border-radius: 5px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
            outline: none;
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            flex: 1;
        }
        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #dcdcdc;
            border-left: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
            transition: opacity 0.3s ease;
        }
        .input-error {
            border-color: #e74c3c;
        }
        .shake {
            animation: shake 0.4s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .btn-success {
            background: #28a745;
            border: none;
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            border-radius: 5px;
            width: 100%;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-success:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment for Auction #<?php echo $auction_id; ?></h1>
        <div class="card">
            <div class="card-header">Credit Card Payment</div>
            <div class="card-body">
                <form id="paymentForm" method="POST">
                    <div class="form-group">
                        <label for="username">Card Owner</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Card Owner Name" required>
                        <div class="error-message" id="usernameError"></div>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <div class="input-group">
                            <input type="text" name="cardNumber" id="cardNumber" class="form-control" placeholder="Valid Card Number" required>
                            <span class="input-group-text">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard mx-1"></i>
                                <i class="fab fa-cc-amex"></i>
                            </span>
                        </div>
                        <div class="error-message" id="cardNumberError"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Expiration Date (MM/YY)</label>
                                <div class="input-group">
                                    <input type="number" name="expiryMonth" id="expiryMonth" class="form-control" placeholder="MM" min="1" max="12" required>
                                    <input type="number" name="expiryYear" id="expiryYear" class="form-control" placeholder="YY" min="24" max="99" required>
                                </div>
                                <div class="error-message" id="expiryDateError"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="cvv">CVV <i class="fa fa-question-circle" data-toggle="tooltip" title="3-digit code on the back of your card"></i></label>
                                <input type="text" name="cvv" id="cvv" class="form-control" placeholder="CVV" required>
                                <div class="error-message" id="cvvError"></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="cnfbtn" class="btn btn-success" disabled>Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        const form = $('#paymentForm');
        const inputs = {
            username: $('#username'),
            cardNumber: $('#cardNumber'),
            expiryMonth: $('#expiryMonth'),
            expiryYear: $('#expiryYear'),
            cvv: $('#cvv')
        };
        const errors = {
            username: $('#usernameError'),
            cardNumber: $('#cardNumberError'),
            expiryDate: $('#expiryDateError'),
            cvv: $('#cvvError')
        };
        const submitBtn = $('#cnfbtn');

        function showError(field, message) {
            errors[field].text(message).fadeIn(200);
            inputs[field].addClass('input-error shake');
            setTimeout(() => inputs[field].removeClass('shake'), 400);
        }

        function clearError(field) {
            errors[field].fadeOut(200);
            inputs[field].removeClass('input-error');
        }

        Object.keys(inputs).forEach(field => {
            inputs[field].on('input', function() {
                const value = $(this).val().trim();
                let isValid = true;

                if (field === 'username' && value.length < 2) {
                    showError('username', 'Name must be at least 2 characters.');
                    isValid = false;
                } else if (field === 'cardNumber' && (value.length < 13 || value.length > 19 || !/^\d+$/.test(value))) {
                    showError('cardNumber', 'Card number must be 13-19 digits.');
                    isValid = false;
                } else if (field === 'expiryMonth' || field === 'expiryYear') {
                    const month = parseInt(inputs.expiryMonth.val()) || 0;
                    const year = parseInt(inputs.expiryYear.val()) || 0;
                    const currentDate = new Date();
                    const currentMonth = currentDate.getMonth() + 1;
                    const currentYear = currentDate.getFullYear() % 100;

                    if (month < 1 || month > 12 || year < currentYear || (year === currentYear && month < currentMonth)) {
                        showError('expiryDate', 'Invalid or expired date.');
                        isValid = false;
                    } else {
                        clearError('expiryDate');
                    }
                } else if (field === 'cvv' && !/^\d{3}$/.test(value)) {
                    showError('cvv', 'CVV must be 3 digits.');
                    isValid = false;
                } else {
                    clearError(field);
                }

                toggleSubmitButton();
            });
        });

        function toggleSubmitButton() {
            const allFieldsFilled = Object.values(inputs).every(input => input.val().trim() !== '');
            const noErrors = Object.values(errors).every(error => error.is(':hidden'));
            submitBtn.prop('disabled', !(allFieldsFilled && noErrors));
        }

        form.on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Processing Payment...',
                text: 'Please wait while we process your payment.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            title: 'Payment Successful!',
                            text: `Transaction ID: ${response.transaction_id}\nAmount Paid: ₹${response.amount}`,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'bid.php?id=' + response.auction_id;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Payment Failed',
                            text: response.error || 'An unknown error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while processing your payment: ' + (xhr.responseText || error),
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
<?php
include_once("./review-popup.php");
include_once("./footer.php");
ob_end_flush();
?>