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
defined( 'LGV_ANDISOL_CATCHER' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

define('__ANDISOL_VERSION__', '1.0.0.0000');

if (!defined('LGV_ACCESS_CATCHER')) {
    define('LGV_ACCESS_CATCHER', 1);
}

require_once(CO_Config::cobra_main_class_dir().'/co_cobra.class.php');

$lang = CO_Config::$lang;

global $g_lang_override;    // This allows us to override the configured language at initiation time.

if (isset($g_lang_override) && $g_lang_override && file_exists(CO_Config::lang_class_dir().'/'.$g_lang_override.'.php')) {
    $lang = $g_lang_override;
}

$lang_file = CO_Config::lang_class_dir().'/'.$lang.'.php';
$lang_common_file = CO_Config::lang_class_dir().'/common.inc.php';

if ( !defined('LGV_LANG_CATCHER') ) {
    define('LGV_LANG_CATCHER', 1);
}

require_once($lang_file);
require_once($lang_common_file);

/***************************************************************************************************************************/
/**
 */
class Andisol {
    private $_chameleon_instance = NULL;    ///< This is the CHAMELEON instance.
    private $_cobra_instance = NULL;        ///< This is the COBRA instance.
    
    var $version;                           ///< The version indicator.
        
    /***********************************************************************************************************************/    
    /***********************/
    /**
    The constructor.
    
    We declare it private to prevent it being instantiated outside the factory.
     */
	private function __construct(    $in_chameleon_instance = NULL   ///< The CHAMELEON instance associated with this COBRA instance.
	                            ) {
	    $this->_chameleon_instance = $in_chameleon_instance;
	    $this->version = __ANDISOL_VERSION__;
    }
};
