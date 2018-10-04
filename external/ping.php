<?php
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
     * Dog::PingDomain($domain, $ssl)
     * Ping a domain and return the response time in ms
     */
    function PingDomain($domain, $ssl=0) {
        $sTime = microtime(true);

        if($ssl) {
            $fsOpen = @fsockopen($domain, 443, $errNo, $errstr, 2);
        }else{
            $fsOpen = @fsockopen($domain, 80, $errNo, $errstr, 2);
        }

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