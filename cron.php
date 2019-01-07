<?php
    /**
     * cron.php
     *
     * Main cron to poll servers
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2018 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */

    include 'app/dog.app.php';

    # Run from the CLI
    if(php_sapi_name() == "cli") {
        
    }
?>