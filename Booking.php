<?php

include_once 'Dbh.php';

class Booking extends Dbh {

    public $id;
    public $room_id;
    public $user_id;
    public $guest_name;
    public $guest_email;
    public $guests;
    public $check_in;
    public $check_out;
    public $total_price;

    public function isRoomAvailable() {
        $conn = $this->connect();

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM bookings
            WHERE room_id = ?
            AND NOT (check_out <= ? OR check_in >= ?)
        ");
        $stmt->bind_param("iss", $this->room_id, $this->check_in, $this->check_out);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $count = $result['total'];

        $stmt->close();
        $conn->close();

        return $count == 0;
    }

    public function add() {
        if (!$this->isRoomAvailable()) {
            return false;
        }

        $conn = $this->connect();

        $stmt = $conn->prepare("
            INSERT INTO bookings
            (room_id, user_id, guest_name, guest_email, guests, check_in, check_out, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $user_id = $this->user_id ? $this->user_id : null;

        $stmt->bind_param(
            "iississd",
            $this->room_id,
            $user_id,
            $this->guest_name,
            $this->guest_email,
            $this->guests,
            $this->check_in,
            $this->check_out,
            $this->total_price
        );

        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function delete() {
        $conn = $this->connect();
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("
            SELECT b.*, r.name AS room_name
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE b.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $data;
    }

    public function all() {
        $conn = $this->connect();
        $result = $conn->query("
            SELECT b.*, r.name AS room_name
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            ORDER BY b.id ASC
        ");

        $bookings = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
        }

        $conn->close();
        return $bookings;
    }

    public function userBookings($user_id) {
        $conn = $this->connect();

        $stmt = $conn->prepare("
            SELECT b.*, r.name AS room_name
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE b.user_id = ?
            ORDER BY b.id DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $bookings = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
        }

        $stmt->close();
        $conn->close();
        return $bookings;
    }
}

?>
