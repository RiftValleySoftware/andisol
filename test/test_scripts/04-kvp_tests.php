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

function kvp_small_run_tests() {
    kvp_run_test(63, 'PASS - Store and Retrieve a Simple Text Value', 'In this test, we log in as a regular user, and store a simple text value to the database, then read it back (not logged in), to make sure we have it.');
    kvp_run_test(64, 'PASS - Store and Retrieve a Simple Image Value', 'We do the same thing, but this time, we use a small GIF image.');
    kvp_run_test(65, 'PASS - Change a Simple Text Value', 'We go back in, and change the value we previously stored, and make sure it comes out proper.');
    kvp_run_test(66, 'PASS - Delete Text Value', 'We simply delete the text value, logged in with the account that created it.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(67, 'PASS - Delete Image Value', 'We simply delete the image value, logged in with another account, which has write permissions.', 'asp', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function kvp_test_63() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'keymaster';
    $value = 'Rick Moranis';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The value \''.$value .'\' was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_64() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'Honey Badger Don\'t Care';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/honey_badger_dont_care.gif');
    $image_1_base64_data = base64_encode ($value);
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The image was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                $image_2_base64_data = base64_encode ($fetched_value);
                echo('<h3 style="color:green">The values match!</h3>');
                echo('<h4>BEFORE:</h4>');
                echo('<img src="data:image/jpeg;base64,'.$image_1_base64_data.'" alt="Nice View" />');
                echo('<h4>AFTER:</h4>');
                echo('<img src="data:image/jpeg;base64,'.$image_2_base64_data.'" alt="Nice View" />');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_65() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'keymaster';
    $value = 'ZOOL\'S BITCH';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The value \''.$value .'\' was successfully changed for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_66($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'keymaster';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof Andisol)) {
        if ($andisol_instance1->delete_key($key)) {
            echo('<h3 style="color:green">The value was successfully deleted!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
    }
    
    $andisol_instance2 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:red">PROBLEM! This should be gone!</h3>');
        } else {
            echo('<h3 style="color:green">The value is gone, as it should be!</h3>');
        }
    }
}

function kvp_test_67($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'Honey Badger Don\'t Care';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof Andisol)) {
        if ($andisol_instance1->delete_key($key)) {
            echo('<h3 style="color:green">The value was successfully deleted!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
    }
    
    $andisol_instance2 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:red">PROBLEM! This should be gone!</h3>');
        } else {
            echo('<h3 style="color:green">The value is gone, as it should be!</h3>');
        }
    }
}

// -------------------------------- STRUCTURE ---------------------------------------------

function kvp_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(TRUE);
            $function_name = sprintf('kvp_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(TRUE) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('kvp_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">KEY/VALUE TESTS</h1>');
        echo('<div id="kvp-small-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'kvp-small-tests\')">SMALL VALUE TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                kvp_small_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
