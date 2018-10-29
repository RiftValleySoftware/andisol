<?php
/***************************************************************************************************************************/
/**
    COBRA Security Administration Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
    LICENSE:
    
    FOR OPEN-SOURCE (COMMERCIAL OR FREE):
    This code is released as open source under the GNU Plublic License (GPL), Version 3.
    You may use, modify or republish this code, as long as you do so under the terms of the GPL, which requires that you also
    publish all modificanions, derivative products and license notices, along with this code.
    
    UNDER SPECIAL LICENSE, DIRECTLY FROM LITTLE GREEN VIPER OR THE GREAT RIFT VALLEY SOFTWARE COMPANY:
    It is NOT to be reused or combined into any application, nor is it to be redistributed, republished or sublicensed,
    unless done so, specifically WITH SPECIFIC, WRITTEN PERMISSION from Little Green Viper Software Development LLC,
    or The Great Rift Valley Software Company.

    Little Green Viper Software Development: https://littlegreenviper.com
    The Great Rift Valley Software Company: https://riftvalleysoftware.com

    Little Green Viper Software Development: https://littlegreenviper.com
*/
require_once(dirname(dirname(__FILE__)).'/functions.php');
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function instance_run_tests() {
    instance_run_test(1, 'PASS -Simple Instantiation -No Login', 'We create an instance of ANDISOL with no login, and make sure its valid.');
    instance_run_test(2, 'PASS -Simple Instantiation -Basic Login', 'We create an instance of ANDISOL with a basic user login, make sure its valid, and that it registers as logged in.', 'krait', NULL, 'CoreysGoryStory');
    instance_run_test(3, 'PASS -Simple Instantiation -Manager Login', 'We create an instance of ANDISOL with a manager user login, make sure its valid, and that it registers as a manager user.', 'asp', NULL, 'CoreysGoryStory');
    instance_run_test(4, 'PASS -Simple Instantiation -God Login', 'We create an instance of ANDISOL with a God user login, make sure its valid, and that it registers as a God user.', 'admin', NULL, CO_Config::god_mode_password());
    instance_run_test(5, 'FAIL -Simple Instantiation -No Login', 'We create an instance of ANDISOL with no login, and see if it registers as a login. It should fail.');
    instance_run_test(6, 'FAIL -Simple Instantiation -No Login', 'We create an instance of ANDISOL with no login, and see if it registers as a manager. It should fail.');
    instance_run_test(7, 'FAIL -Simple Instantiation -No Login', 'We create an instance of ANDISOL with no login, and see if it registers as a god. It should fail.');
    instance_run_test(8, 'FAIL -Simple Instantiation -Basic Login', 'We create an instance of ANDISOL with a basic login, and see if it registers as a manager. It should fail.', 'krait', NULL, 'CoreysGoryStory');
    instance_run_test(9, 'FAIL -Simple Instantiation -Basic Login', 'We create an instance of ANDISOL with a basic login, and see if it registers as a god. It should fail.', 'krait', NULL, 'CoreysGoryStory');
    instance_run_test(10, 'FAIL -Simple Instantiation -Manager Login', 'We create an instance of ANDISOL with a manager user login, make sure its valid, that it registers as a manager user, but not as a God user.', 'asp', NULL, 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function instance_test_01($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    return make_andisol($in_login, $in_hashed_password, $in_password);
}

function instance_test_02($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = instance_test_01($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        if ($andisol_instance->logged_in()) {
            echo('<h4 style="color:green">The ANDISOL instance is logged in.</h4>');
        } else {
            echo('<h4 style="color:red;font-weight:bold">The ANDISOL instance is not logged in!</h4>');
        }
    }
    
    return $andisol_instance;
}

function instance_test_03($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = instance_test_02($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        if ($andisol_instance->manager()) {
            echo('<h4 style="color:green">The ANDISOL instance is logged in as a manager.</h4>');
        } else {
            echo('<h4 style="color:red;font-weight:bold">The ANDISOL instance is not logged in as a manager!</h4>');
        }
    }
    
    return $andisol_instance;
}

function instance_test_04($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = instance_test_03($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        if ($andisol_instance->god()) {
            echo('<h4 style="color:green">The ANDISOL instance is logged in as a God.</h4>');
        } else {
            echo('<h4 style="color:red;font-weight:bold">The ANDISOL instance is not logged in as God!</h4>');
        }
    }
    
    return $andisol_instance;
}

function instance_test_05($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_02($in_login, $in_hashed_password, $in_password);
}

function instance_test_06($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_03($in_login, $in_hashed_password, $in_password);
}

function instance_test_07($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_04($in_login, $in_hashed_password, $in_password);
}

function instance_test_08($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_03($in_login, $in_hashed_password, $in_password);
}

function instance_test_09($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_04($in_login, $in_hashed_password, $in_password);
}

function instance_test_10($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    instance_test_04($in_login, $in_hashed_password, $in_password);
}

// -------------------------------- STRUCTURE ---------------------------------------------

function instance_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(true);
            $function_name = sprintf('instance_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('instance_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">INSTANCE TESTS</h1>');
        echo('<div id="instance-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'instance-tests\')">BASIC ANDISOL INSTANTIATION TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain">These tests exercise the most fundamental aspects of ANDISOL -Just instantiating and making sure the logins are correct.</p>');
            
                $start = microtime(true);
                
                instance_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
