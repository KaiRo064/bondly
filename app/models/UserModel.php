<?php
class UserModel {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function register($u, $p, $f) {
        $hash = password_hash($p, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)");
        return $stmt->execute([$u, $hash, $f]);
    }

    public function login($u, $p) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$u]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($p, $user['password'])) {
            return $user;
        }
        return false;
    }
}