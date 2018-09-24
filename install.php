<?php
    /**
     * install.php
     *
     * Installer for tellDog, creating the SQLite database and tables
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2018 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */
    
    include 'app/dog.app.php';

    # If not installed
    if(!Dog::Installed()) {
        Dog::InstallDatabase();
        Dog::GenerateData();
    }
?>