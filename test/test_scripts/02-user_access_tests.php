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
    user_access_run_test(15, 'PASS - God Login', 'We create an instance of ANDISOL with the "God" login, and make sure its valid.', 'admin', '', CO_Config::$god_mode_password);
    user_access_run_test(16, 'FAIL - Basic Login With No Associated User', 'We create an instance of ANDISOL with the a basic COBRA login that has no associated user, and make sure the login is valid, but we expect the user search to fail.', 'cobra', '', 'CoreysGoryStory');
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
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'user_access-tests\')">ANDISOL LOGGED-IN USER ACCESS TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                user_access_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
