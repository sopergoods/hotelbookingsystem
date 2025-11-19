<?php
include_once 'user_auth.php';
user_require_login();

include 'Booking.php';
$bookingObj = new Booking();
$bookings = $bookingObj->userBookings($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">My Bookings</h2>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You have no bookings yet.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Room</th>
                            <th>Dates</th>
                            <th>Guests</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['room_name']) ?></td>
                                <td><?= $b['check_in'] ?> → <?= $b['check_out'] ?></td>
                                <td><?= $b['guests'] ?></td>
                                <td>$<?= number_format($b['total_price'],2) ?></td>
                                <td>
                                    <?php if ($b['check_in'] > date('Y-m-d')): ?>
                                        <span class="badge bg-success">Upcoming</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="view_booking.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                    <?php if ($b['check_in'] > date('Y-m-d')): ?>
                                        <a href="cancel_booking.php?id=<?= $b['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Cancel this booking?');">
                                            Cancel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
