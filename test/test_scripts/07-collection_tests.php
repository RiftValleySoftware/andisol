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

function basic_collection_run_tests() {
    collection_run_test(93, 'FAIL -Create Generic Collection (Not Logged In)', 'We do not log in, and attempt to create a collection. Like all attempts to create data when not logged in, this should fail.');
    collection_run_test(94, 'PASS -Create Generic Collection (Logged In)', 'We log in, and attempt to create a collection. This should succeed.', 'asp', '', 'CoreysGoryStory');
    collection_run_test(95, 'PASS -Create Initialized Generic Collection', 'We log in, and attempt to create a collection with a bunch of existing children records.', 'asp', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function collection_test_093($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $collection_instance = $andisol_instance->create_collection();
        
        if (isset($collection_instance) && ($collection_instance instanceof CO_Collection)) {
            echo('<h3 style="color:green">The Collection object was created!</h3>');
            display_record($collection_instance);
        } else {
            echo('<h3 style="color:red">The Collection object was not created!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function collection_test_094($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    collection_test_093($in_login, $in_hashed_password, $in_password);
}

function collection_test_095($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $initial_ids = Array(3, 4, 5);
        
        $collection_instance = $andisol_instance->create_collection($initial_ids);
        
        if (isset($collection_instance) && ($collection_instance instanceof CO_Collection)) {
            echo('<h3 style="color:green">The Collection object was created!</h3>');
            display_record($collection_instance);
        } else {
            echo('<h3 style="color:red">The Collection object was not created!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

// -------------------------------- STRUCTURE ---------------------------------------------

function collection_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(true);
            $function_name = sprintf('collection_test_%03d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('collection_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">COLLECTION TESTS</h1>');
        echo('<div id="basic-storage-collection-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'basic-storage-collection-tests\')">BASIC INSTANTIATION AND DELETION TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                basic_collection_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
