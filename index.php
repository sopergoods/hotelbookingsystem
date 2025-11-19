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
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            height: 500px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-content { 
            text-align: center;
            color: #fff; 
            z-index: 2;
            max-width: 800px;
            padding: 0 2rem;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }
        
        .hero .btn {
            padding: 0.875rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        
        .hero .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
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
        
        .section-title {
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: #3b82f6;
            border-radius: 2px;
        }
        
        @media (max-width: 768px) {
            .hero {
                height: 400px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<?php include 'public_navbar.php'; ?>

<div class="hero">
    <div class="hero-content">
        <h1>Find Your Perfect Stay</h1>
        <p>Book comfortable rooms at the best prices</p>
        <a href="search.php" class="btn btn-light btn-lg">Search Availability</a>
    </div>
</div>

<div class="container py-5">
    <h2 class="section-title">Available Rooms</h2>

    <div class="row g-4">
        <?php foreach ($rooms as $room): ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <?php 
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
                        <a href="book.php?id=<?= $room['id'] ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>