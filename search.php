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
    <style>
        .room-img { 
            height: 220px; 
            object-fit: cover; 
            width: 100%; 
            transition: transform 0.3s;
        }
        
        .card:hover .room-img {
            transform: scale(1.05);
        }
        
        .card {
            transition: all 0.3s;
            border: none;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .search-form {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Search Availability</h2>

    <form method="POST" class="search-form mb-5">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Check-in</label>
                <input type="date" name="check_in" class="form-control" 
                       value="<?= htmlspecialchars($_POST['check_in'] ?? '') ?>" 
                       min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Check-out</label>
                <input type="date" name="check_out" class="form-control" 
                       value="<?= htmlspecialchars($_POST['check_out'] ?? '') ?>" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Guests</label>
                <input type="number" name="guests" class="form-control" 
                       value="<?= htmlspecialchars($_POST['guests'] ?? '1') ?>" 
                       min="1" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100">Search Rooms</button>
            </div>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($searchPerformed): ?>
        <h4 class="mb-4">Available Rooms</h4>
        <?php if (empty($rooms)): ?>
            <div class="alert alert-warning">
                <strong>No rooms available</strong> for the selected dates and number of guests.
                <br>Please try different dates or adjust the number of guests.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($rooms as $room): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <?php 
                        // Display uploaded image or fallback to placeholder
                        $image_src = !empty($room['image_path']) && file_exists($room['image_path']) 
                            ? $room['image_path'] 
                            : 'https://images.unsplash.com/photo-1560448075-bb4caa6dfb0b?auto=format&fit=crop&w=900&q=60';
                        ?>
                        <img src="<?= htmlspecialchars($image_src) ?>"
                             class="card-img-top room-img" alt="<?= htmlspecialchars($room['name']) ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                            <p class="text-muted mb-2"><?= htmlspecialchars($room['type']) ?></p>
                            <p class="text-secondary"><?= htmlspecialchars(substr($room['description'],0,80)) ?>...</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                <strong class="text-primary fs-5">₱<?= number_format($room['price_per_night'],2) ?>/night</strong>
                                <a href="book.php?id=<?= $room['id'] ?>&ci=<?= $check_in ?>&co=<?= $check_out ?>&g=<?= $guests ?>"
                                   class="btn btn-primary">Book Now</a>
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