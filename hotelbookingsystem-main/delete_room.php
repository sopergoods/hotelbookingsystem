<?php
include 'Room.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $room = new Room();
    $room->id = $id;
    $room->delete();
}
header('Location: rooms_index.php');
exit;
?>
