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
    }
?>