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
                desc varchar,
                addr varchar,
                ipaddr varchar,
                loc varchar,
                public integer,
                u_id integer,
                FOREIGN KEY(u_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');
            
            $db->query('CREATE TABLE records (
                id integer PRIMARY KEY AUTOINCREMENT,
                response varchar,
                timestamp varchar,
                m_id varchar,
                FOREIGN KEY(m_id) REFERENCES monitors(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');

            $db->query('CREATE TABLE external (
                id integer PRIMARY KEY AUTOINCREMENT,
                code varchar,
                loc varchar,
                addr varchar,
                active integer,
                u_id integer,
                FOREIGN KEY(u_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');

            $db->query('CREATE TABLE auditlog (
                id integer PRIMARY KEY AUTOINCREMENT,
                audtype varchar,
                desc varchar,
                timestamp varchar,
                u_id integer,
                FOREIGN KEY(u_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
            )');

            $db->close();
        }

        /**
         * Dog::GenerateData()
         * Generate the default data into the database, including the admin user, a monitor and the externals
         */
        public static function GenerateData() {
            $db = new SQLite3('app/db/dog.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

            $db->exec('BEGIN');

            # Generate default password hash
            $adUser = password_hash("password", PASSWORD_DEFAULT);

            # Admin user
            $db->query('INSERT INTO users ("username", "password", "active")
                        VALUES ("admin", "' . $adUser . '", "2")');

            # Example monitor
            $db->query('INSERT INTO monitors ("name", "desc", "addr", "ipaddr", "loc", "public", "u_id")
                        VALUES ("Example monitor", "Information about this monitor", "google.co.uk", "' . gethostbyname('google.co.uk') . '", "1", "1", "1")');

            # External monitors
            $db->query('INSERT INTO external ("code", "loc", "addr", "active", "u_id")
                        VALUES ("de", "Germany", "ping-de1.telldog.com", "1", "1")');

            $db->query('INSERT INTO external ("code", "loc", "addr", "active", "u_id")
                        VALUES ("us", "United States", "ping-us1.telldog.com", "1", "1")');

            $db->query('INSERT INTO external ("code", "loc", "addr", "active", "u_id")
                        VALUES ("fi", "Finland", "ping-fi1.telldog.com", "1", "1")');

            $db->exec('COMMIT');

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
        public static function PingDomain($domain, $ssl=0) {
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

        /**
         * Dog::GetExternalPing($domain, $id)
         * Get a Ping value from the External monitors, providing the domain and ID of the Ping server
         */
        public static function GetExternalPing($domain, $id) {
            $db = new SQLite3('app/db/dog.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

            # Find the external server
            $getExternal = $db->prepare('SELECT * FROM external WHERE id = :id');
            $getExternal->bindValue(':id', $id);
            $getExternalRes = $getExternal->execute();

            $getPing = file_get_contents('http://' . $getExternalRes[0]['addr'] . '/ping.php?domain=' . $domain);

            return $getPing;
        }

        public static function AddToAudit($type, $info) {
            # Switch type
            return;
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
         * Dog::LoadPage($page)
         * Load a page with a header redirect
         */
        public static function LoadPage($page) {
            header("Location: index.php?page=" . $page);
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