<?php

include_once 'dbh.php';

class Room extends Dbh {

    public $id;
    public $name;
    public $type;
    public $price_per_night;
    public $max_guests;
    public $description;
    public $image_path;

    public function add() {
        $conn = $this->connect();
        $stmt = $conn->prepare("
            INSERT INTO rooms (name, type, price_per_night, max_guests, description, image_path)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssdiss",
            $this->name,
            $this->type,
            $this->price_per_night,
            $this->max_guests,
            $this->description,
            $this->image_path
        );
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function update() {
        $conn = $this->connect();
        
        // If image_path is set, update it; otherwise keep the old one
        if ($this->image_path !== null) {
            $stmt = $conn->prepare("
                UPDATE rooms
                SET name = ?, type = ?, price_per_night = ?, max_guests = ?, description = ?, image_path = ?
                WHERE id = ?
            ");
            $stmt->bind_param(
                "ssdissi",
                $this->name,
                $this->type,
                $this->price_per_night,
                $this->max_guests,
                $this->description,
                $this->image_path,
                $this->id
            );
        } else {
            $stmt = $conn->prepare("
                UPDATE rooms
                SET name = ?, type = ?, price_per_night = ?, max_guests = ?, description = ?
                WHERE id = ?
            ");
            $stmt->bind_param(
                "ssdisi",
                $this->name,
                $this->type,
                $this->price_per_night,
                $this->max_guests,
                $this->description,
                $this->id
            );
        }
        
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function delete() {
        // Get the room data to delete the image file
        $room = $this->find($this->id);
        
        $conn = $this->connect();
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        
        // Delete the image file if it exists
        if ($result && $room && !empty($room['image_path'])) {
            $file_path = $room['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        return $result;
    }

    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $row;
    }

    public function all() {
        $conn = $this->connect();
        $result = $conn->query("SELECT * FROM rooms ORDER BY id ASC");
        $rooms = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }

        $conn->close();
        return $rooms;
    }

    /**
     * Upload room image
     * @param array $file - $_FILES['image'] array
     * @return string|false - Returns the file path on success, false on failure
     */
    public function uploadImage($file) {
        // Check if file was uploaded
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file['tmp_name']);
        
        if (!in_array($file_type, $allowed_types)) {
            return false;
        }

        // Validate file size (max 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $max_size) {
            return false;
        }

        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/rooms/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'room_' . uniqid() . '_' . time() . '.' . $extension;
        $file_path = $upload_dir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $file_path;
        }

        return false;
    }

    /**
     * Delete old image file
     * @param string $old_path - Path to the old image file
     */
    public function deleteImage($old_path) {
        if (!empty($old_path) && file_exists($old_path)) {
            unlink($old_path);
        }
    }
}

?>