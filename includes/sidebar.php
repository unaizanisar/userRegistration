<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard/index.php">
        <div class="sidebar-brand-text mx-3">
            <img src="../../img/blogslogo.png" alt="Blogs Logo" style="max-height: 150px;">
        </div>
    </a>
    <li class="nav-item">
        <a class="nav-link" href="../dashboard/index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <?php
    $permission_name = 'User Listing'; 
    if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
        <li class="nav-item">
            <a class="nav-link" href="../users/index.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>
    <?php }?>
    <?php
    $permission_name = 'Category Listing'; 
    if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
        <li class="nav-item">
            <a class="nav-link" href="../categories/index.php">
                <i class="fas fa-fw fa-list"></i>
                <span>Categories</span>
            </a>
        </li>
    <?php }?>
    <?php
    $permission_name = 'Blogs Listing'; 
    if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
        <li class="nav-item">
            <a class="nav-link" href="../blogs/index.php">
                <i class="fas fa-fw fa-book"></i>
                <span>Blogs</span>
            </a>
        </li>
    <?php }?>
    <?php
    $permission_name = 'Roles Listing'; 
    if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
        <li class="nav-item">
            <a class="nav-link" href="../roles/index.php">
                <i class="fas fa-fw fa-briefcase"></i>
                <span>Roles</span>
            </a>
        </li>
    <?php }?>
    <?php
    $permission_name = 'Permissions Listing'; 
    if (hasRolePermission($_SESSION['role_id'], $permission_name)) {?>
        <li class="nav-item">
            <a class="nav-link" href="../permissions/index.php">
                <i class="fas fa-fw fa-user-lock"></i>
                <span>Permissions</span>
            </a>
        </li>
    <?php }?>
    <li class="nav-item">
        <a class="nav-link" href="../authentication/logout.php">
            <i class="fas fa-fw fa-sign-out"></i>
            <span>Logout</span>
        </a>
    </li>
</ul>
