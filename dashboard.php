<?php
include 'Room.php';
include 'Booking.php';

$pageTitle = "Dashboard";

$roomObj = new Room();
$rooms = $roomObj->all();
$totalRooms = count($rooms);

$bookingObj = new Booking();
$bookings = $bookingObj->all();
$totalBookings = count($bookings);

$upcoming = 0;
$today = date('Y-m-d');
foreach ($bookings as $b) {
    if ($b['check_in'] >= $today) {
        $upcoming++;
    }
}

include 'admin_header.php';
?>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-bg-primary shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Rooms</h5>
                <p class="display-6 mb-0"><?= $totalRooms ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Bookings</h5>
                <p class="display-6 mb-0"><?= $totalBookings ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Upcoming Stays</h5>
                <p class="display-6 mb-0"><?= $upcoming ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
