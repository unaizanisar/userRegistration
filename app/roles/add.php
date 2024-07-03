<?php
include('../database/db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    
    // Insert the new role
    $sql = "INSERT INTO roles (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $name);
        if($stmt->execute()){
            $roleId = $stmt->insert_id;
            
            // Insert associated permissions
            if (!empty($permissions)) {
                $permissionSql = "INSERT INTO roles_has_permissions (role_id, permission_id) VALUES (?, ?)";
                $permissionStmt = $conn->prepare($permissionSql);
                foreach ($permissions as $permissionId) {
                    $permissionStmt->bind_param("ii", $roleId, $permissionId);
                    $permissionStmt->execute();
                }
                $permissionStmt->close();
            }
            
            session_start();
            $_SESSION['create_message'] = 'New Role Added Successfully.';
            header('Location:index.php');
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add role: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare statement: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
