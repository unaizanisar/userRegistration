<?php
function hasRolePermission($role_id, $permission_name) {
    global $conn;

    // Get permission ID based on the permission name
    $stmt = $conn->prepare("SELECT id FROM permissions WHERE name = ?");
    $stmt->bind_param("s", $permission_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $permission = $result->fetch_assoc();

    if (!$permission) {
        return false; // Permission does not exist
    }
    $permission_id = $permission['id'];
    // Check if the role has the specified permission
    //Prepares an SQL statement to count the number of rows in the roles_has_permissions table where the role_id and permission_id match the given parameters.
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM roles_has_permissions WHERE role_id = ? AND permission_id = ?");
    $stmt->bind_param("ii", $role_id, $permission_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    return $count > 0;
}
function setRoleSession($role_id) {
    global $conn;
    // Get all permissions for the role
    // Prepares an SQL statement to select the name of permissions from the permissions table. It joins this table with roles_has_permissions on the permission ID to filter permissions by the given role ID. The ? is a placeholder for the role ID, which will be bound later.
    $stmt = $conn->prepare("
        SELECT p.name
        FROM permissions p
        INNER JOIN roles_has_permissions rp ON p.id = rp.permission_id 
        WHERE rp.role_id = ?
    ");
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $permissions = $result->fetch_all(MYSQLI_ASSOC);

    // Store permissions in session
    $_SESSION['role_id'] = $role_id;
    $_SESSION['permissions'] = array_column($permissions, 'name'); //stores all permissions for the role in the session.
}
?>