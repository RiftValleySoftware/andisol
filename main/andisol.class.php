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

define('__ANDISOL_VERSION__', '1.0.0.1000');

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
	public function __construct(    $in_login_string_id = NULL, ///< The String login ID
                                    $in_hashed_password = NULL, ///< The password, crypt-hashed
                                    $in_raw_password = NULL     ///< The password, cleartext.
	                            ) {
        $this->class_description = 'The main model interface class.';
	    $this->version = __ANDISOL_VERSION__;
	    $this->error = NULL;
	    
	    // The first thing we do, is set up any login instance, as well as any possible COBRA instance.
        $chameleon_instance = new CO_Chameleon($in_login_string_id, $in_hashed_password, $in_raw_password);
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
    
    /************************************************************************************************************************/    
    /*                                                  USER ACCESS METHODS                                                 */
    /************************************************************************************************************************/    

    /***********************/
    /**
    This returns the actual security DB login item for the requested user (or the current logged-in user).
    
    The response is subject to standard security vetting, so there is a possibility that nothing will be returned, when there is an existing login at that ID.
    
    \returns the instance for the requested user.
     */
    public function get_login_item( $in_login_integer_id = NULL ///< The integer login ID to check. If not-NULL, then the ID of a login instance. It must be one that the current user can see. If NULL, then the current user.
                                    ) {
        $ret = $this->get_chameleon_instance()->get_login_item($in_login_integer_id);
        
        $this->error = $this->get_chameleon_instance()->error;
        
        return $ret;
    }

    /***********************/
    /**
    This returns the actual security DB login item for the requested user (or the current logged-in user).
    
    The response is subject to standard security vetting, so there is a possibility that nothing will be returned, when there is an existing login at that ID.
    
    \returns the instance for the requested user.
     */
    public function get_login_item_by_login_string( $in_login_string_id    ///< The string login ID to check. It must be one that the current user can see.
                                                    ) {
        $ret = $this->get_chameleon_instance()->get_login_item_by_login_string($in_login_string_id);
        
        $this->error = $this->get_chameleon_instance()->error;
        
        return $ret;
    }
        
    /***********************/
    /**
    \returns the user collection object for a given login string. If there is no login given, then the current login is assumed. This is subject to security restrictions.
     */
    public function get_user_from_login_string( $in_login_string_id    ///< The string login ID that is associated with the user collection.   
                                                ) {
        $ret = NULL;
        
        $login_item = $this->get_login_item_by_login_string($in_login_string_id);
        if ($login_item instanceof CO_Security_Login) {
            $ret = $this->get_user_from_login($login_item->id());
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
            $ret = $this->get_cobra_instance()->who_can_see($in_test_target);
        
            $this->error = $this->get_cobra_instance()->error;
        } else {
            $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_authorized,
                                            CO_ANDISOL_Lang::$andisol_error_name_user_not_authorized,
                                            CO_ANDISOL_Lang::$andisol_error_desc_user_not_authorized);
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
    
    All parameters are optional, but calling this "blank" will return the entire data databse (that is visible to the user).
    
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
        $ret = $this->get_chameleon_instance()->generic_search($in_search_parameters, $or_search, $page_size, $initial_page, $and_writeable, $count_only, $ids_only);
        
        $this->error = $this->get_chameleon_instance()->error;
        
        return $ret;
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
        $ret = $this->generic_search(Array('location' => Array('longitude' => $in_longitude_degrees, 'latitude' => $in_latitude_degrees, 'radius' => $in_radius_kilometers)), FALSE, $page_size, $initial_page, $and_writeable, $count_only, $ids_only);
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of instances of all the users (not logins) that are visible to the current login. It should be noted that this can return standalone users.
     */
    public function get_all_users(  $and_write = FALSE  ///< If TRUE (Default is FALSE), then we only want ones we have write access to.
                                    ) {
        $ret = Array();
        
        $temp = $this->generic_search(Array('access_class' => Array('%_User_Collection', 'use_like' => 1)), FALSE, 0, 0, $and_write);
        
        // We make sure that we don't return the God user, if there is one.
        foreach ($temp as $user) {
            $login_instance = $user->get_login_instance();
            if ($this->god() || (isset($login_instance) && ($login_instance->id() != CO_Config::god_mode_id()))) {
                array_push($ret, $user);
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of instances (or integers, if $ids_only is TRUE) that match the requested tag values. If $count_only is TRUE, then it will be a single integer, with the count of responses to the search (if a page, then this count will only be the number of items on that page).
     */
    public function tag_search( $in_tags_associative_array, /**< REQUIRED:  This is an associative array, with the keys being "0" through "9". Each element will have a requested value for that element.
                                                                            Leaving an element out will remove it as a search factor. Adding it, but leaving it NULL or blank, means that this tag MUST be null or blank.
                                                                            If you add an element called 'use_like' ('use_like' => 1) to the array, then you can use SQL-style "wildcards" (%) in your matches.
                                                                            Unless $in_or_search is set to TRUE, the search will be an AND search; meaning that ALL the tag values must match, in order to result in a record being returned.
                                                            */
                                $in_or_search = FALSE,      ///< OPTIONAL: If TRUE (Default is FALSE), then the search will be an "OR" search (any of the values).
                                $page_size = 0,             ///< OPTIONAL: If specified with a 1-based integer, this denotes the size of a "page" of results. NOTE: This is only applicable to MySQL or Postgres, and will be ignored if the DB is not MySQL or Postgres. Default is 0.
                                $initial_page = 0,          ///< OPTIONAL: This is ignored unless $page_size is greater than 0. If so, then this 0-based index will specify which page of results to return. Values beyond the maximum number of pages will result in no returned values.
                                $and_writeable = FALSE,     ///< OPTIONAL: If TRUE, then we only want records we can modify.
                                $count_only = FALSE,        ///< OPTIONAL: If TRUE (default is FALSE), then only a single integer will be returned, with the count of items that fit the search.
                                $ids_only = FALSE           ///< OPTIONAL: If TRUE (default is FALSE), then the return array will consist only of integers (the object IDs). If $count_only is TRUE, this is ignored.
                            ) {
        $tags_array = Array();
        $ret = Array();
        
        return $ret;
    }
        
    /************************************************************************************************************************/    
    /*                                                 USER MANAGEMENT METHODS                                              */
    /************************************************************************************************************************/
        
    /***********************/
    /**
    This is a special function for returning the user for a login, with the possibility of creating one, if one was not already in place.
    In order to potentially create a user, the current login must be a manager, $in_make_user_if_necessary must be TRUE, and the user must not already exist (even if the current login cannot see that user).
     
    \returns the user collection object for a given login. If there is no login given, then the current login is assumed. This is subject to security restrictions.
     */
    public function get_user_from_login(    $in_login_integer_id = NULL,        ///< The integer login ID that is associated with the user collection. If NULL, then the current login is used.
                                            $in_make_user_if_necessary = FALSE  ///< If TRUE (Default is FALSE), then the user will be created if it does not already exist. Ignored, if we are not a Login Manager.
                                        ) {
        $ret = NULL;
        
        if ($in_make_user_if_necessary && $this->manager()) {   // See if we are a manager, and they want to maybe create a new user.
            $ret = $this->get_cobra_instance()->get_user_from_login($in_login_integer_id, $in_make_user_if_necessary);
        
            $this->error = $this->get_cobra_instance()->error;
        } else {
            $ret = $this->get_chameleon_instance()->get_user_from_login($in_login_integer_id);
        
            $this->error = $this->get_chameleon_instance()->error;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This method can only be called if the user is logged in as a Login Manager (or God).
    This creates a new login and user collection.
    Upon successful completion, both a new login, and a user collection, based upon that login, now exist.
    If there was an error, the user and login are deleted. It should be noted that, if the login was created, it is not really deleted, and is, instead, turned into a security token object.
    
    \returns a string, with the login password as cleartext (If an acceptable-length password is supplied in $in_password, that that is returned). If the operation failed, then NULL is returned.
     */
    public function create_new_user(    $in_login_string_id,                   ///< REQUIRED: The login ID. It must be unique in the Security DB.
                                        $in_password = NULL,            ///< OPTIONAL: A new password. It must be at least as long as the minimum password length. If not supplied, an auto-generated password is created and returned as the function return. If too short, then an auto-generated password is created.
                                        $in_display_name = NULL,        ///< OPTIONAL: A string, representing the basic "display name" to be associated with the login and user collection. If not supplied, the $in_login_string_id is used.
                                        $in_security_tokens = NULL,     ///< Any additional security tokens to apply to the new login. These must be a subset of the security tokens available to the logged-in manager. The God admin can set any tokens they want.
                                        $in_read_security_id = NULL,    ///< An optional read security ID. If not supplied, then ID 1 (logged-in users) is set. The write security ID is always set to the ID of the login.
                                        $is_manager = FALSE             ///< If TRUE (default is FALSE), then the new user will be a CO_Login_Manager object.
                                    ) {
        $ret = NULL;
        
        if ($in_login_string_id) {
            if ($this->manager()) { // Don't even bother unless we're a manager.
                $login_item = NULL;
                
                // See if we need to auto-generate a password.
                if (!$in_password || (strlen($in_password) < CO_Config::$min_pw_len)) {
                    $in_password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*~_-=+;:,.!?"), 0, CO_Config::$min_pw_len);
                }
            
                if ($is_manager) {  // See if they want to create a manager, or a standard login.
                    $login_item = $this->get_cobra_instance()->create_new_manager_login($in_login_string_id, $in_password, $in_security_tokens);
                } else {
                    $login_item = $this->get_cobra_instance()->create_new_standard_login($in_login_string_id, $in_password, $in_security_tokens);
                }
                // Make sure we got what we expect.
                if ($login_item instanceof CO_Security_Login) {
                    // Next, set the display name.
                    $display_name = $in_display_name;
                    if (!$display_name) {
                        $display_name = $in_login_string_id;
                    }
                    
                    // Set the display name.
                    if ($login_item->set_name($display_name)) {
                        // Assuming all that went well, now we create the user item.
                        $id = $login_item->id();
                        $user_item = $this->get_cobra_instance()->get_user_from_login($id, true);
                        if ($user_item instanceof CO_User_Collection) {
                            if ($user_item->set_name($display_name)) {
                                if ($login_item->set_password_from_cleartext($in_password)) {
                                    $ret = $in_password;
                                } else {
                                    $user_item->delete_from_db();
                                    $login_item->delete_from_db();
                                    $user_item = NULL;
                                    $login_item = NULL;
                                    $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_failed_to_initialize,
                                                                    CO_ANDISOL_Lang::$andisol_error_name_login_instance_failed_to_initialize,
                                                                    CO_ANDISOL_Lang::$andisol_error_desc_login_instance_failed_to_initialize);
                                }
                            } else {
                                $user_item->delete_from_db();
                                $login_item->delete_from_db();
                                $user_item = NULL;
                                $login_item = NULL;
                                $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_failed_to_initialize,
                                                                CO_ANDISOL_Lang::$andisol_error_name_login_instance_failed_to_initialize,
                                                                CO_ANDISOL_Lang::$andisol_error_desc_login_instance_failed_to_initialize);
                            }
                        } else {
                            $this->error = $this->get_cobra_instance()->error;
                    
                            // Just in case something was created.
                            if (isset($user_item) && ($user_item instanceof A_CO_DB_Table_Base)) {
                                $user_item->delete_from_db();
                            }
                            
                            $user_item = NULL;
                            
                            $login_item->delete_from_db();
                            $login_item = NULL;
                            if (!$this->error) {
                                $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_failed_to_initialize,
                                                                CO_ANDISOL_Lang::$andisol_error_name_login_instance_failed_to_initialize,
                                                                CO_ANDISOL_Lang::$andisol_error_desc_login_instance_failed_to_initialize);
                            }
                        }
                    } else {
                        $this->error = $this->get_cobra_instance()->error;
                        $login_item->delete_from_db();
                        $login_item = NULL;
                        if (!$this->error) {
                            $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_failed_to_initialize,
                                                            CO_ANDISOL_Lang::$andisol_error_name_login_instance_failed_to_initialize,
                                                            CO_ANDISOL_Lang::$andisol_error_desc_login_instance_failed_to_initialize);
                        }
                    }
                    
                } else {
                    $this->error = $this->get_cobra_instance()->error;
                    
                    // Just in case something was created.
                    if (isset($login_item) && ($login_item instanceof A_CO_DB_Table_Base)) {
                        $login_item->delete_from_db();
                    }
                    
                    $login_item = NULL;
                    
                    if (!$this->error) {
                        $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_failed_to_initialize,
                                                        CO_ANDISOL_Lang::$andisol_error_name_login_instance_failed_to_initialize,
                                                        CO_ANDISOL_Lang::$andisol_error_desc_login_instance_failed_to_initialize);
                    }
                }
            } else {
                $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_authorized,
                                                CO_ANDISOL_Lang::$andisol_error_name_user_not_authorized,
                                                CO_ANDISOL_Lang::$andisol_error_desc_user_not_authorized);
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This method can only be called if the user is logged in as a Login Manager (or God).
    This will delete both the login and the user collection for the given login ID.
    It should be noted that deleting a collection does not delete the data associated with that collection, unless $with_extreme_prejudice is TRUE, and even then, only the records this manager can see will be deleted.
    
    \returns TRUE, if the operation was successful.
     */
    public function delete_user(    $in_login_string_id,            ///< REQUIRED: The string login ID of the user to delete.
                                    $with_extreme_prejudice = FALSE ///< OPTIONAL: If TRUE (Default is FALSE), then the manager will delete as many of the user data points as possible (It may not be possible for the manager to delete all data, unless the manager is God).
                                ) {
        $ret = FALSE;
        
        if ($in_login_string_id) {
            if ($this->manager()) { // Don't even bother unless we're a manager.
                // First, fetch the login item.
                $login_item = $this->get_cobra_instance()->get_login_instance($in_login_string_id);
                if ($login_item) {
                    // Next, get the user item.
                    $user_item = $this->get_cobra_instance()->get_user_from_login($login_item->id());
                    if ($user_item) {
                        // We have to have both the login and the user. Now, we make sure that we have write perms on both.
                        if ($login_item->user_can_write() && $user_item->user_can_write()) {
                            if ($user_item->delete_from_db($with_extreme_prejudice, TRUE)) {
                                $ret = TRUE;
                            } else {
                                $this->error = $user_item->error;
                                if (!$this->error) {
                                    $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_deleted,
                                                                    CO_ANDISOL_Lang::$andisol_error_name_user_not_deleted,
                                                                    CO_ANDISOL_Lang::$andisol_error_desc_user_not_deleted);
                                }
                            }
                        } else {
                            $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_authorized,
                                                            CO_ANDISOL_Lang::$andisol_error_name_user_not_authorized,
                                                            CO_ANDISOL_Lang::$andisol_error_desc_user_not_authorized);
                        }
                    } else {
                        $this->error = $this->get_cobra_instance()->error;
                        if (!$this->error) {
                            $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_instance_unavailable,
                                                            CO_ANDISOL_Lang::$andisol_error_name_user_instance_unavailable,
                                                            CO_ANDISOL_Lang::$andisol_error_desc_user_instance_unavailable);
                        }
                    }
                } else {
                    $this->error = $this->get_cobra_instance()->error;
                    if (!$this->error) {
                        $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_login_instance_unavailable,
                                                        CO_ANDISOL_Lang::$andisol_error_name_login_instance_unavailable,
                                                        CO_ANDISOL_Lang::$andisol_error_desc_login_instance_unavailable);
                    }
                }
            } else {
                $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_authorized,
                                                CO_ANDISOL_Lang::$andisol_error_name_user_not_authorized,
                                                CO_ANDISOL_Lang::$andisol_error_desc_user_not_authorized);
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of instances of all the logins that are visible to the current login (or a supplied login, if in "God" mode). The user must be a manager.
     */
    public function get_all_logins( $and_write = FALSE,         ///< If TRUE, then we only want ones we have write access to.
                                    $in_login_string_id = NULL, ///< This is ignored, unless this is the God login. If We are logged in as God, then we can select a login via its string login ID, and see what logins are available to it. This trumps the integer ID.
                                    $in_login_integer_id = NULL ///< This is ignored, unless this is the God login and $in_login_string_id is not specified. If We are logged in as God, then we can select a login via its integer login ID, and see what logins are available to it.
                                    ) {
        $ret = Array();
        
        if ($this->manager()) { // Don't even bother unless we're a manager.
            $ret = $this->get_cobra_instance()->get_all_logins($and_write, $in_login_string_id, $in_login_integer_id);
        
            $this->error = $this->get_cobra_instance()->error;
        } else {
            $this->error = new LGV_Error(   CO_ANDISOL_Lang_Common::$andisol_error_code_user_not_authorized,
                                            CO_ANDISOL_Lang::$andisol_error_name_user_not_authorized,
                                            CO_ANDISOL_Lang::$andisol_error_desc_user_not_authorized);
        }
        
        return $ret;
    }
};
