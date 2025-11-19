<?php
include 'Room.php';
include 'Booking.php';
include_once 'user_auth.php';

$rooms = [];
$error = "";
$searchPerformed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in  = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests    = $_POST['guests'];

    if ($check_in >= $check_out) {
        $error = "Check-out date must be after check-in.";
    } else {
        $searchPerformed = true;
        $roomObj = new Room();
        $allRooms = $roomObj->all();
        $bookingObj = new Booking();

        foreach ($allRooms as $room) {
            $bookingObj->room_id = $room['id'];
            $bookingObj->check_in  = $check_in;
            $bookingObj->check_out = $check_out;

            if ($room['max_guests'] >= $guests && $bookingObj->isRoomAvailable()) {
                $rooms[] = $room;
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Search Rooms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-4">
    <h2 class="mb-3">Search Availability</h2>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Check-in</label>
            <input type="date" name="check_in" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Check-out</label>
            <input type="date" name="check_out" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Guests</label>
            <input type="number" name="guests" class="form-control" min="1" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($searchPerformed): ?>
        <h4 class="mb-3">Available Rooms</h4>
        <?php if (empty($rooms)): ?>
            <div class="alert alert-warning">No rooms available.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($rooms as $room): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=900&q=60"
                             class="card-img-top" alt="Room">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($room['type']) ?></p>
                            <p><?= htmlspecialchars(substr($room['description'],0,80)) ?>...</p>
                            <div class="d-flex justify-content-between">
                                <strong>$<?= number_format($room['price_per_night'],2) ?>/night</strong>
                                <a href="book.php?id=<?= $room['id'] ?>&ci=<?= $check_in ?>&co=<?= $check_out ?>&g=<?= $guests ?>"
                                   class="btn btn-primary btn-sm">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
