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
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function personal_id_run_tests() {
    personal_id_run_test(104, 'PASS - Test Get Personal IDs', 'Make Sure that Item 3 Has 8 and 9 as Personal Tokens, Item 4 has 10 and 11, and Item 5 has no personal tokens.', 'admin', '', CO_Config::god_mode_password());
}

// -------------------------------- TESTS ---------------------------------------------

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
                                
                                if (!count($personal_ids)) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">ID 5 PASSES!</h4>');
                                } else {
                                    echo('<h4 style="color:red;font-weight:bold; color: red">ID 5 FAILS!</h4>');
                                }
                                
                                if ([8,9,10,11] == $all_ids) {
                                    echo('<h4 style="color:red;font-weight:bold; color: green">ALL IDS PASSES!</h4>');
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

// -------------------------------- STRUCTURE ---------------------------------------------

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

ob_start();
    prepare_databases('personal_id_test');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">PERSONAL TOKEN TESTS</h1>');
        
        echo('<div id="personal_id-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'personal_id-tests\')">BASIC PERSONAL ID TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                personal_id_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
