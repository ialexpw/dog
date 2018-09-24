<?php
    /**
     * dog.app.php
     *
     * Main functions file, contains the main Dog class
     *
     * @package    tellDog
     * @author     Alex White (https://github.com/ialexpw)
     * @copyright  2018 tellDog
     * @license    https://github.com/ialexpw/dog/blob/master/LICENSE  MIT License
     * @link       https://viro.app
     */

    # Start the session
    if(!headers_sent()) {
		session_start();
    }

    /**
     * Dog Class
     */
    class Dog {

        /**
         * Dog::Connect()
         * Connect to the SQLite database and return the database query
         */
        public static function Connect() {
            $db = new SQLite3('app/db/dog.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
            return $db;
        }

        /**
         * Dog::InstallDatabase()
         * Install the database tables needed for tellDog
         */
        public static function InstallDatabase() {
            $db = new SQLite3('app/db/dog.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

            $db->query('CREATE TABLE users (
                id integer PRIMARY KEY AUTOINCREMENT,
                username varchar,
                password varchar,
                nti_email varchar,
                nti_phone varchar,
                active integer
            )');
            
            $db->query('CREATE TABLE monitors (
                id integer PRIMARY KEY AUTOINCREMENT,
                name varchar,
                description varchar,
                address varchar,
                location varchar,
                public integer,
                u_id integer
                FOREIGN KEY(u_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');
            
            $db->query('CREATE TABLE records (
                id integer PRIMARY KEY AUTOINCREMENT,
                response varchar,
                timestamp varchar,
                m_id varchar
                FOREIGN KEY(m_id) REFERENCES monitors(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');

            $db->close();
        }

        /**
         * Dog::LoggedIn()
         * Check logged in status by looking at the sessions
         */
        public static function LoggedIn() {
            if(!isset($_SESSION['UserID']) || !isset($_SESSION['Username'])) {
				return 0;
			}else{
				return 1;
			}
        }

        /**
         * Dog::PingDomain($domain, $ssl)
         * Ping a domain and return the response time in ms
         */
        public static function PingDomain($domain, $ssl) {
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
                $status = floor(($stoTime - $staTime) * 1000);
            }

            @fclose($fsOpen);

            return $status;
        }

        /**
         * Dog::LoadView($view)
         * Load a view by including the relevant php file, if not found then load the 404
         */
        public static function LoadView($view) {
            if(file_exists('app/tpl/' . $view . '.php')) {
                include 'app/tpl/' . $view . '.php';
            }else{
                include 'app/tpl/404.php';
            }
        }

        /**
         * Dog::Translate($string, $lang)
         * Taking in the string to translate and the language file, this will replace $string with the translation
         */
        public static function Translate($string, $lang) {
            if(!empty($lang[$string])) {
                echo $lang[$string];
            }else{
                echo 'TRANSLATION-ERROR';
            }
        }

        /**
         * Dog::Version()
         * Return the current version of ViroCMS
         */
        public static function Version() {
            return "v0.1.0-alpha";
        }

        /**
         * Dog::CheckUpdate()
         * Check if updates are available using Dog::Version() and the website
         */
        public static function CheckUpdate() {
            # Get the current version
            $getVer = file_get_contents('https://viro.app/dog-version.txt');

            # Get our version
            $locVer = explode('-', Dog::Version());
            $locVer = str_replace('v', '', $locVer[0]);

            # true = update available
            if($getVer != $locVer) {
                return true;
            }else{
                return false;
            }
        }

        /**
         * Dog::Installed()
         * Check the installation status
         */
        public static function Installed() {
            if(!file_exists('app/db/dog.db')) {
                return false;
            }else{
                return true;
            }
        }
    }
?>