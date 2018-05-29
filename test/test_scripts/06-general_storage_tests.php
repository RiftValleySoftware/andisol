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

function generic_storage_run_tests() {
    generic_storage_run_test(88, 'FAIL -Try to Create a Blank Generic Data Record (Not Logged In)', 'We don\'t log in, and try to create a generic record.');
    generic_storage_run_test(89, 'PASS -Try to Create a Blank Generic Data Record (Logged In)', 'We now log in, and try it again.', 'asp', '', 'CoreysGoryStory');
    generic_storage_run_test(90, 'FAIL -Try to Delete the Record We Just Created (Not Logged In)', 'We don\'t log in, and try a deletion.');
    generic_storage_run_test(91, 'FAIL -Try to Delete the Record We Just Created With A Read-Only Login', 'We now log in with an ID that has read access, but no write access, and try to delete the new record.', 'norm', '', 'CoreysGoryStory');
    generic_storage_run_test(92, 'PASS -Try to Delete the Record We Just Created With Another Write-Capable Login', 'We now log in with an ID that has write access to the record, and try the deletion again. If the deletion is successful, it will try to resurrect the object from its old data record object.', 'bob', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function generic_storage_test_088($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $generic_object = $andisol_instance->create_general_data_item();
        
        if (isset($generic_object) && ($generic_object instanceof CO_Main_DB_Record)) {
            echo('<h3 style="color:green">We created a new record!</h3>');
            if (6 == $generic_object->id()) {
                echo('<h4 style="color:green">The IDs match!</h4>');
                if ($generic_object->set_read_security_id(0)) {
                    display_record($generic_object);
                } else {
                    echo('<h3 style="color:red">We could not set the scurity ID!</h3>');
                    if (isset($andisol_instance->error)) {
                        echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                    }
                }
            } else {
                echo('<h4 style="color:red">The IDs do not match!</h4>');
            }
        } else {
            echo('<h3 style="color:red">We could not create a record!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function generic_storage_test_089($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    generic_storage_test_088($in_login, $in_hashed_password, $in_password);
}

function generic_storage_test_090($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $generic_object = $andisol_instance->get_single_data_record_by_id(6);
        
        if (isset($generic_object) && ($generic_object instanceof CO_Main_DB_Record)) {
            echo('<h3 style="color:green">We found the new record!</h3>');
            echo('<h5 style="color:green;font-style:italic">This is the object before the delete attempt:</h5>');
            display_record($generic_object);
            if ($andisol_instance->delete_item_by_id(6)) {
                $generic_object2 = $andisol_instance->get_single_data_record_by_id(6);
                if (isset($generic_object2) && ($generic_object2 instanceof CO_Main_DB_Record)) {
                    echo('<h3 style="color:red">ERROR! We\'re not supposed to have the record anymore!</h3>');
                    if (isset($andisol_instance->error)) {
                        echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                    }
                    echo('<div style="color:red">');
                    display_record($generic_object);
                    echo('</div>');
                } else {
                    echo('<h3 style="color:green">The record was properly deleted!</h3>');
                    echo('<h5 style="color:green;font-style:italic">Note that the ID of our local copy of the record is now 0, as it is no longer in the database:</h5>');
                        display_record($generic_object);
                    echo('<h4 style="color:green">Throw the switch, Igor!</h4>');
                    if ($generic_object->update_db()) {
                        echo('<h4 style="color:green">They laughed at me at Hiedelberg! They said I was mad -MAD! BWUHA-HA-HAAAH!!</h4>');
                        echo('<h5 style="color:green;font-style:italic">This is the resurrected object. Note that it has a new ID:</h5>');
                        display_record($generic_object);
                    } else {
                        echo('<h3 style="color:red">Igor failed to throw the switch!</h3>');
                        if (isset($andisol_instance->error)) {
                            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                        }
                    }
                }
            } else {
                echo('<h3 style="color:red">The deletion failed!</h3>');
                if (isset($andisol_instance->error)) {
                    echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                }
            }
        } else {
            echo('<h3 style="color:red">We could not access the record!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function generic_storage_test_091($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    generic_storage_test_090($in_login, $in_hashed_password, $in_password);
}

function generic_storage_test_092($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    generic_storage_test_090($in_login, $in_hashed_password, $in_password);
}

// -------------------------------- STRUCTURE ---------------------------------------------

function generic_storage_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(true);
            $function_name = sprintf('generic_storage_test_%03d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

ob_start();
    prepare_databases('general_storage_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">GENERAL STORAGE TESTS</h1>');
        echo('<div id="basic-generic-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'basic-generic-tests\')">BASIC GENERIC TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                generic_storage_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
