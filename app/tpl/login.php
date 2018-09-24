<?php
    /**
     * login.php
     *
     * Login form
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2018 tellDog
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
        <meta charset="utf-8">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>tellDog - <?php Dog::Translate('Login', $l); ?></title>

        <!-- Styles -->
        <link rel="stylesheet" href="app/tpl/css/bootstrap.min.css">
    </head>

    <body>
        <nav class="navbar navbar-dark bg-primary">
            <!-- Navbar content -->
        </nav>
        <div class="container">
            <h3>Test</h3>
        </div>
        <div>
            &copy; 2018 tellDog - <?php echo Dog::Version(); ?>.
        </div>
    </body>
</html>