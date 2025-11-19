<?php
include 'Room.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: rooms_index.php');
    exit;
}

$roomObj = new Room();
$data = $roomObj->find($id);
if (!$data) {
    die("Room not found.");
}

$pageTitle = "View Room";
include 'admin_header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Room Details</h2>

        <p><strong>ID:</strong> <?= $data['id'] ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($data['type']) ?></p>
        <p><strong>Price per Night:</strong> $<?= number_format($data['price_per_night'], 2) ?></p>
        <p><strong>Max Guests:</strong> <?= $data['max_guests'] ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($data['description'])) ?></p>
        <p><strong>Created:</strong> <?= $data['created_at'] ?></p>

        <a href="rooms_index.php" class="btn btn-secondary mt-3">← Back to Rooms</a>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
