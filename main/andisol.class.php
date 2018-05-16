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

/****************************************************************************************************************************/
/**
This class is the principal Model layer interface for the Rift Valley Platform. You instantiate an instance of this class, and
it, in turn, will create an instance of CHAMELEON/BADGER, and, possibly, an instance of COBRA. That establishes a connection
to the lower level data storage and security infrastructure.

You are to use this class for ALL access to the lower level functionality.
 */
class Andisol {
    private $_chameleon_instance = NULL;    ///< This is the CHAMELEON instance.
    private $_cobra_instance = NULL;        ///< This is the COBRA instance.
    
    var $version;                           ///< The version indicator.
    var $error;                             ///< Any errors that occured are kept here.
        
    /************************************************************************************************************************/    
    /***********************/
    /**
    The constructor.
     */
	public function __construct(    $in_login_id = NULL,        ///< The login ID
                                    $in_hashed_password = NULL, ///< The password, crypt-hashed
                                    $in_raw_password = NULL     ///< The password, cleartext.
	                            ) {
        $this->class_description = 'The main model interface class.';
	    $this->version = __ANDISOL_VERSION__;
	    
	    // The first thing we do, is set up any login instance, as well as any possible COBRA instance.
        $chameleon_instance = new CO_Chameleon($in_login_id, $in_hashed_password, $in_raw_password);
        if (isset($chameleon_instance) && ($chameleon_instance instanceof CO_Chameleon)) {
            if ($chameleon_instance->valid) {
                $this->_chameleon_instance = $chameleon_instance;
                
                $login_item = $chameleon_instance->get_login_item();
                
                // COBRA requires a manager (or God).
                if (isset($login_item) && ($chameleon_instance->god_mode() || ($login_item instanceof CO_Login_Manager))) {
                    $cobra_instance = CO_Cobra::make_cobra($chameleon_instance);
        
                    if (isset($cobra_instance) && ($cobra_instance instanceof CO_Cobra)) {
                        $this->_cobra_instance = $cobra_instance;
                    } elseif (isset($cobra_instance) && ($cobra_instance->error instanceof LGV_Error)) {
                        $this->error = $cobra_instance->error;
                    }
                }
            } elseif (isset($chameleon_instance) && ($chameleon_instance->error instanceof LGV_Error)) {
                $this->error = $chameleon_instance->error;
            }
        }
        
        // At this point, we have (or have not) logged in, and any infrastructure for logged-in operations is in place.
    }
    
    /************************************************************************************************************************/    
    /*                                              BASIC LOGIN STATUS QUERIES                                              */
    /************************************************************************************************************************/    

    /***********************/
    /**
    \returns TRUE, if we have an active database connection (as represented by an active CHAMELEON instance).
     */
    public function valid() {
        return isset($this->_chameleon_instance) && ($this->_chameleon_instance instanceof CO_Chameleon);
    }
    
    /***********************/
    /**
    \returns TRUE, if we have actually logged into the CHAMELEON instance.
     */
    public function logged_in() {
        return $this->valid() && ($this->_chameleon_instance->get_login_item() instanceof CO_Security_Login);
    }
    
    /***********************/
    /**
    \returns TRUE, if we are logged in as a COBRA Login Manager.
     */
    public function manager() {
        return isset($this->_cobra_instance) && ($this->_cobra_instance instanceof CO_Cobra);
    }
    
    /***********************/
    /**
    \returns TRUE, if we are logged in as the "God" admin ID.
     */
    public function god() {
        return $this->valid() && $this->_chameleon_instance->god_mode();
    }
    
    /***********************/
    /**
    \returns The current login Item. NULL if no login.
     */
    public function current_login() {
        $ret = NULL;
    
        if ($this->logged_in()) {
            $ret = $this->_chameleon_instance->get_login_item();
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns The current user Item. NULL, if no user for the current login.
     */
    public function current_user() {
        $ret = NULL;
        $login_item = $this->current_login();
        if ($login_item) {
            $login_id = $login_item->id();
            $ret = $this->_chameleon_instance->get_user_from_login($login_id);
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns the user collection object for a given login. If there is no login given, then the current login is assumed. This is subject to security restrictions.
     */
    public function get_user_from_login(    $in_login_id = NULL ///< The login ID that is associated with the user collection. If NULL, then the current login is used.
                                        ) {
        return $this->_chameleon_instance->get_user_from_login($in_login_id);
    }
};
