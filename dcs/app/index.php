<?php
session_start();
require_once "../classes/app.class.php";
require_once "../classes/utilities.php";
require_once "../classes/student.class.php";
require_once "../classes/app.class.php";
require_once "../classes/organization.class.php";
require_once "../classes/role.class.php";

$role = Role::getInstance();
$application = Application::getInstance();
if (empty($application->getAccountId())) {
    header("Location: ./login");
}
$page = $_GET["page"] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1" />
    <title><?= $application->getPageTitle() ?></title>
    <link href="../css/bootstrap.min.css"
          rel="stylesheet" />
    <link rel="stylesheet"
          href="../css/main.css">
    <script src="../css/bootstrap.bundle.min.js"></script>
    <script src="../css/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="sidebar ">
        <a href="/"
           class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <span class="fs-4">College of Computing Studies </span>
        </a>
        <hr />
        <?php
        if (!empty($application->getRoles()) && $application->userInRole(['system_admin'])) {
            include './dashboard/menu.php'; ?>
            <hr />
        <?php } ?>
        <div class="dropdown position-absolute bottom-0 start-1">
            <a href="#"
               class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
               id="dropdownUser2"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                <img src="https://github.com/mdo.png"
                     alt="User Avatar"
                     width="32"
                     height="32"
                     class="rounded-circle me-2" />
                <strong>Account</strong>
            </a>
            <ul class="dropdown-menu text-small shadow"
                aria-labelledby="dropdownUser2">

                <li><a class="dropdown-item"
                       href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <header>
        <nav class="navbar navbar-light"
             id="navbar">
            <div class="container-fluid">
                <div class="mb-5"></div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="content-wrapper">
            <?php
            if (!empty($application->getRoles())) {
                if ($application->userInRole(['system_admin'])) {
                    if ($page == 'accounts') {
                        include './accounts/index.php';
                    } elseif ($page == 'organizations') {
                        include './organizations/index.php';
                    } elseif ($page == 'courses') {
                        include './courses/index.php';
                    } else {
                        include "./dashboard/index.php";
                    }
                } else if ($application->userInRole(['dean', 'student_affairs', 'adviser', 'organization_officer'])) {
                    include "./student/student_list.php";
                } else if ($application->userInRole(['student'])) {
                    include "./student/student_profile.php";
                }
            } else {
                echo 'test test';
            }
            ?>
        </div>
    </div>
</body>

</html>