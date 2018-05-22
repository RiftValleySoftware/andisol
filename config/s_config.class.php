<?php
/***************************************************************************************************************************/
/**
    ANDISOL Object Model Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
defined( 'LGV_CONFIG_CATCHER' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

/***************************************************************************************************************************/
/**
This file contains the implementation-dependent configuration settings.

This file is an example only, and contains dummy values.
 */
require_once(dirname(dirname(dirname(__FILE__))).'/t_config.interface.php');    // This is the trait with most of the code.

/**
This class is a static class with a global scope. It contains the various configuartion settings for the Rift Valley Platform installation.

We have two separate database, with the main data database holding the actual data, and the security database, holding logins and security tokens.

These databases do not need to have any relations, may be totally different technologies (we are currently limited to MySQL and Postgres), and can even be on different hosts.
 */
class CO_Config {
    static private $_god_mode_id = 2;   ///< God Login Security DB ID. This is private, so it can't be programmatically changed.
    static private $_god_mode_password = <GOD MODE PASSWORD>;   ///< Plaintext password for the God Mode ID login. This overrides anything in the ID row.
    
    static $lang = 'en';                ///< The default language for the server.
    static $min_pw_len = 8;             ///< The minimum password length.
    
    static $data_db_name = <MAIN DB NAME>;                          ///< The PDO name for the main data database.
    static $data_db_host = <MAIN DB HOST (USUALLY 'localhost')>;    ///< The URL name for the main data database host.
    static $data_db_type = <'pgsql' OR 'mysql'>;                    ///< The technology used for the main data database (MySQL or Postgres).
    static $data_db_login = <MAIN DB LOGIN>;                        ///< The string login name for the main data database.
    static $data_db_password = <MAIN DB PASSWORD>;                  ///< The main data database password string.

    static $sec_db_name = <SECURITY DB NAME>;                       ///< The PDO name for the security database.
    static $sec_db_host = <SECURITY DB HOST (USUALLY 'localhost')>; ///< The URL name for the security database host.
    static $sec_db_type = <'pgsql' OR 'mysql'>;                     ///< The technology used for the security database (MySQL or Postgres).
    static $sec_db_login = <SECURITY DB LOGIN>;                     ///< The string login name for the security database.
    static $sec_db_password = <SECURITY DB PASSWORD>;               ///< The security database password string.

    /**
    This is the Google API key. It's required for CHAMELEON to do address lookups and other geocoding tasks.
    CHAMELEON requires this to have at least the Google Geocoding API enabled.
    */
    static $google_api_key = <YOUR GOOGLE API KEY (With Geocoding API)>;
    
    /***********************/
    /**
    \returns the POSIX path to the main ANDISOL directory.
    
    You can change this to reflect a different directory path if you want. It should be absolute POSIX.
     */
    static function base_dir() {
        return dirname(dirname(dirname(__FILE__)));
    }
    
    use tCO_Config; ///< These are the built-in config methods.
}
