<?php
include 'Booking.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $booking = new Booking();
    $booking->id = $id;
    $booking->delete();
}
header('Location: bookings_index.php');
exit;
?>
