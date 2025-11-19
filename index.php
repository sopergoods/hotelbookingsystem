<?php
include 'Room.php';
include_once 'user_auth.php';

$roomObj = new Room();
$rooms = $roomObj->all();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero {
            background: url('https://images.unsplash.com/photo-1501117716987-c8e1ecb2104f?auto=format&fit=crop&w=1200&q=60') center/cover no-repeat;
            height: 380px;
            position: relative;
        }
        .hero-overlay { background: rgba(0,0,0,0.55); position: absolute; inset: 0; }
        .hero-content { position: absolute; bottom: 60px; left: 40px; color: #fff; }
        .room-img { height: 180px; object-fit: cover; width: 100%; }
    </style>
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="hero mb-4">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="display-4 fw-bold">Find Your Perfect Stay</h1>
        <p class="lead">Book comfortable rooms at the best prices.</p>
        <a href="search.php" class="btn btn-light btn-lg">Search Availability</a>
    </div>
</div>

<div class="container pb-5">
    <h2 class="mb-4">Available Rooms</h2>

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
                    <p class="text-muted"><?= htmlspecialchars($room['type']) ?></p>
                    <p><?= htmlspecialchars(substr($room['description'],0,80)) ?>...</p>

                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <strong class="text-primary">₱<?= number_format($room['price_per_night'],2) ?>/night</strong>
                        <a href="book.php?id=<?= $room['id'] ?>" class="btn btn-primary btn-sm">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>