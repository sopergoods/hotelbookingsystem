<?php
include_once 'user_auth.php';

// Check if admin is logged in first
$is_admin = admin_is_logged_in();

// If not admin, require user login
if (!$is_admin) {
    user_require_login();
}

include 'Booking.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    if ($is_admin) {
        header('Location: bookings_index.php');
    } else {
        header('Location: my_bookings.php');
    }
    exit;
}

$bookingObj = new Booking();
$data = $bookingObj->find($id);
if (!$data) {
    die("Booking not found.");
}

// Security: Make sure user can only view their own bookings (unless admin)
if (!$is_admin && $data['user_id'] != $_SESSION['user_id']) {
    die("Access denied. This is not your booking.");
}

// Use different headers based on who's viewing
if ($is_admin) {
    $pageTitle = "View Booking";
    include 'admin_header.php';
} else {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>View Booking</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <?php include 'public_navbar.php'; ?>
    <div class="container py-5">
    <?php
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Booking Details</h2>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Booking Information</h6>
                <p class="mb-1"><strong>Booking ID:</strong> #<?= $data['id'] ?></p>
                <p class="mb-1"><strong>Created:</strong> <?= date('F j, Y', strtotime($data['created_at'])) ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Guest Details</h6>
                <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($data['guest_name']) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($data['guest_email']) ?></p>
                <p class="mb-1"><strong>Number of Guests:</strong> <?= $data['guests'] ?></p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Room Details</h6>
                <p class="mb-1"><strong>Room:</strong> <?= htmlspecialchars($data['room_name']) ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Stay Duration</h6>
                <p class="mb-1"><strong>Check-in:</strong> <?= date('F j, Y', strtotime($data['check_in'])) ?></p>
                <p class="mb-1"><strong>Check-out:</strong> <?= date('F j, Y', strtotime($data['check_out'])) ?></p>
            </div>
        </div>

        <hr>

        <div class="alert alert-success">
            <h5 class="mb-0"><strong>Total Price:</strong> $<?= number_format($data['total_price'], 2) ?></h5>
        </div>

        <div class="d-flex gap-2">
            <?php if ($is_admin): ?>
                <a href="bookings_index.php" class="btn btn-secondary">← Back to All Bookings</a>
                <a href="delete_booking.php?id=<?= $data['id'] ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Are you sure you want to delete this booking?')">
                    Delete Booking
                </a>
            <?php else: ?>
                <a href="my_bookings.php" class="btn btn-secondary">← Back to My Bookings</a>
                <a href="cancel_booking.php?id=<?= $data['id'] ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                    Cancel Booking
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
if ($is_admin) {
    include 'admin_footer.php';
} else {
    echo '</div></body></html>';
}
?>