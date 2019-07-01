<?php
    /**
     * login.php
     *
     * Login form
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2019 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    # Logged in? Redirect
    if(Dog::LoggedIn()) {
        Dog::LoadPage('dashboard');
    }

    global $l;
    $Connect = Dog::Connect();

    # POSTing the login form
    if(isset($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
        # User
        $usr = $_POST['username'];

        # SELECT User
        $getUser = $Connect->prepare('SELECT * FROM users WHERE username = :username');
        $getUser->bindValue(':username', $usr);
        $getUserRes = $getUser->execute();

        # Get user data
        $getUserRes = $getUserRes->fetchArray(SQLITE3_ASSOC);

        # User does not exist (add log?)
        if(!$getUserRes) {
            Dog::LoadPage('login&error');
        }

        # Password/login
        if(password_verify($_POST['password'], $getUserRes['password'])) {
            $_SESSION['UserID'] = $getUserRes['id'];
            $_SESSION['Username'] = $getUserRes['username'];

            # Redirect
            Dog::LoadPage('dashboard');
        }else{
            Dog::LoadPage('login&error');
        }
    }
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
                    <h1 class="h2">Log in</h1>
                </div>

                <form action="?page=login" method="post">
                    <div class="form-group row">
                        <label for="username" class="col-sm-1 col-form-label">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="username" name="username" aria-describedby="UserHelp" placeholder="Enter username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-1 col-form-label">Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
    <div>
        &copy; 2018 tellDog - <?php echo Dog::Version(); ?>.
    </div>
</body>

</html>
