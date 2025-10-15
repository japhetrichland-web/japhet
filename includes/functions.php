<?php
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function get_projects($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM projects ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT :limit";
    }
    $stmt = $pdo->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_skills() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY category, name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>