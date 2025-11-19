<?php
include 'Booking.php';
include 'Room.php';
include_once 'user_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$room_id     = $_POST['room_id'];
$guest_name  = trim($_POST['guest_name']);
$guest_email = trim($_POST['guest_email']);
$guests      = $_POST['guests'];
$check_in    = $_POST['check_in'];
$check_out   = $_POST['check_out'];

// Validate inputs
if (empty($guest_name) || empty($guest_email) || empty($guests) || empty($check_in) || empty($check_out)) {
    die("All fields are required.");
}

if ($check_in >= $check_out) {
    die("Check-out date must be after check-in date.");
}

$roomObj = new Room();
$room = $roomObj->find($room_id);
if (!$room) {
    die("Room not found.");
}

// Check if room can accommodate guests
if ($room['max_guests'] < $guests) {
    die("This room can only accommodate up to " . $room['max_guests'] . " guests.");
}

$days = (strtotime($check_out) - strtotime($check_in)) / 86400;
$days = max($days, 1);
$total_price = $days * $room['price_per_night'];

$booking = new Booking();
$booking->room_id = $room_id;
$booking->guest_name = $guest_name;
$booking->guest_email = $guest_email;
$booking->guests = $guests;
$booking->check_in = $check_in;
$booking->check_out = $check_out;
$booking->total_price = $total_price;
$booking->user_id = user_is_logged_in() ? $_SESSION['user_id'] : null;

// This will check availability and prevent double booking with transaction
$success = $booking->add();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Booking Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-card {
            max-width: 600px;
            margin: 3rem auto;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            background: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .booking-details {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        
        .booking-details p {
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-5">
    <div class="status-card">
        <?php if ($success): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="success-icon">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    
                    <h2 class="mb-3 text-success">Booking Confirmed!</h2>
                    <p class="text-muted mb-4">Your reservation has been successfully created.</p>
                    
                    <div class="booking-details">
                        <p><strong>Room:</strong> <span><?= htmlspecialchars($room['name']) ?></span></p>
                        <p><strong>Guest Name:</strong> <span><?= htmlspecialchars($guest_name) ?></span></p>
                        <p><strong>Email:</strong> <span><?= htmlspecialchars($guest_email) ?></span></p>
                        <p><strong>Guests:</strong> <span><?= $guests ?></span></p>
                        <p><strong>Check-in:</strong> <span><?= date('M j, Y', strtotime($check_in)) ?></span></p>
                        <p><strong>Check-out:</strong> <span><?= date('M j, Y', strtotime($check_out)) ?></span></p>
                        <p><strong>Nights:</strong> <span><?= $days ?></span></p>
                        <hr>
                        <p class="fs-5"><strong>Total:</strong> <strong class="text-primary">₱<?= number_format($total_price,2) ?></strong></p>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>A confirmation email will be sent to <strong><?= htmlspecialchars($guest_email) ?></strong></small>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="index.php" class="btn btn-primary">Back to Home</a>
                        <?php if (user_is_logged_in()): ?>
                            <a href="my_bookings.php" class="btn btn-outline-primary">View My Bookings</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center p-5">
                    <div class="error-icon">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                    
                    <h2 class="mb-3 text-danger">Booking Failed</h2>
                    <p class="text-muted mb-4">Unfortunately, this room is no longer available for the selected dates.</p>
                    
                    <div class="alert alert-warning">
                        <strong>Room:</strong> <?= htmlspecialchars($room['name']) ?><br>
                        <strong>Dates:</strong> <?= date('M j', strtotime($check_in)) ?> - <?= date('M j, Y', strtotime($check_out)) ?>
                    </div>
                    
                    <p class="text-secondary">Someone else may have just booked this room. Please try:</p>
                    
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="search.php" class="btn btn-primary">Search Again</a>
                        <a href="index.php" class="btn btn-outline-secondary">View All Rooms</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>