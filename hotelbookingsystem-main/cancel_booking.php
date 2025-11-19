<?php
// Users can cancel their own bookings
include_once 'user_auth.php';
user_require_login();

include 'Booking.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $bookingObj = new Booking();
    $data = $bookingObj->find($id);
    
    // Security: Make sure user can only cancel their own booking
    if (!$data) {
        header('Location: my_bookings.php?error=not_found');
        exit;
    }
    
    if ($data['user_id'] != $_SESSION['user_id']) {
        header('Location: my_bookings.php?error=access_denied');
        exit;
    }
    
    // Delete the booking
    $bookingObj->id = $id;
    if ($bookingObj->delete()) {
        header('Location: my_bookings.php?success=cancelled');
        exit;
    } else {
        header('Location: my_bookings.php?error=cancel_failed');
        exit;
    }
} else {
    header('Location: my_bookings.php?error=invalid_id');
    exit;
}
?>