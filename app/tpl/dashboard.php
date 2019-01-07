<?php
    /**
     * dashboard.php
     *
     * Main page that will list the monitors
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2018 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */

    global $l;
    $Connect = Dog::Connect();

    # SELECT Monitors
    $getMonitors = $Connect->prepare('SELECT * FROM monitors WHERE u_id = :u_id');
    $getMonitors->bindValue(':u_id', $_SESSION['UserID']);
    $getMonitorsRes = $getMonitors->execute();

    $getMonitorsRes = $getMonitorsRes->fetchArray(SQLITE3_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="robots" content="index, follow">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>tellDog - <?php Dog::Translate('dashboard', $l); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="app/tpl/css/bootstrap.min.css">
    <link rel="stylesheet" href="app/tpl/css/dashboard.css">

    <!-- Javascript -->
    <script type="text/javascript" src="app/tpl/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">tell<b>Dog</b></a>
        <!--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">-->
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="?logout">Sign out</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky"><br /><br /><br />
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="?page=dashboard">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Server Monitoring</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="menu"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=add-server">
                                <span data-feather="plus-square"></span>
                                Add new
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=manage-server">
                                <span data-feather="hard-drive"></span>
                                Manage
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>External Monitors</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="menu"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=add-external">
                                <span data-feather="plus-square"></span>
                                Add new
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=manage-external">
                                <span data-feather="external-link"></span>
                                Manage
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Tools</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="menu"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=profile">
                                <span data-feather="user"></span>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=audit-log">
                                <span data-feather="file-text"></span>
                                Audit Log
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <p>Welcome to your Dashboard!</p>

                <hr>

                <div class="card" style="width: 18rem;">
                <div class="card-body">
                <h5 class="card-title" align="center">Monitors</h5>
                <p class="card-text" style="font-size:45px;">5</p>
                <!--<a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>-->
                </div>
                </div>

                <?php
                    print_r($getMonitorsRes);
                ?>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
</body>

</html>
