<?php
include '../database/db.php';
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

    // Update role name
    $sql = "UPDATE roles SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    if ($stmt->execute()) {
        // Delete old permissions
        $deleteSql = 'DELETE FROM roles_has_permissions WHERE role_id = ?';
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt-> execute();
            $deleteStmt->close();
        // Insert new permissions along with the role_id
        if (!empty($permissions)) {
            $permissionSql = "INSERT INTO roles_has_permissions (role_id, permission_id) VALUES (?, ?)";
            $permissionStmt = $conn->prepare($permissionSql);
            foreach ($permissions as $permissionId) {
                $permissionStmt->bind_param("ii", $id, $permissionId);
                $permissionStmt->execute();
            }
            $permissionStmt->close();
        }

        $_SESSION['edit_message'] = 'Role updated successfully.';
    } else {
        $_SESSION['edit_message'] = 'Error updating role: ' . $stmt->error;
    }
    $stmt->close();
    header("Location: index.php");
    exit();
}
$conn->close();
?>
