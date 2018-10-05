<?php
    /**
     * index.php
     *
     * Main templating file, with redirection
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

    include 'app/dog.app.php';
    include 'app/lang/en.php';

    # Check installation state
    if(!Dog::Installed()) {
        echo '<p align="center">Please ensure the app/db folder is writable to the server and you have run the <a href="install.php">installer</a>.</p>';
        die();
    }

    # Logout
    if(Dog::LoggedIn() && isset($_GET['logout'])) {
        session_destroy();
        session_start();
    }

    # Simple templating
    if(Dog::LoggedIn()) {
        if(!isset($_GET['page']) || empty($_GET['page'])) {
            Dog::LoadView('dashboard');
        }else{
            Dog::LoadView($_GET['page']);
        }
    }else{
        Dog::LoadView('login');
    }
?>