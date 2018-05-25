<?php
/***************************************************************************************************************************/
/**
    COBRA Security Administration Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
require_once(dirname(dirname(__FILE__)).'/functions.php');
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function user_access_run_tests() {
    user_access_run_test(11, 'FAIL - No Login', 'We create an instance of ANDISOL with no login, and make sure its valid, but we will have no login or user.');
    user_access_run_test(12, 'PASS - Fundamental Login', 'We create an instance of ANDISOL with a "fundamental" (BADGER-level) login, and make sure its valid.', 'norm', '', 'CoreysGoryStory');
    user_access_run_test(13, 'PASS - Basic Login', 'We create an instance of ANDISOL with a basic COBRA login, and make sure its valid.', 'krait', '', 'CoreysGoryStory');
    user_access_run_test(14, 'PASS - Manager Login', 'We create an instance of ANDISOL with a COBRA Login manager login, and make sure its valid.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(15, 'PASS - God Login', 'We create an instance of ANDISOL with the "God" login, and make sure its valid.', 'admin', '', CO_COnfig::god_mode_password());
    user_access_run_test(16, 'FAIL - Basic Login With No Associated User', 'We create an instance of ANDISOL with the a basic COBRA login that has no associated user, and make sure the login is valid, but we expect the user search to fail.', 'cobra', '', 'CoreysGoryStory');
}

function user_visibility_run_tests() {
    user_access_run_test(17, 'FAIL - Fundamental Login', 'We create an instance of ANDISOL with a "fundamental" (BADGER-level) login, and try to see other users.', 'norm', '', 'CoreysGoryStory');
    user_access_run_test(18, 'FAIL - Basic Login', 'We create an instance of ANDISOL with a basic COBRA login, and try to see other users.', 'krait', '', 'CoreysGoryStory');
    user_access_run_test(19, 'PASS - Manager Login', 'We create an instance of ANDISOL with a COBRA Login manager login, and try to see other users.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(20, 'PASS - God Login', 'We create an instance of ANDISOL with the "God" login, and try to see other users.', 'admin', '', CO_COnfig::god_mode_password());
}

function login_run_tests() {
    user_access_run_test(21, 'FAIL - No Login', 'We create an instance of ANDISOL with no login, and try to see other logins.');
    user_access_run_test(22, 'FAIL - Fundamental Login', 'We create an instance of ANDISOL with a "fundamental" (BADGER-level) login, and try to see other logins.', 'norm', '', 'CoreysGoryStory');
    user_access_run_test(23, 'FAIL - Basic Login', 'We create an instance of ANDISOL with a basic COBRA login, and try to see other logins.', 'krait', '', 'CoreysGoryStory');
    user_access_run_test(24, 'FAIL - Manager Login', 'We create an instance of ANDISOL with a COBRA Login manager login, and try to see other logins.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(25, 'PASS - God Login', 'We create an instance of ANDISOL with the "God" login, and try to see other logins.', 'admin', '', CO_COnfig::god_mode_password());
}

function create_run_tests() {
    user_access_run_test(26, 'FAIL - No Login', 'We create an instance of ANDISOL with no login, and try to create a user for a login with no user.');
    user_access_run_test(27, 'FAIL - Fundamental Login', 'We create an instance of ANDISOL with a "fundamental" (BADGER-level) login, and try to create a user for a login with no user.', 'norm', '', 'CoreysGoryStory');
    user_access_run_test(28, 'FAIL - Basic Login', 'We create an instance of ANDISOL with a basic COBRA login, and try to create a user for a login with no user.', 'krait', '', 'CoreysGoryStory');
    user_access_run_test(29, 'PASS - Manager Login', 'We create an instance of ANDISOL with a COBRA Login manager login, and try to create a user for a login with no user.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(30, 'PASS - God Login', 'We create an instance of ANDISOL with the "God" login, and try to create a user for another login with no user.', 'admin', '', CO_COnfig::god_mode_password());
}

function create_delete_run_tests() {
    user_access_run_test(31, 'PASS - Create With Presets', 'We create an instance of ANDISOL with the "asp" login, and create a user and login pair with a login ID of \'crocodile\' and the \'CoresGoryStory\' password. We also give it the security tokens owned by the \'asp\' login (minus its own token).', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(32, 'PASS - Create Default', 'We create an instance of ANDISOL with the "king-cobra" login, and create a user and login pair with a login ID of \'python\', specifying no password or other parameters.', 'king-cobra', '', 'CoreysGoryStory');
    user_access_run_test(33, 'FAIL - Create a Manager With Presets', 'We create an instance of ANDISOL with the "asp" login, and create a manager user and login pair with a login ID of \'crocodile\' and the \'CoresGoryStory\' password. We also give it the security tokens owned by the \'asp\' login (minus its own token). This will fail, because \'crocodile\' already exists.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(34, 'PASS - Create a Manager With Presets', 'We create an instance of ANDISOL with the "asp" login, and create a manager user and login pair with a login ID of \'alligator\' and the \'CoresGoryStory\' password. We also give it the security tokens owned by the \'asp\' login (minus its own token). This will succeed.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(35, 'FAIL - Create Default', 'We create an instance of ANDISOL with the "krait" login, and try to create a user and login pair with a login ID of \'mamba\', specifying no password or other parameters. We expect this to fail, as \'krait\' is not a manager.', 'krait', '', 'CoreysGoryStory');
    user_access_run_test(36, 'PASS - Create Default', 'We create an instance of ANDISOL with the "king-cobra" login, and try to create a user and login pair with a login ID of \'mamba\', specifying no password or other parameters. We expect this to succeed.', 'king-cobra', '', 'CoreysGoryStory');
    user_access_run_test(37, 'FAIL - Delete User/Login Pair', 'We create an instance of ANDISOL with the "asp" login, and try to delete \'python\'. This won\'t work, as \'asp\' doesn\'t have write perms for \'python\'.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(38, 'PASS - Delete User/Login Pair', 'We create an instance of ANDISOL with the "king-cobra" login, and try to delete \'python\'.', 'king-cobra', '', 'CoreysGoryStory');
    user_access_run_test(39, 'FAIL - Delete Login Only', 'We create an instance of ANDISOL with the "asp" login. We create a new login directly, then try to use ANDISOL to delete it. This should fail, as ANDISOL only works on pairs.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(40, 'PASS - Delete Login Only (Directly)', 'We create an instance of ANDISOL with the "asp" login. We create a new login directly, then try to delete it directly. This time, it will work.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(41, 'FAIL - Delete User', 'We create an instance of ANDISOL with the "asp" login. We then try to delete a user that we don\'t have permission to write.', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(42, 'PASS - Delete User', 'This time, we go in as \'king-cobra\', and should be able to delete the user and login.', 'king-cobra', '', 'CoreysGoryStory');
    user_access_run_test(43, 'FAIL - Delete Login -Remove Reference From User', 'In this test, we attempt to remove a user we have write permissions for, but a login we don\'t (we only have read permissions for the associated login).', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(44, 'PASS - Delete Login -Remove Reference From User', 'Here, we have write permissions for both.', 'king-cobra', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function user_access_test_11($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $login_item = $andisol_instance->current_login();
        $user_item = $andisol_instance->current_user();
        
        if ($login_item) {
            display_record($login_item);
        } else {
            echo('<h3 style="color:red">The Login Was Not Found!</h3>');
        }
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User Was Not Found!</h3>');
        }
    }
}

function user_access_test_12($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_11($in_login, $in_hashed_password, $in_password);
}

function user_access_test_13($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_11($in_login, $in_hashed_password, $in_password);
}

function user_access_test_14($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_11($in_login, $in_hashed_password, $in_password);
}

function user_access_test_15($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_11($in_login, $in_hashed_password, $in_password);
}

function user_access_test_16($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_11($in_login, $in_hashed_password, $in_password);
}

function user_access_test_17($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $user_item = $andisol_instance->get_user_from_login(2);
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User (Login 2) Was Not Found!</h3>');
        }
        
        $user_item = $andisol_instance->get_user_from_login(3);
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User (Login 3) Was Not Found!</h3>');
        }
        
        $user_item = $andisol_instance->get_user_from_login(6);
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User (Login 6) Was Not Found!</h3>');
        }
    }
}

function user_access_test_18($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_17($in_login, $in_hashed_password, $in_password);
}

function user_access_test_19($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_17($in_login, $in_hashed_password, $in_password);
}

function user_access_test_20($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_17($in_login, $in_hashed_password, $in_password);
}

function user_access_test_21($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $login_item = $andisol_instance->get_login_item(2);
        
        if ($login_item) {
            display_record($login_item);
        } else {
            echo('<h3 style="color:red">The Login (2) Was Not Found!</h3>');
        }
        
        $login_item = $andisol_instance->get_login_item(3);
        
        if ($login_item) {
            display_record($login_item);
        } else {
            echo('<h3 style="color:red">The Login (3) Was Not Found!</h3>');
        }
        
        $login_item = $andisol_instance->get_login_item(4);
        
        if ($login_item) {
            display_record($login_item);
        } else {
            echo('<h3 style="color:red">The Login (4) Was Not Found!</h3>');
        }
        
        $login_item = $andisol_instance->get_login_item(6);
        
        if ($login_item) {
            display_record($login_item);
        } else {
            echo('<h3 style="color:red">The Login (6) Was Not Found!</h3>');
        }
    }
}

function user_access_test_22($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_21($in_login, $in_hashed_password, $in_password);
}

function user_access_test_23($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_21($in_login, $in_hashed_password, $in_password);
}

function user_access_test_24($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_21($in_login, $in_hashed_password, $in_password);
}

function user_access_test_25($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_21($in_login, $in_hashed_password, $in_password);
}

function user_access_test_26($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $user_item = $andisol_instance->get_user_from_login(4, true);
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User (Login 4) Was Not Created!</h3>');
        }
    }
}

function user_access_test_27($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_26($in_login, $in_hashed_password, $in_password);
}

function user_access_test_28($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_26($in_login, $in_hashed_password, $in_password);
}

function user_access_test_29($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_26($in_login, $in_hashed_password, $in_password);
}

function user_access_test_30($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $user_item = $andisol_instance->get_user_from_login(5, true);
        
        if ($user_item) {
            display_record($user_item);
        } else {
            echo('<h3 style="color:red">The User (Login 5) Was Not Created!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function user_access_test_31($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $andisol_login = $andisol_instance->get_login_item();
        
        $ids = array_filter($andisol_login->ids(), function($in){return $in != 7;});
        
        $password = $andisol_instance->create_new_user('crocodile', 'CoreysGoryStory', 'Croc O\'Dial', $ids);
        if ($password) {
            if ($password == 'CoreysGoryStory') {
                $user_from_andisol = $andisol_instance->get_user_from_login_string('crocodile');
                
                if ($user_from_andisol) {
                    $login_item = $andisol_instance->get_login_item_by_login_string('crocodile');
                    
                    if ($login_item) {
                        if ($user_from_andisol->get_login_instance() === $login_item) {
                            echo('<h3 style="color:green">The Login Item:</h3>');
                            display_record($login_item);
                        } else {
                            echo('<h3 style="color:red">There was a problem! The login from the user did not match the login we got from COBRA!</h3>');
                        }
                    
                        $user_item = $andisol_instance->get_user_from_login($login_item->id());
                        if ($user_item) {
                            if ($user_from_andisol === $user_item) {
                                echo('<hr /><h3 style="color:green">The User Item:</h3>');
                                display_record($user_item);
                            } else {
                                echo('<h3 style="color:red">There was a problem! The user from ANDISOL did not match the user we got from COBRA!</h3>');
                            }
                        } else {
                            echo('<h3 style="color:red">There was a problem! We did not get a user from COBRA!</h3>');
                        }
                    } else {
                        echo('<h3 style="color:red">There was a problem! We did not get a login from COBRA!</h3>');
                    }
                } else {
                    echo('<h3 style="color:red">There was a problem! The user wasn\'t returned from ANDISOL!</h3>');
                }
            } else {
                echo('<h3 style="color:red">There was a problem! The passwords don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">The User Was Not Created!</h3>');
            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
        }
    }
}

function user_access_test_32($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $password = $andisol_instance->create_new_user('python');
        if ($password) {
            echo('<h3 style="color:green">The User (\'python\') Was Created, and the auto-generated password is '.htmlspecialchars($password).'</h3>');
            $user_from_andisol = $andisol_instance->get_user_from_login_string('python');
            
            if ($user_from_andisol) {
                $login_item = $andisol_instance->get_login_item_by_login_string('python');
            
                if ($login_item) {
                    if ($user_from_andisol->get_login_instance() === $login_item) {
                        echo('<hr /><h3 style="color:green">The Login Item:</h3>');
                        display_record($login_item);
                        echo('<hr /><h3 style="color:green">The User Item:</h3>');
                        display_record($user_from_andisol);
                    } else {
                        echo('<h3 style="color:red">There was a problem! The login from the user did not match the login we got from COBRA!</h3>');
                    }
                }
            }
        } else {
            echo('<h3 style="color:red">The User Was Not Created!</h3>');
            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
        }
    }
}

function user_access_test_33($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_31($in_login, $in_hashed_password, $in_password);
}

function user_access_test_34($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $andisol_login = $andisol_instance->get_login_item();
        
        $ids = array_filter($andisol_login->ids(), function($in){return $in != 7;});
        
        $password = $andisol_instance->create_new_user('alligator', 'CoreysGoryStory', 'Alley Gater', $ids, NULL, TRUE);
        if ($password) {
            if ($password == 'CoreysGoryStory') {
                $user_from_andisol = $andisol_instance->get_user_from_login_string('alligator');
                
                if ($user_from_andisol) {
                    $login_item = $andisol_instance->get_login_item_by_login_string('alligator');
                    
                    if ($login_item) {
                        if ($user_from_andisol->get_login_instance() === $login_item) {
                            echo('<h3 style="color:green">The Login Item:</h3>');
                            display_record($login_item);
                        } else {
                            echo('<h3 style="color:red">There was a problem! The login from the user did not match the login we got from COBRA!</h3>');
                        }
                    
                        $user_item = $andisol_instance->get_user_from_login($login_item->id());
                        if ($user_item) {
                            if ($user_from_andisol === $user_item) {
                                echo('<hr /><h3 style="color:green">The User Item:</h3>');
                                display_record($user_item);
                            } else {
                                echo('<h3 style="color:red">There was a problem! The user from ANDISOL did not match the user we got from COBRA!</h3>');
                            }
                        } else {
                            echo('<h3 style="color:red">There was a problem! We did not get a user from COBRA!</h3>');
                        }
                    } else {
                        echo('<h3 style="color:red">There was a problem! We did not get a login from COBRA!</h3>');
                    }
                } else {
                    echo('<h3 style="color:red">There was a problem! The user wasn\'t returned from ANDISOL!</h3>');
                }
            } else {
                echo('<h3 style="color:red">There was a problem! The passwords don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">The User Was Not Created!</h3>');
            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
        }
    }
}

function user_access_test_35($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $password = $andisol_instance->create_new_user('mamba');
        if ($password) {
            echo('<h3 style="color:green">The User Was Created, and the auto-generated password is '.htmlspecialchars($password).'</h3>');
            $user_from_andisol = $andisol_instance->get_user_from_login_string('mamba');
            
            if ($user_from_andisol) {
                $login_item = $andisol_instance->get_login_item_by_login_string('mamba');
                if ($login_item) {
                    if ($user_from_andisol->get_login_instance() === $login_item) {
                        $login_item->set_read_security_id(7);
                        $user_from_andisol->set_read_security_id(7);
                        echo('<hr /><h3 style="color:green">The Login Item:</h3>');
                        display_record($login_item);
                        echo('<hr /><h3 style="color:green">The User Item:</h3>');
                        display_record($user_from_andisol);
                    } else {
                        echo('<h3 style="color:red">There was a problem! The login from the user did not match the login we got from COBRA!</h3>');
                    }
                }
            }
        } else {
            echo('<h3 style="color:red">The User Was Not Created!</h3>');
            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
        }
    }
}

function user_access_test_36($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_35($in_login, $in_hashed_password, $in_password);
}

function user_access_test_37($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $user_from_andisol = $andisol_instance->get_user_from_login_string('python');
        
        if ($user_from_andisol) {
            echo('<hr /><h3 style="color:green">BEFORE:</h3>');
            display_record($user_from_andisol);
        }
        
        $andisol_instance->delete_user('python');
        
        $user_from_andisol = $andisol_instance->get_user_from_login_string('python');
        
        if ($user_from_andisol) {
            echo('<hr /><h3 style="color:red">UH-OH. This ain\'t supposed to be here:</h3>');
            display_record($user_from_andisol);
        } else {
            $login_item = $andisol_instance->get_login_item_by_login_string('python');
        
            if ($login_item) {
                echo('<hr /><h3 style="color:red">UH-OH. This ain\'t supposed to be here:</h3>');
                display_record($login_item);
            } else {
                echo('<hr /><h3 style="color:green">WOO-HOO! It Worked!</h3>');
            }
        }
        
    }
}

function user_access_test_38($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_37($in_login, $in_hashed_password, $in_password);
}

function user_access_test_39($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $cobra = $andisol_instance->get_cobra_instance();
        if ($cobra) {
            $login_item = $cobra->create_new_standard_login('puff-adder', 'CoreysGoryStory');
            
            if ($login_item) {
                if ($andisol_instance->delete_user('puff-adder')) {
                    echo('<h3 style="color:green">UH-OH! This should not have suceeded!</h3>');
                } else {
                    echo('<h3 style="color:red">This did not succeed, which is correct. We cannot use ANDISOL to delete single entities.</h3>');
                    echo('<p style="color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                }
            } else {
                echo('<h3 style="color:red">Login Item Failed to Instaniate!</h3>');
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$cobra->error->error_code.') '.$cobra->error->error_name.' ('.$cobra->error->error_description.')</p>');
            }
        } else {
            echo('<h3 style="color:red">No COBRA Instance!</h3>');
        }
    }
}

function user_access_test_40($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $login_instance = $andisol_instance->get_login_item_by_login_string('puff-adder');
        if ($login_instance) {
            if ($login_instance->delete_from_db()) {
                echo('<h3 style="color:green">We successfully deleted the login!</h3>');
            } else {
                echo('<h3 style="color:red">This did not succeed.</h3>');
                echo('<p style="color:red;font-weight:bold">Error: ('.$login_instance->error->error_code.') '.$login_instance->error->error_name.' ('.$login_instance->error->error_description.')</p>');
            }
        } else {
            echo('<h3 style="color:red">Could not get Login Item!</h3>');
        }
    }
}

function user_access_test_41($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $user_from_andisol = $andisol_instance->get_user_from_login_string('krait');
        
        if ($user_from_andisol) {
            echo('<h3 style="color:green">We can see the user:</h3>');
            display_record($user_from_andisol);
        }
        
        if ($andisol_instance->delete_user('krait')) {
            echo('<h3 style="color:green">We successfully deleted the user and login pair!</h3>');
        } else {
            echo('<h3 style="color:red">This did not succeed!</h3>');
            echo('<p style="color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
        }
    }
}

function user_access_test_42($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_41($in_login, $in_hashed_password, $in_password);
}

function user_access_test_43($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $login_instance = $andisol_instance->get_login_item_by_login_string('mamba');
        if ($login_instance) {
            $user_object = $login_instance->get_user_object();
            
            echo('<h3 style="color:green">The associated user (BEFORE):</h3>');
            display_record($user_object);
        
            if ($login_instance->delete_from_db()) {
                echo('<h3 style="color:green">We successfully deleted the login!</h3>');
            
                echo('<h3 style="color:green">The associated user (AFTER):</h3>');
                display_record($user_object);
            } else {
                echo('<h3 style="color:red">This did not succeed (We could not write the login).</h3>');
                if (isset($login_instance->error)) {
                    echo('<p style="color:red;font-weight:bold">Error: ('.$login_instance->error->error_code.') '.$login_instance->error->error_name.' ('.$login_instance->error->error_description.')</p>');
                }
            }
        } else {
            echo('<h3 style="color:red">Could not get Login Item!</h3>');
        }
    }
}

function user_access_test_44($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    user_access_test_43($in_login, $in_hashed_password, $in_password);
}

// -------------------------------- STRUCTURE ---------------------------------------------

function user_access_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(TRUE);
            $function_name = sprintf('user_access_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(TRUE) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('user_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">USER TESTS</h1>');
        echo('<div id="user_access-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'user_access-tests\')">LOGGED-IN USER ACCESS TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                user_access_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="user_visibility-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'user_visibility-tests\')">LOGGED-IN USER CROSS-VISIBILITY TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                user_visibility_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="login-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'login-tests\')">LOGIN TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                login_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="create-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'create-tests\')">CREATE USER FROM LOGIN TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain">In these tests, we have a couple of logins with no user associated, and we will try to create users for these logins.</p>');
            
                $start = microtime(TRUE);
                
                create_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="create-delete-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'create-delete-tests\')">BASIC CREATE AND DELETE USER/LOGIN PAIRS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                create_delete_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
