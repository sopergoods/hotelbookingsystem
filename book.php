<?php
include 'Room.php';
include_once 'user_auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$roomObj = new Room();
$data = $roomObj->find($id);
if (!$data) {
    die("Room not found.");
}

$check_in  = $_GET['ci'] ?? '';
$check_out = $_GET['co'] ?? '';
$guests    = $_GET['g'] ?? 1;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-5">
    <h2 class="mb-3">Book: <?= htmlspecialchars($data['name']) ?></h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Type:</strong> <?= htmlspecialchars($data['type']) ?></p>
            <p><strong>Price per night:</strong> $<?= number_format($data['price_per_night'], 2) ?></p>

            <form method="POST" action="booking_submit.php">
                <input type="hidden" name="room_id" value="<?= $data['id'] ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Guest Name</label>
                        <input type="text" name="guest_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="guest_email" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guests</label>
                        <input type="number" name="guests" class="form-control" value="<?= htmlspecialchars($guests) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="check_in" class="form-control" value="<?= htmlspecialchars($check_in) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="check_out" class="form-control" value="<?= htmlspecialchars($check_out) ?>" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary">Submit Booking</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>
