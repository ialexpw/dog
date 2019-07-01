<?php
    /**
     * ping.php
     *
     * The ping functions
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2019 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */

    # Ping domain
    if(isset($_GET['domain']) && !empty($_GET['domain'])) {
        # SSL?
        if(isset($_GET['ssl'])) {
            $pRes = PingDomain($_GET['domain'], 1);
        }else{
            $pRes = PingDomain($_GET['domain']);
        }

        # Response
        echo $pRes;
    }

    /**
     * Dog::PingDomain($domain, $port)
     * Ping a domain and return the response time in ms
     */
    function PingDomain($domain, $port) {
        # Start timer
        $sTime = microtime(true);

        # Ping a chosen port
        $fsOpen = @fsockopen($domain, $port, $errNo, $errstr, 2);

        # Stop timer
        $eTime = microtime(true);

        # Site is down
        if(!$fsOpen) {
            $status = -1;
        }else{
            $status = floor(($eTime - $sTime) * 1000);
        }

        @fclose($fsOpen);

        return $status;
    }
?>
