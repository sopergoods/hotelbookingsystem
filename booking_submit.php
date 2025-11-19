<?php
include 'Booking.php';
include 'Room.php';
include_once 'user_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$room_id     = $_POST['room_id'];
$guest_name  = $_POST['guest_name'];
$guest_email = $_POST['guest_email'];
$guests      = $_POST['guests'];
$check_in    = $_POST['check_in'];
$check_out   = $_POST['check_out'];

$roomObj = new Room();
$room = $roomObj->find($room_id);
if (!$room) {
    die("Room not found.");
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

$success = $booking->add();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Booking Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-5">
<?php if ($success): ?>
    <div class="alert alert-success">
        Booking successful! You will receive a confirmation shortly.
    </div>
    <p><strong>Room:</strong> <?= htmlspecialchars($room['name']) ?></p>
    <p><strong>Dates:</strong> <?= $check_in ?> → <?= $check_out ?></p>
    <p><strong>Total:</strong> $<?= number_format($total_price,2) ?></p>
    <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
<?php else: ?>
    <div class="alert alert-danger">
        The room is not available for the selected dates.
    </div>
    <a href="search.php" class="btn btn-secondary mt-3">Try Another Room</a>
<?php endif; ?>
</div>
</body>
</html>
