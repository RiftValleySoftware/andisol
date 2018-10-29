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

function search_run_tests() {
    search_run_test(45, 'PASS - Simple Location Search', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial. We expect to get 63 meetings.');
    search_run_test(46, 'FAIL - Simple Location Search', 'In this case, we search in Chicago. We should get no responses.');
    search_run_test(47, 'PASS - Paged Location Search', 'Again, back to the Lincoln Memorial, but this time, we are searching in pages of ten.');
    search_run_test(48, 'FAIL - Paged Location Search', 'Going back to Chicago, looking for pages of ten.');
    search_run_test(49, 'PASS - Simple Location Search (Count)', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial; however, this time, we ask only for the count (63).');
    search_run_test(50, 'PASS - Simple Location Search (IDs)', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial; however, this time, we ask only for the IDs of the records.');
    search_run_test(51, 'PASS - Simple Location Search (Paged Count)', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial; however, this time, we ask only for the count (which comes to 3), and for the last page in pages of ten.');
    search_run_test(52, 'PASS - Simple Location Search (Paged IDs)', 'The DB is preloaded with a bunch of meetings in the DC area, so we do a simple 5Km search, based on the Lincoln Memorial; however, this time, we ask only for the IDs of the records, and for the last page in pages of ten.');
}

function user_search_run_tests() {
    search_run_test(53, 'PASS - Get All Users (God)', 'Log in as the God admin, and see which users we can find.', 'admin', '', CO_Config::god_mode_password());
    search_run_test(54, 'PASS - Get All Users (Main Manager)', 'Log in as the main manager, and see which users we can find. The difference should be that we don\'t see the \'God\' admin user now.', 'DCAreaManager', '', 'CoreysGoryStory');
    search_run_test(55, 'PASS - Get All Login Users (Main Manager)', 'Log in as the main manager, and see which login (not standalone) users we can find.', 'DCAreaManager', '', 'CoreysGoryStory');
    search_run_test(56, 'PASS - Get All Standalone Users (Main Manager)', 'Log in as the main manager, and see which standalone (not login) users we can find.', 'DCAreaManager', '', 'CoreysGoryStory');
    search_run_test(57, 'PASS - Get All Users (DC Manager)', 'Log in as the DC manager, and see which users we can find.', 'DCAdmin', '', 'CoreysGoryStory');
    search_run_test(58, 'PASS - Get All Writeable Users (DC Manager)', 'Log in as the DC manager, and see which users we can find. This time, however, we are looking for ones we have write permissions on. We will end up with just one (us).', 'DCAdmin', '', 'CoreysGoryStory');
    search_run_test(59, 'PASS - Get All Login Users (DC Manager)', 'Log in as the DC manager, and see which login (not standalone) users we can find.', 'DCAdmin', '', 'CoreysGoryStory');
    search_run_test(60, 'PASS - Get All Writeable Login Users (DC Manager)', 'Log in as the DC manager, and see which login (not standalone) users we can find. This time, we look for writeable ones, which will give us just one.', 'DCAdmin', '', 'CoreysGoryStory');
    search_run_test(61, 'PASS - Get All Standalone Users (DC Manager)', 'Log in as the DC manager, and see which standalone (not login) users we can find.', 'DCAdmin', '', 'CoreysGoryStory');
    search_run_test(62, 'FAIL - Get All Writeable standalone Users (DC Manager)', 'Log in as the DC manager, and see which standalone (not login) users we can find. This time, we look for writeable ones, which will give us nothing.', 'DCAdmin', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function search_test_45($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
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

function search_test_46($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
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

function search_test_47($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $page = 0;
        $total = 0;
        
        do {
            $returned_array = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 10, $page);
        
            if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
                echo('<hr style="margin-top:1em;" /><h3 style="text-align:center;color:green">Page '.$page.' ('.count($returned_array).' responses):</h3><hr style="margin-bottom:0.25em;" />');
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

function search_test_48($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $page = 0;
        $total = 0;
        
        do {
            $returned_array = $andisol_instance->location_search(-87.621887, 41.876465, 5.0, 10, $page);
        
            if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
                echo('<hr style="margin-top:1em;" /><h3 style="text-align:center;color:green">Page '.$page.' ('.count($returned_array).' responses):</h3><hr style="margin-bottom:0.25em;" />');
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

function search_test_49($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $returned_integer = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 0, 0, false, true);
        
        if (isset($returned_integer) && $returned_integer) {
            echo('<h3 style="color:green">We got '.$returned_integer.' responses to the location search.</h3>');
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_50($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $returned_array = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 0, 0, false, false, true);
        
        if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
            echo('<h3 style="color:green">We got '.count($returned_array).' responses to the location search:</h3>');
            echo('<div style="font-family:Courier,Monospace">');
            echo('<span style="float:left;width:4em;display:block;text-align:right">'.implode(',</span><span style="float:left;width:4em;display:block;text-align:right">', $returned_array).'</span>');
            echo('<div style="clear:both"></div></div>');
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_51($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $returned_integer = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 10, 6, false, true);
        
        if (isset($returned_integer) && $returned_integer) {
            echo('<h3 style="color:green">We got '.$returned_integer.' responses to the location search.</h3>');
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_52($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $returned_array = $andisol_instance->location_search(-77.0502, 38.8893, 5.0, 10, 6, false, false, true);
        
        if (isset($returned_array) && is_array($returned_array) && count($returned_array)) {
            echo('<h3 style="color:green">We got '.count($returned_array).' responses to the location search:</h3>');
            echo('<div style="font-family:Courier,Monospace">');
            echo('<span style="float:left;width:4em;display:block;text-align:right">'.implode(',</span><span style="float:left;width:4em;display:block;text-align:right">', $returned_array).'</span>');
            echo('<div style="clear:both"></div></div>');
        } else {
            echo('<h3 style="color:red">We got NUTHIN\'!</h3>');
        }
    }
}

function search_test_53($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_users();
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
}

function search_test_54($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    search_test_53($in_login, $in_hashed_password, $in_password);
}

function search_test_55($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_login_users();
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
}

function search_test_56($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_standalone_users();
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
}

function search_test_57($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    search_test_53($in_login, $in_hashed_password, $in_password);
}

function search_test_58($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_users(true);
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
}

function search_test_59($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    search_test_55($in_login, $in_hashed_password, $in_password);
}

function search_test_60($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_login_users(true);
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
}

function search_test_61($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    search_test_56($in_login, $in_hashed_password, $in_password);
}

function search_test_62($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $all_users = $andisol_instance->get_all_standalone_users(true);
        
        if (isset($all_users) && is_array($all_users) && count($all_users)) {
            echo('<h3 style="color:green">We got '.count($all_users).' responses to the user search:</h3>');
            foreach ($all_users as $record) {
                display_record($record);
            }
        } else {
            echo('<h3 style="color:red">We got NOTHING!</h3>');
        }
    }
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
            $st1 = microtime(true);
            $function_name = sprintf('search_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
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
            
                $start = microtime(true);
                
                search_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="user-search-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'user-search-tests\')">USER SEARCH TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                user_search_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
