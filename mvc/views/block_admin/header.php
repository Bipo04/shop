<!DOCTYPE html>
<html lang="vi">

<?php
$page = explode('/', $data['page']);
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo _WEB_ROOT ?>/public/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo _WEB_ROOT ?>/public/admin/css/sb-admin-2.min.css" rel="stylesheet">
    <?php
if($page[0] == 'dashboard') {
    echo '    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="'._WEB_ROOT.'/public/admin/css/dashboard.css">';
}
?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="http://localhost:8088/web/admin/dashboard">
                <div class="sidebar-brand-icon">
                    <img src="<?=_WEB_ROOT?>/public/clients/images/final-01.png" alt="" style="height:50px;">
                </div>
                <div class="sidebar-brand-text mx-3">AngelBaby</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page[0] == 'dashboard') echo 'active'; ?>">
                <a class="nav-link" href="http://localhost:8088/web/admin/dashboard">
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page[0] == 'user' || $page[0] == 'product' || $page[0] == 'category'
                || $page[0] == 'supply' ) echo 'active'; ?>">
                <a class="nav-link <?php if($data['type'] != 'qli') echo 'collapsed'; ?>" href="#"
                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <span>Quản lí</span>
                </a>
                <div id="collapseTwo" class="collapse <?php if($data['type'] == 'qli') echo 'show'; ?>"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Quản lí:</h6>
                        <a class="collapse-item <?php if($page[0] == 'user') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/user">Người dùng</a>
                        <a class="collapse-item <?php if($page[0] == 'product') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/product">Sản phẩm</a>
                        <a class="collapse-item <?php if($page[0] == 'category') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/category">Danh mục</a>
                        <a class="collapse-item <?php if($page[0] == 'supply') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/supply">Nhà cung cấp</a>
                        <a class="collapse-item <?php if($page[0] == 'order') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/order">Đơn hàng</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item <?php if($page[0] == 'revenue' || $page[0] == 'topsale') echo 'active'; ?>">
                <a class="nav-link <?php if($data['type'] != 'bcao') echo 'collapsed'; ?>" href="#"
                    data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <span>Báo cáo</span>
                </a>
                <div id="collapseUtilities" class="collapse <?php if($data['type'] == 'bcao') echo 'show'; ?>"
                    aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Báo cáo:</h6>
                        <a class="collapse-item <?php if($page[0] == 'revenue') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/revenue">Doanh thu</a>
                        <a class="collapse-item <?php if($page[0] == 'topsale') echo 'active'; ?>"
                            href="http://localhost:8088/web/admin/topsale">Top sale</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
                    style="display: flex; justify-content: flex-end;">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow" style="list-style-type: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="img-profile rounded-circle"
                                src="<?php echo _WEB_ROOT ?>/public/admin/img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="http://localhost:8088/web/home">Trang chủ</a>
                            <a class="dropdown-item" href="http://localhost:8088/web/auth/logout">Đăng xuất</a>
                        </div>
                    </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->