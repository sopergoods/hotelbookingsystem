<?php
include 'Booking.php';

$pageTitle = "Bookings";
$booking = new Booking();
$bookings = $booking->all();

include 'admin_header.php';
?>

<h2 class="h4 mb-3">Bookings</h2>

<?php
// Show success/error messages
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'deleted') {
        echo '<div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Booking deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }
}

if (isset($_GET['error'])) {
    $error_msg = 'An error occurred.';
    if ($_GET['error'] === 'delete_failed') {
        $error_msg = 'Failed to delete booking.';
    } elseif ($_GET['error'] === 'invalid_id') {
        $error_msg = 'Invalid booking ID.';
    }
    echo '<div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> ' . $error_msg . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<?php if (empty($bookings)): ?>
    <div class="alert alert-info">No bookings found.</div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Room</th>
                            <th>Guest</th>
                            <th>Dates</th>
                            <th>Guests</th>
                            <th>Total (₱)</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><?= $b['id'] ?></td>
                            <td><?= htmlspecialchars($b['room_name']) ?></td>
                            <td>
                                <?= htmlspecialchars($b['guest_name']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($b['guest_email']) ?></small>
                            </td>
                            <td>
                                <?= date('M j', strtotime($b['check_in'])) ?> → 
                                <?= date('M j, Y', strtotime($b['check_out'])) ?>
                            </td>
                            <td><?= $b['guests'] ?></td>
                            <td>$<?= number_format($b['total_price'], 2) ?></td>
                            <td>
                                <a href="view_booking.php?id=<?= $b['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="View Details">
                                    👁️ View
                                </a>
                                <a href="delete_booking.php?id=<?= $b['id'] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete this booking?')"
                                   title="Delete Booking">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'admin_footer.php'; ?>