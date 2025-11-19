<?php
include 'Room.php';

$pageTitle = "Add Room";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $price       = $_POST['price_per_night'] ?? '';
    $max_guests  = $_POST['max_guests'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if (empty($name) || empty($type) || empty($price) || empty($max_guests)) {
        $error = "Please fill in all required fields.";
    } elseif (!is_numeric($price) || !is_numeric($max_guests)) {
        $error = "Price and Max Guests must be numeric.";
    } else {
        $room = new Room();
        $room->name = $name;
        $room->type = $type;
        $room->price_per_night = (float)$price;
        $room->max_guests = (int)$max_guests;
        $room->description = $description;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_path = $room->uploadImage($_FILES['image']);
            if ($image_path) {
                $room->image_path = $image_path;
            } else {
                $error = "Failed to upload image. Please use JPG, PNG, GIF or WEBP (max 5MB).";
            }
        } else {
            $room->image_path = null; // No image uploaded
        }

        if (empty($error) && $room->add()) {
            header('Location: rooms_index.php');
            exit;
        } elseif (empty($error)) {
            $error = "Failed to add room.";
        }
    }
}

include 'admin_header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h4 mb-3">Add Room</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name*</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Type*</label>
                <input type="text" name="type" class="form-control"
                       value="<?= htmlspecialchars($_POST['type'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price per Night* ($)</label>
                <input type="text" name="price_per_night" class="form-control"
                       value="<?= htmlspecialchars($_POST['price_per_night'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Guests*</label>
                <input type="number" name="max_guests" class="form-control"
                       value="<?= htmlspecialchars($_POST['max_guests'] ?? '') ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            
            <!-- IMAGE UPLOAD SECTION -->
            <div class="col-12">
                <label class="form-label">Room Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                <small class="text-muted">Accepted: JPG, PNG, GIF, WEBP (Max 5MB)</small>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-3" style="display: none;">
                    <p class="text-muted mb-2">Preview:</p>
                    <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                </div>
            </div>
            
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="rooms_index.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Room</button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
            alert('File is too large! Maximum size is 5MB.');
            this.value = '';
            document.getElementById('imagePreview').style.display = 'none';
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Invalid file type! Please use JPG, PNG, GIF, or WEBP.');
            this.value = '';
            document.getElementById('imagePreview').style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('preview').src = event.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});
</script>

<?php include 'admin_footer.php'; ?>