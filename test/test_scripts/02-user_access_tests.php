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
    user_access_run_test(31, 'PASS - Manager Login', 'We create an instance of ANDISOL with the "asp" login, and create a user and login pair with a login ID of \'crocodile\' and the \'CoresGoryStory\' password', 'asp', '', 'CoreysGoryStory');
    user_access_run_test(32, 'PASS - Manager Login', 'We create an instance of ANDISOL with the "king-cobra" login, and create a user and login pair with a login ID of \'python\', specifying no password', 'king-cobra', '', 'CoreysGoryStory');
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
        }
    }
}

function user_access_test_31($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $password = $andisol_instance->create_new_user('crocodile', 'CoreysGoryStory', 'Croc O\'Dial', $andisol_instance->get_login_item()->ids());
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
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'create-delete-tests\')">CREATE AND DELETE USER/LOGIN PAIRS</a></h2>');
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
