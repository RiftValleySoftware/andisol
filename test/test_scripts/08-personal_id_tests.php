<?php
/***************************************************************************************************************************/
/**
    ANDISOL Object Model Layer
    
    © Copyright 2018, The Great Rift Valley Software Company
    
    LICENSE:
    
    MIT License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
    modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

    The Great Rift Valley Software Company: https://riftvalleysoftware.com
*/
require_once(dirname(dirname(__FILE__)).'/functions.php');
set_time_limit ( 300 ); // More than five minutes is a problem.
    
global $global_num_ids;
global $global_max_num_personal_ids;

$global_num_ids = 25;
$global_max_num_personal_ids = 200;

// ----------------------------------- BASIC TESTS ----------------------------------------------

//##############################################################################################################################################

function personal_id_run_basic_tests() {
    personal_id_run_test(104, 'PASS - Test Get Personal IDs', 'Make Sure that Item 3 Has 8 and 9 as Personal Tokens, Item 4 has 10 and 11, Item 5 has no personal tokens, and all the personal tokens are reported when requested.', 'admin', '', CO_Config::god_mode_password());
    personal_id_run_test(105, 'PASS - Test Change Personal IDs', 'Log in as the God Admin, remove IDs from one record, change the personal IDs of one record, and add new IDs to another.', 'admin', '', CO_Config::god_mode_password());
}

//##############################################################################################################################################

function personal_id_test_104($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $test_items = $andisol_instance->get_chameleon_instance()->get_multiple_security_records_by_id([3,4,5]);
        echo('<div class="inner_div">');
            if ( isset($test_items) ) {
                if (is_array($test_items)) {
                    if (count($test_items)) {
                            echo('<div class="inner_div">');
                            $all_ids = $andisol_instance->get_all_personal_ids_except_for_id();
                            if (isset($all_ids) && is_array($all_ids) && count($all_ids)) {
                                $personal_ids = $test_items[0]->personal_ids();
                                
                                if ([8,9] == $personal_ids) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">ID 3 PASSES!</h4>');
                                } else {
                                    echo('<h4 style="color:red;font-weight:bold; color: red">ID 3 FAILS!</h4>');
                                }
                                
                                $personal_ids = $test_items[1]->personal_ids();
                                
                                if ([10,11] == $personal_ids) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">ID 4 PASSES!</h4>');
                                } else {
                                    echo('<h4 style="color:red;font-weight:bold; color: red">ID 4 FAILS!</h4>');
                                }
                                
                                $personal_ids = $test_items[2]->personal_ids();
                                
                                if (!isset($personal_ids) || !count($personal_ids)) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">ID 5 PASSES!</h4>');
                                } else {
                                    echo('<h4 style="color:red;font-weight:bold; color: red">ID 5 FAILS!</h4>');
                                }
                                
                                if ([8,9,10,11] == $all_ids) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">GET ALL IDS TEST PASSES!</h4>');
                                } else {
                                    echo('<h4 style="color:red;font-weight:bold; color: red">ALL IDS FAILS!</h4>');
                                }
                            } else {
                                echo('<h4 style="color:red;font-weight:bold; color: red">NO GLOBAL IDS!</h4>');
                            }
                        } else {
                            echo("<h4>NO ITEMS!</h4>");
                        }
                    } else {
                        echo('<h4 style="color:red;font-weight:bold; color: red">NO ARRAY!</h4>');
                    }
                } else {
                    echo("<h4 style=\"color:red;font-weight:bold; color: red\">NOTHING RETURNED!</h4>");
                }
            echo('</div>');
        echo('</div>');
    } else {
        echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em; color: red">We do not have an ANDISOL instance!</h4></div>');
    }
}

//##############################################################################################################################################

function personal_id_test_105($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $test_items = $andisol_instance->get_chameleon_instance()->get_multiple_security_records_by_id([3,4,5]);
        echo('<div class="inner_div">');
            if ( isset($test_items) ) {
                if (is_array($test_items)) {
                    if (3 == count($test_items)) {
                        $pass = true;
                        if (3 == count($test_items)) {
                            echo("<h4>BEFORE:</h4>");
                            foreach ( $test_items as $item ) {
                                display_record($item);
                            }
                        
                            $result = $test_items[0]->set_personal_ids([]);
                            
                            if (!is_array($result) || count($result)) {
                                $pass = false;
                                echo("<h4 style=\"color:red;font-weight:bold\">UNEXPECTED RESULT (".print_r($result, true).") FOR ITEM 3!</h4>");
                            }
                            
                            $result = $test_items[1]->set_personal_ids([8,9]);
                            
                            if (!is_array($result) || 2 != count($result) || $result[0] != 8 || $result[1] != 9) {
                                $pass = false;
                                echo("<h4 style=\"color:red;font-weight:bold\">UNEXPECTED RESULT (".print_r($result, true).") FOR ITEM 4!</h4>");
                            }
                            
                            $result = $test_items[2]->set_personal_ids([10,11]);
                            
                            if (!is_array($result) || 2 != count($result) || $result[0] != 10 || $result[1] != 11) {
                                $pass = false;
                                echo("<h4 style=\"color:red;font-weight:bold\">UNEXPECTED RESULT (".print_r($result, true).") FOR ITEM 5!</h4>");
                            }
                            
                            echo('<h4 style="margin-top:1em">AFTER:</h4>');
                            
                            foreach ( $test_items as $item ) {
                                display_record($item);
                            }
                            
                            $all_ids = $andisol_instance->get_all_personal_ids_except_for_id();
                            if (isset($all_ids) && is_array($all_ids) && count($all_ids)) {
                                if (4 == count($all_ids)) {
                                    if ([8,9,10,11] == $all_ids) {
                                        $all_ids_string = implode(", ", $all_ids);
                                        echo('<div><strong style=\"color:green;font-weight:bold\">All Personal IDs:</strong> '.htmlspecialchars($all_ids_string).'</div>');
                                    } else {
                                        $pass = false;
                                        echo("<h4 style=\"color:red;font-weight:bold\">INCORRECT GLOBAL IDS!</h4>");
                                    }
                                } else {
                                    $pass = false;
                                    echo("<h4 style=\"color:red;font-weight:bold\">INCORRECT NUMBER OF GLOBAL IDS!</h4>");
                                }
                            } else {
                                $pass = false;
                                echo("<h4 style=\"color:red;font-weight:bold\">NO GLOBAL IDS!</h4>");
                            }
                            
                            if ($pass) {
                                echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES</h4>");
                            }
                        }
                    } else {
                        echo("<h4 style=\"color:red;font-weight:bold\">UNEXPECTED NUMBER OF ITEMS!</h4>");
                        $pass = false;
                    }
                } else {
                    echo("<h4 style=\"color:red;font-weight:bold\">NO ARRAY!</h4>");
                }
            } else {
                echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
            }
        
        echo('</div>');
    }
}

// ---------------------------------- ADVANCED TESTS --------------------------------------------

//##############################################################################################################################################

function personal_id_run_advanced_tests() {
    global $global_num_ids;
    global $global_max_num_personal_ids;
    personal_id_run_test(106, 'PASS- CREATE AND CHECK '.$global_num_ids.' RANDOM IDS', 'Sign in as the \'tertiary\' Admin, create '.$global_num_ids.' IDs of random types (either user or manager), with random numbers of personal IDs (between 0 and '.$global_max_num_personal_ids.'), then ensure that the IDs and types match.', 'tertiary', '', 'CoreysGoryStory');
    personal_id_run_test(107, 'PASS- CHECK FOR VISIBILITY (MANAGER)', 'Log in as a non-God Admin, and check to see if the IDs are hidden properly. We should see 8 and 9 for Item 3 (Our login), and no personal IDs for either of the other logins.', 'secondary', '', 'CoreysGoryStory');
    personal_id_run_test(108, 'PASS- CHECK FOR VISIBILITY (GOD)', 'Log in as a God Admin, and check to see if the IDs are displayed properly. We should see 8 and 9 for Item 3, 10 and 11 for Item 4, and no IDs for item 5.', 'admin', '', CO_Config::god_mode_password());
    personal_id_run_test(109, 'PASS- ADD TOKEN', 'Log in as a non-God Admin, and set a personal token (9) to another ID.', 'secondary', '', 'CoreysGoryStory');
    personal_id_run_test(110, 'FAIL- ADD TOKEN', 'Log in as a non-God Admin, and try the same, but with a non-personal ID (6).', 'secondary', '', 'CoreysGoryStory');
    personal_id_run_test(111, 'PASS- REMOVE TOKEN', 'Log in as a non-God Admin, and remove token 9 from the ID we assigned it to, before.', 'secondary', '', 'CoreysGoryStory');
    personal_id_run_test(112, 'FAIL- REMOVE TOKEN', 'Log in as a non-God Admin, and try to remove token 3 (non-personal) from ID 4.', 'secondary', '', 'CoreysGoryStory');
}

//##############################################################################################################################################

function personal_id_test_106($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        echo('<div class="inner_div">');
            global $global_num_ids;
            global $global_max_num_personal_ids;

            
            $tracker = [];
    
            set_time_limit ( max(30, intval($global_num_ids) * 2) );

            for ($index = 0; $index < $global_num_ids; $index++) {
                $is_manager = rand(0, 1);
                $num_personal_ids = intval(rand(0, $global_max_num_personal_ids));
                $login_id = ($is_manager ? "manager" : "user")."_".strval($index);
                $tracker[] = ['login_id' => $login_id, 'is_manager' => $is_manager, 'num_personal_ids' => $num_personal_ids];
                make_one_user($andisol_instance->get_cobra_instance(), $login_id, $is_manager, $num_personal_ids);
            }
    
            if (is_array($tracker) && count($tracker)) {
                $pass = true;
                $god_access_instance = new CO_Access('admin', NULL, CO_Config::god_mode_password());
                foreach ($tracker as $track) {
                    $pass = $pass || examine_one_user($god_access_instance, $track);
                }
        
                echo('<div id="personal_id-tests-advanced-results" class="closed">');
                    if ($pass) {
                        echo('<h4 class="header"><a href="javascript:toggle_main_state(\'personal_id-tests-advanced-results\')"><span style="color:green">TEST PASSES</span></a></h4>');
                    } else {
                        echo('<h4 class="header"><a href="javascript:toggle_main_state(\'personal_id-tests-advanced-results\')"><span style="color:red">TEST FAILS</span></a></h4>');
                    }
                    echo('<div class="container">');
                        foreach ($tracker as $track) {
                            display_one_user($god_access_instance, $track);
                        }
                    echo('</div>');
                echo('</div>');
            } else {
                echo("<h2 style=\"color:red;font-weight:bold\">Failed to Create Users!</h2>");
            }
    
            set_time_limit ( 30 );
        echo('</div>');
    }
}

//##############################################################################################################################################

function personal_id_test_107($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        if ($andisol_instance->get_chameleon_instance()->security_db_available()) {
            echo('<div class="inner_div">');
                if (user_check($in_login, $in_hashed_password, $in_password, [3,4,5], [[8,9],[],[]])) {
                    echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                } else {
                    echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                }
            echo('</div>');
        } else {
            echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em">We do not have a security DB</h4></div>');
        }
    }
}

//##############################################################################################################################################

function personal_id_test_108($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        if ($andisol_instance->get_chameleon_instance()->security_db_available()) {
            echo('<div class="inner_div">');
                if (user_check($in_login, $in_hashed_password, $in_password, [3,4,5], [[8,9],[10,11],[]])) {
                    echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                } else {
                    echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                }
            echo('</div>');
        } else {
            echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em">We do not have a security DB</h4></div>');
        }
    }
}

//##############################################################################################################################################

function personal_id_test_109($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $access_instance = NULL;
    $id_to_change = 5;
    $token = 9;
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $access_instance = $andisol_instance->get_chameleon_instance();
        $access_instance_god = new CO_Access('admin', '', CO_Config::god_mode_password());
        if ($access_instance->security_db_available()) {
            echo('<div class="inner_div">');
                $source_item = $access_instance->get_login_item();
                $test_item = $access_instance->get_single_security_record_by_id($id_to_change);
                $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                echo('<div class="inner_div">');
                    echo('<div class="inner_div">');
                        if (isset($source_item) && isset($test_item)) {
                            echo("<h4>BEFORE</h4>");
                            echo("<h5>Our Login Record:</h5>");
                            display_record($source_item);
                            echo("<h5>The record we will change:</h5>");
                            display_record($examination_item);
                            $test_ids_before = $examination_item->ids();
                            $success = $andisol_instance->add_personal_token_from_current_login($test_item->id(), $token);
                            $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                            $test_ids_after = $examination_item->ids();
                            $success = $success && !in_array($token, $test_ids_before) && in_array($token, $test_ids_after);
                            echo('<div style="text-align:center"><img src="images/'.($success ? 'magic.gif' : 'fail.gif').'" style="margin:auto" /></div>');
                            echo("<h5>The changed record:</h5>");
                            display_record($examination_item);
                            if ($success) {
                                echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                            } else {
                                echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                            }
                        } else {
                            echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
                        }
                    echo('</div>');
                echo('</div>');
            echo('</div>');
        } else {
            echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em">We do not have a security DB</h4></div>');
        }
    }
}

//##############################################################################################################################################

function personal_id_test_110($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $access_instance = NULL;
    $id_to_change = 5;
    $token = 6;
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $access_instance = $andisol_instance->get_chameleon_instance();
        $access_instance_god = new CO_Access('admin', '', CO_Config::god_mode_password());
        if ($access_instance->security_db_available()) {
            echo('<div class="inner_div">');
                $source_item = $access_instance->get_login_item();
                $test_item = $access_instance->get_single_security_record_by_id($id_to_change);
                $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                echo('<div class="inner_div">');
                    echo('<div class="inner_div">');
                        if (isset($source_item) && isset($test_item)) {
                            echo("<h4>BEFORE</h4>");
                            echo("<h5>Our Login Record:</h5>");
                            display_record($source_item);
                            echo("<h5>The record we will change:</h5>");
                            display_record($examination_item);
                            $test_ids_before = $examination_item->ids();
                            $success = $andisol_instance->add_personal_token_from_current_login($test_item->id(), $token);
                            $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                            $test_ids_after = $examination_item->ids();
                            $success = $success && !in_array($token, $test_ids_before) && in_array($token, $test_ids_after);
                            echo('<div style="text-align:center"><img src="images/'.($success ? 'magic.gif' : 'fail.gif').'" style="margin:auto" /></div>');
                            echo("<h5>The changed record:</h5>");
                            display_record($examination_item);
                            if ($success) {
                                echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                            } else {
                                echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                            }
                        } else {
                            echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
                        }
                    echo('</div>');
                echo('</div>');
            echo('</div>');
        } else {
            echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em">We do not have a security DB</h4></div>');
        }
    }
}

//##############################################################################################################################################

function personal_id_test_111($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $access_instance = NULL;
    $id_to_change = 5;
    $token = 9;
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $access_instance_god = new CO_Access('admin', '', CO_Config::god_mode_password());
        echo('<div class="inner_div">');
        $source_item = $andisol_instance->get_login_item();
        $test_item = $andisol_instance->get_login_item($id_to_change);
        $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
        echo('<div class="inner_div">');
            echo('<div class="inner_div">');
                if (isset($source_item) && isset($test_item)) {
                    echo("<h4>BEFORE</h4>");
                    echo("<h5>Our Login Record:</h5>");
                    display_record($source_item);
                    echo("<h5>The record we will change:</h5>");
                    display_record($examination_item);
                    $test_ids_before = $examination_item->ids();
                    $success = $andisol_instance->remove_personal_token_from_this_login($test_item->id(), $token);
                    $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                    $test_ids_after = $examination_item->ids();
                    $success = $success && !in_array($token, $test_ids_after) && in_array($token, $test_ids_before);
                    echo('<div style="text-align:center"><img src="images/'.($success ? 'magic.gif' : 'fail.gif').'" style="margin:auto" /></div>');
                    echo("<h5>The changed record:</h5>");
                    display_record($examination_item);
                    if ($success) {
                        echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                    } else {
                        echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                    }
                } else {
                    echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
                }
                echo('</div>');
            echo('</div>');
        echo('</div>');
    }
}

//##############################################################################################################################################

function personal_id_test_112($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $access_instance = NULL;
    $id_to_change = 5;
    $token = 3;
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $access_instance_god = new CO_Access('admin', '', CO_Config::god_mode_password());
        echo('<div class="inner_div">');
            $source_item = $andisol_instance->get_login_item();
            $test_item = $andisol_instance->get_login_item($id_to_change);
            $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
            echo('<div class="inner_div">');
                echo('<div class="inner_div">');
                    if (isset($source_item) && isset($test_item)) {
                        echo("<h4>BEFORE</h4>");
                        echo("<h5>Our Login Record:</h5>");
                        display_record($source_item);
                        echo("<h5>The record we will change:</h5>");
                        display_record($examination_item);
                        $test_ids_before = $examination_item->ids();
                        $success = $andisol_instance->remove_personal_token_from_this_login($test_item->id(), $token);
                        $examination_item = $access_instance_god->get_single_security_record_by_id($id_to_change);
                        $test_ids_after = $examination_item->ids();
                        $success = $success && !in_array($token, $test_ids_after) && in_array($token, $test_ids_before);
                        echo('<div style="text-align:center"><img src="images/'.($success ? 'magic.gif' : 'fail.gif').'" style="margin:auto" /></div>');
                        echo("<h5>The changed record:</h5>");
                        display_record($examination_item);
                        if ($success) {
                            echo("<h4 style=\"color:green;font-weight:bold\">TEST PASSES!</h4>");
                        } else {
                            echo("<h4 style=\"color:red;font-weight:bold\">TEST FAILS!</h4>");
                        }
                    } else {
                        echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
                    }
                echo('</div>');
            echo('</div>');
        echo('</div>');
    }
}

// -------------------------------- STRUCTURE ---------------------------------------------

//##############################################################################################################################################

function personal_id_run_test($in_num, $in_title, $in_explain, $in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $test_num_string = sprintf("%03d", $in_num);
    echo('<div id="test-'.$test_num_string.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\'test-'.$test_num_string.'\')">TEST '.$in_num.': '.$in_title.'</a></h3>');
        echo('<div class="main_div inner_container">');
            echo('<div class="main_div" style="margin-right:2em">');
                echo('<p class="explain">'.$in_explain.'</p>');
            echo('</div>');
            $st1 = microtime(true);
            $function_name = sprintf('personal_id_test_%03d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password, 'original');
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to complete.</h4>");
        echo('</div>');
    echo('</div>');
}

//##############################################################################################################################################

function make_one_user($in_cobra_instance, $in_user_id, $in_is_manager, $in_number_of_personal_ids) {
    $cobra_login_instance = NULL;
    
    if ($in_is_manager) {
        $cobra_login_instance = $in_cobra_instance->create_new_manager_login($in_user_id, 'CoreysGoryStory', $in_number_of_personal_ids);
    } else {
        $cobra_login_instance = $in_cobra_instance->create_new_standard_login($in_user_id, 'CoreysGoryStory', $in_number_of_personal_ids);
    }
    
    if (!isset($cobra_login_instance) || (!($cobra_login_instance instanceof CO_Cobra_Login) && !($cobra_login_instance instanceof CO_Cobra_Login_Manager))) {
        echo("<h4 style=\"color:red;font-weight:bold\">The User instance is not valid!</h4>");
        if ($in_cobra_instance->error) {
            echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$in_cobra_instance->error->error_code.') '.$in_cobra_instance->error->error_name.' ('.$in_cobra_instance->error->error_description.')</p>');
        }
        $cobra_login_instance = NULL;
    }
    
    return $cobra_login_instance;
}

//##############################################################################################################################################

function examine_one_user($in_god_access_instance, $in_tracker) {
    $test_record = $in_god_access_instance->get_login_item_by_login_string($in_tracker['login_id']);
    if (isset($test_record) && ($test_record instanceof CO_Cobra_Login)) {
        $personal_ids = $test_record->personal_ids();
        if (!is_array($personal_ids) || (count($personal_ids) != $in_tracker['num_personal_ids'])) {
            echo("<h4 style=\"color:red;font-weight:bold\">The number of personal IDs for ".$in_tracker['login_id']." is invalid!</h4>");
        } else {
            if ($in_tracker['is_manager'] && ($test_record instanceof CO_Login_Manager)) {
                return true;
            } else {
                echo("<h4 style=\"color:red;font-weight:bold\">".$in_tracker['login_id']." should be a manager!</h4>");
            }
        }
    } else {
        echo("<h4 style=\"color:red;font-weight:bold\">The User instance is not valid!</h4>");
        echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$cobra_instance->error->error_code.') '.$cobra_instance->error->error_name.' ('.$cobra_instance->error->error_description.')</p>');
    }
    
    return false;
}

//##############################################################################################################################################

function display_one_user($in_god_access_instance, $in_tracker) {
    $test_record = $in_god_access_instance->get_login_item_by_login_string($in_tracker['login_id']);
    display_record($test_record);
}

//##############################################################################################################################################

function user_check($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL, $ids, $comparison_ids) {
    $access_instance = NULL;
    
    $pass = true;
    
    $access_instance = new CO_Access($in_login, $in_hashed_password, $in_password);
    if ($access_instance->security_db_available()) {
        echo('<div class="inner_div">');
            $test_items = $access_instance->get_multiple_security_records_by_id($ids);
            echo('<div class="inner_div">');
                echo('<div class="inner_div">');
                    if (isset($test_items)) {
                        if (is_array($test_items) && count($test_items)) {
                            $index = 0;
                            foreach ( $test_items as $item ) {
                                display_record($item);
                                $ids = $comparison_ids[$index++];
                                if ($item->personal_ids() != $ids) {
                                    echo("<h4 style=\"color:red;font-weight:bold\">ITEM $index HAS A PERSONAL ID MISMATCH!</h4>");
                                    $pass = false;
                                }
                            }
                        } else {
                            echo("<h4 style=\"color:red;font-weight:bold\">NO ARRAY!</h4>");
                            $pass = false;
                        }
                    } else {
                        echo("<h4 style=\"color:red;font-weight:bold\">NOTHING RETURNED!</h4>");
                        $pass = false;
                    }
                echo('</div>');
            echo('</div>');
        echo('</div>');
    } else {
        echo('<div class="inner_div"><h4 style="text-align:center;margin-top:0.5em">We do not have a security DB</h4></div>');
        $pass = false;
    }
    
    return $pass;
}

//##############################################################################################################################################
//                                                              MAIN CONTEXT
//##############################################################################################################################################

ob_start();
    prepare_databases('personal_id_test');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">PERSONAL TOKEN TESTS</h1>');
        
        echo('<div id="personal_id-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'personal_id-tests\')">BASIC PERSONAL ID TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                personal_id_run_basic_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        prepare_databases('personal_id_test');

        echo('<div id="personal_id-tests-2" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'personal_id-tests-2\')">ADVANCED PERSONAL ID TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                personal_id_run_advanced_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
