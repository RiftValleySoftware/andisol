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

function search_run_tests() {
    search_run_test(45, 'PASS - Simple Location Search', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial.');
    search_run_test(46, 'FAIL - Simple Location Search', 'In this case, we search in Chicago. We should get no responses.');
    search_run_test(47, 'PASS - Paged Location Search', 'Again, back to the Lincoln Memorial, but this time, we are searching in pages of ten.');
    search_run_test(48, 'FAIL - Paged Location Search', 'Going back to Chicago, looking for pages of ten.');
}

function user_search_run_tests() {
    search_run_test(49, 'PASS - Get All Users (God)', 'Log in as the God admin, and see which users we can find.', 'admin', '', CO_Config::god_mode_password());
    search_run_test(50, 'PASS - Get All Users', 'Log in as the main manager, and see which users we can find. The difference should be that we don\'t see the \'God\' admin user now.', 'DCAreaManager', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function search_test_40($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $returned_array = $andisol_instance->location_search(-77.0502, 38.8893, 5.0);
        
        if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
            echo('<h3 style="color:green">We got '.count($returned_array).' responses to the location search:</h3>');
            foreach ($returned_array as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_41($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $returned_array = $andisol_instance->location_search(-87.621887, 41.876465, 5.0);
        
        if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
            echo('<h3 style="color:green">We got '.count($returned_array).' responses to the location search:</h3>');
            foreach ($returned_array as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_42($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $page = 0;
        $total = 0;
        
        do {
            $returned_array = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 10, $page);
        
            if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
                echo('<h3 style="color:green">Page '.$page.' ('.count($returned_array).' responses):</h3>');
                $total += count($returned_array);
                foreach ($returned_array as $record) {
                    display_record($record);
                }
            } else {
                break;
            }
            
            $page++;
        } while ($page < 20);
        
        if ($total) {
            echo('<h3 style="color:green">We had a total of '.$total.' records returned, in '.$page.' pages:</h3>');
        } else {
            echo('<h3 style="color:red">We had no records returned.</h3>');
        }
    }
}

function search_test_43($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $page = 0;
        $total = 0;
        
        do {
            $returned_array = $andisol_instance->location_search(-87.621887, 41.876465, 5.0, 10, $page);
        
            if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
                echo('<h3 style="color:green">Page '.$page.' ('.count($returned_array).' responses):</h3>');
                $total += count($returned_array);
                foreach ($returned_array as $record) {
                    display_record($record);
                }
            } else {
                break;
            }
            
            $page++;
        } while ($page < 20);
        
        if ($total) {
            echo('<h3 style="color:green">We had a total of '.$total.' records returned, in '.$page.' pages:</h3>');
        } else {
            echo('<h3 style="color:red">We had no records returned.</h3>');
        }
    }
}

function search_test_44($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $all_users = $andisol_instance->get_all_users();
        
        if (isset($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        }
    }
}

function search_test_45($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    search_test_44($in_login, $in_hashed_password, $in_password);
}

// -------------------------------- STRUCTURE ---------------------------------------------

function search_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(TRUE);
            $function_name = sprintf('search_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(TRUE) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('search_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">BASIC SEARCH TESTS</h1>');
        echo('<div id="search-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'search-tests\')">LOCATION SEARCH TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                search_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="user-search-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'user-search-tests\')">USER SEARCH TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                user_search_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
