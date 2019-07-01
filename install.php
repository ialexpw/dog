<?php
    /**
     * install.php
     *
     * Installer for tellDog, creating the SQLite database and tables
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

    include 'app/dog.app.php';

    # If not installed
    if(!Dog::Installed()) {
        Dog::InstallDatabase();
        Dog::GenerateData();
        Dog::LoadPage('login?installed');
    }
?>
