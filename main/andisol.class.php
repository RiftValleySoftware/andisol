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

if ( !defined('LGV_LANG_CATCHER') ) {
    define('LGV_LANG_CATCHER', 1);
}

require_once(CO_Config::lang_class_dir().'/common.inc.php');

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
    /*                                                COMPONENT ACCESS METHODS                                              */
    /************************************************************************************************************************/    

    /***********************/
    /**
    \returns The COBRA instance. It will be NULL if the current login is not a manager or God.
     */
    public function get_cobra_instance() {
        return $this->_cobra_instance;
    }
    
    /***********************/
    /**
    \returns The CHAMELEON instance.
     */
    public function get_chameleon_instance() {
        return $this->_chameleon_instance;
    }
    
    /************************************************************************************************************************/    
    /*                                              BASIC LOGIN STATUS QUERIES                                              */
    /************************************************************************************************************************/    

    /***********************/
    /**
    \returns TRUE, if we have an active database connection (as represented by an active CHAMELEON instance).
     */
    public function valid() {
        return (NULL != $this->get_chameleon_instance()) && ($this->get_chameleon_instance() instanceof CO_Chameleon);
    }
    
    /***********************/
    /**
    \returns TRUE, if we have actually logged into the CHAMELEON instance.
     */
    public function logged_in() {
        return $this->valid() && ($this->get_chameleon_instance()->get_login_item() instanceof CO_Security_Login);
    }
    
    /***********************/
    /**
    \returns TRUE, if we are logged in as a COBRA Login Manager or as God.
     */
    public function manager() {
        return (NULL != $this->get_cobra_instance()) && ($this->get_cobra_instance() instanceof CO_Cobra);
    }
    
    /***********************/
    /**
    \returns TRUE, if we are logged in as the "God" admin ID.
     */
    public function god() {
        return $this->valid() && $this->get_chameleon_instance()->god_mode();
    }
    
    /************************************************************************************************************************/    
    /*                                                  USER ACCESS METHODS                                                 */
    /************************************************************************************************************************/    

    /***********************/
    /**
    This returns the actual security DB login item for the requested user (or the current logged-in user).
    
    The response is subject to standard security vetting, so there is a possibility that nothing will be returned, when there is an existing login at that ID.
    
    \returns the instance for the requested user.
     */
    public function get_login_item( $in_login_id = NULL ///< The integer login ID to check. If not-NULL, then the ID of a login instance. It must be one that the current user can see. If NULL, then the current user.
                                    ) {
        return $this->get_chameleon_instance()->get_login_item($in_login_id);
    }
        
    /***********************/
    /**
    \returns the user collection object for a given login. If there is no login given, then the current login is assumed. This is subject to security restrictions.
     */
    public function get_user_from_login(    $in_login_id = NULL,                ///< The login ID that is associated with the user collection. If NULL, then the current login is used.
                                            $in_make_user_if_necessary = FALSE  ///< If TRUE (Default is FALSE), then the user will be created if it does not already exist. Ignored, if we are not a Login Manager.
                                        ) {
        if ($in_make_user_if_necessary && $this->manager()) {   // See if we are a manager, and they want to maybe create a new user.
            return $this->get_cobra_instance()->get_user_from_login($in_login_id, $in_make_user_if_necessary);
        } else {
            return $this->get_chameleon_instance()->get_user_from_login($in_login_id);
        }
    }
    
    /***********************/
    /**
    \returns The current login Item. NULL if no login.
     */
    public function current_login() {
        return $this->get_login_item();
    }
    
    /***********************/
    /**
    \returns The current user Item. NULL, if no user for the current login.
     */
    public function current_user() {
        return $this->get_user_from_login();
    }
    
    /***********************/
    /**
    \returns an array of instances of all the logins that are visible to the current user (or a supplied user, if in "God" mode).
     */
    public function get_all_logins( $and_write = FALSE,         ///< If TRUE, then we only want ones we have write access to.
                                    $in_login_id = NULL,        ///< This is ignored, unless this is the God login. If We are logged in as God, then we can select a login via its string login ID, and see what logins are available to it.
                                    $in_login_integer_id = NULL ///< This is ignored, unless this is the God login and $in_login_id is not specified. If We are logged in as God, then we can select a login via its integer login ID, and see what logins are available to it.
                                    ) {
        $ret = Array();
        
        if ($this->manager()) { // Don't even bother unless we're a manager.
            return $this->get_cobra_instance()->get_all_logins($and_write, $in_login_id, $in_login_integer_id);
        }
        
        return $ret;
    }

    /***********************/
    /**
    Test an item to see which logins can access it.
    
    This is security-limited.
    
    \returns an array of instances of CO_Security_Login (Security Database login) items that can read/see the given item. If the read ID is 0 (open), then the function simply returns TRUE. If nothing can see the item, then FALSE is returned.
     */
    public function who_can_see(    $in_test_target ///< This is a subclass of A_CO_DB_Table_Base (General Database Record).
                                ) {
        $ret = Array();
        
        if ($this->manager()) { // Don't even bother unless we're a manager.
            return $this->get_cobra_instance()->who_can_see($in_test_target);
        }
        
        return $ret;
    }
    
    /************************************************************************************************************************/    
    /*                                                   DATA SEARCH METHODS                                                */
    /************************************************************************************************************************/    

    /***********************/
    /**
    This is a "generic" data search. It can be called from external user contexts, and allows a fairly generalized search of the "data" datasource.
    Sorting will be done for the "owner" and "location" values. "owner" will be sorted by the ID of the returned records, and "location" will be by distance from the center.
    
    String searches are always case-insensitive.
    
    \returns an array of instances (or integers, if $ids_only is TRUE) that match the search parameters. If $count_only is TRUE, then it will be a single integer, with the count of responses to the search (if a page, then this count will only be the number of items on that page).
     */
    public function generic_search( $in_search_parameters = NULL,   /**<    This is an associative array of terms to define the search. The keys should be:
                                                                                - 'id'
                                                                                    This should be accompanied by an array of one or more integers, representing specific item IDs.
                                                                                - 'access_class'
                                                                                    This should be accompanied by an array, containing one or more PHP class names.
                                                                                - 'name'
                                                                                    This will contain a case-insensitive array of strings to check against the object_name column.
                                                                                - 'owner'
                                                                                    This should be accompanied by an array of one or more integers, representing specific item IDs for "owner" objects.
                                                                                - 'tags'
                                                                                    This should be accompanied by an array (up to 10 elements) of one or more case-insensitive strings, representing specific tag values.
                                                                                    The position in the array denotes which tag to match, so unchecked tags should still be in the array, but empty. You don't match empty tags.
                                                                                    You can specify an array for the values, which allows you to do an OR search for the values.
                                                                                - 'location'
                                                                                    This is only relevant if we are searching for subclasses (or instances) of CO_LL_Location
                                                                                    This requires that the parameter be a 3-element associative array of floating-point numbers:
                                                                                        - 'longitude'
                                                                                            This is the search center location longitude, in degrees.
                                                                                        - 'latitude'
                                                                                            This is the search center location latitude, in degrees.
                                                                                        - 'radius'
                                                                                            This is the search radius, in Kilometers.
    
                                                                            You can specify an array for any one of the values, which allows you to do an OR search for those values ($or_search does not apply. It is only for the combination of main values).
                                                                            If you add an element called 'use_like' ('use_like' => 1) to the end of 'access_class', 'name' or one of the 'tags', then you can use SQL-style "wildcards" (%) in your matches.
                                                                    */
                                    $or_search = FALSE,             ///< If TRUE, then the search is very wide (OR), as opposed to narrow (AND), by default. If you specify a location, then that will always be AND, but the other fields can be OR. Tags will always be searched as OR.
                                    $page_size = 0,                 ///< If specified with a 1-based integer, this denotes the size of a "page" of results. NOTE: This is only applicable to MySQL or Postgres, and will be ignored if the DB is not MySQL or Postgres. Default is 0.
                                    $initial_page = 0,              ///< This is ignored unless $page_size is greater than 0. In that case, this 0-based index will specify which page of results to return. Values beyond the maximum number of pages will result in no returned values.
                                    $and_writeable = FALSE,         ///< If TRUE, then we only want records we can modify.
                                    $count_only = FALSE,            ///< If TRUE (default is FALSE), then only a single integer will be returned, with the count of items that fit the search.
                                    $ids_only = FALSE               ///< If TRUE (default is FALSE), then the return array will consist only of integers (the object IDs). If $count_only is TRUE, this is ignored.
                                    ) {
        return $this->get_chameleon_instance()->generic_search($in_search_parameters, $or_search, $page_size, $initial_page, $and_writeable, $count_only, $ids_only);
    }
    
    /***********************/
    /**
    This is a search, based on a location and radius around that location.
    Only objects that have a longitude and latitude that fall within the radius will be returned.
    All visible classes and instances will be returned. Only location and security filtering are applied.
    
    \returns an array of instances (or integers, if $ids_only is TRUE) that fit within the location center and radius. If $count_only is TRUE, then it will be a single integer, with the count of responses to the search (if a page, then this count will only be the number of items on that page).
     */
    public function location_search(    $in_longitude_degrees,  ///< REQUIRED: The latitude of the center, in degrees.
                                        $in_latitude_degrees,   ///< REQUIRED: The logitude of the center, in degrees.
                                        $in_radius_kilometers,  ///< REQUIRED: The search radius, in Kilomters.
                                        $page_size = 0,         ///< OPTIONAL: If specified with a 1-based integer, this denotes the size of a "page" of results. NOTE: This is only applicable to MySQL or Postgres, and will be ignored if the DB is not MySQL or Postgres. Default is 0.
                                        $initial_page = 0,      ///< OPTIONAL: This is ignored unless $page_size is greater than 0. If so, then this 0-based index will specify which page of results to return. Values beyond the maximum number of pages will result in no returned values.
                                        $and_writeable = FALSE, ///< OPTIONAL: If TRUE, then we only want records we can modify.
                                        $count_only = FALSE,    ///< OPTIONAL: If TRUE (default is FALSE), then only a single integer will be returned, with the count of items that fit the search.
                                        $ids_only = FALSE       ///< OPTIONAL: If TRUE (default is FALSE), then the return array will consist only of integers (the object IDs). If $count_only is TRUE, this is ignored.
                                    ) {
        return $this->generic_search(Array('location' => Array('longitude' => $in_longitude_degrees, 'latitude' => $in_latitude_degrees, 'radius' => $in_radius_kilometers)), FALSE, $page_size, $initial_page, $and_writeable, $count_only, $ids_only);
    }
};
