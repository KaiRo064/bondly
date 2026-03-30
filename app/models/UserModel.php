public function getById($id) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

public function updateProfile($id, $fullName, $bio, $image) {
    $stmt = $this->db->prepare("UPDATE users SET full_name = ?, bio = ?, profile_image = ? WHERE id = ?");
    return $stmt->execute([$fullName, $bio, $image, $id]);
}