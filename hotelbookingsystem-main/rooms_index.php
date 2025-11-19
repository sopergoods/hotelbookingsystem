<?php
include 'Room.php';

$pageTitle = "Rooms";
$room = new Room();
$rooms = $room->all();

include 'admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Rooms</h2>
    <a href="add_room.php" class="btn btn-primary btn-sm">Add Room</a>
</div>

<?php if (empty($rooms)): ?>
    <div class="alert alert-info">No rooms found.</div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price / Night</th>
                            <th>Max Guests</th>
                            <th style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rooms as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['name']) ?></td>
                            <td><?= htmlspecialchars($r['type']) ?></td>
                            <td>$<?= number_format($r['price_per_night'], 2) ?></td>
                            <td><?= $r['max_guests'] ?></td>
                            <td>
                                <a href="view_room.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="edit_room.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="delete_room.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete this room?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'admin_footer.php'; ?>
