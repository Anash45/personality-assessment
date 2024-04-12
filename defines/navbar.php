<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <?php
            if (isAdmin()) {
                ?>
                <li class="nav-item">
                    <a class="nav-link ps-0 mb-1 active" href="dashboard.php">
                        <i class="fa fa-home"></i> Dashboard </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ps-0 mb-1" href="ad_users.php">
                        <i class="fa fa-users"></i> Users </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ps-0 mb-1" href="ad_allowed_users.php">
                        <i class="fa fa-user-plus"></i> Allowed Users </a>
                </li>
                <?php
            }
            ?>
            <li class="nav-item">
                <a class="nav-link ps-0 mb-1" href="assessments.php">
                    <i class="fa fa-poll"></i> Completed Assessments </a>
            </li>
        </ul>
    </div>
</nav>