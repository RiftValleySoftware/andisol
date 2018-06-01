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
set_time_limit ( 60 * 60 * 2 );
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function basic_collection_run_tests() {
    collection_run_test(93, 'FAIL -Create Generic Collection (Not Logged In)', 'We do not log in, and attempt to create a collection. Like all attempts to create data when not logged in, this should fail.');
    collection_run_test(94, 'PASS -Create Generic Collection (Logged In)', 'We log in, and attempt to create a collection. This should succeed.', 'asp', '', 'CoreysGoryStory');
    collection_run_test(95, 'PASS -Create Initialized Generic Collection', 'We log in, and attempt to create a collection with a bunch of existing children records.', 'asp', '', 'CoreysGoryStory');
    collection_run_test(96, 'PASS -Create Nested Initialized Generic Collection', 'We log in, and attempt to create a collection with a bunch of existing children records. However, this time, we create a small nested hierarchy.', 'asp', '', 'CoreysGoryStory');
    collection_run_test(97, 'PASS -Observe Resricted Collection', 'In this one, we change the read ID of one of the children (item 9) to be one that the \'norm\' ID can\'t see, then make sure it\'s gone.', 'norm', '', 'CoreysGoryStory');
}

function more_collection_run_tests() {
    collection_run_test(98, 'PASS -Create A Deep, Wide Collection Hierarchy', 'We log in as the \'asp\' login, and create a fairly substantial little hierarchy that will be used for the following tests. This will create about 3,000 collection records.', 'asp', '', 'CoreysGoryStory');
    collection_run_test(99, 'PASS -Log In With God', 'We log in as the \'God\' login, and see what records we can see.', 'admin', '', CO_Config::god_mode_password());
    collection_run_test(100, 'PASS -Log In With A Limited Visibility Login', 'We log in as the \'norm\' login, and see what records we can see.', 'norm', '', 'CoreysGoryStory');
    collection_run_test(101, 'PASS -Log In With A Different Limited Visibility Login', 'We log in as the \'bob\' login, and see what records we can see (different ones from \'norm\').', 'bob', '', 'CoreysGoryStory');
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

function collection_test_096($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $initial_ids = Array(3, 4, 5);
        
        $collection_instance1 = $andisol_instance->create_collection($initial_ids);
        
        if (isset($collection_instance1) && ($collection_instance1 instanceof CO_Collection)) {
            echo('<h3 style="color:green">The Collection object was created!</h3>');
            $collection_instance2 = $andisol_instance->create_collection(Array($collection_instance1->id()));
        
            if (isset($collection_instance2) && ($collection_instance2 instanceof CO_Collection)) {
                echo('<h3 style="color:green">The Collection object was created!</h3>');
                 $collection_instance3 = $andisol_instance->create_collection(Array(7, $collection_instance1->id(), $collection_instance2->id()));
        
                if (isset($collection_instance3) && ($collection_instance3 instanceof CO_Collection)) {
                    echo('<h3 style="color:green">The Collection object was created!</h3>');
                    display_record($collection_instance3);
                } else {
                    echo('<h3 style="color:red">The Collection object was not created!</h3>');
                    if (isset($andisol_instance->error)) {
                        echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                    }
                }
            } else {
                echo('<h3 style="color:red">The Collection object was not created!</h3>');
                if (isset($andisol_instance->error)) {
                    echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                }
            }
        } else {
            echo('<h3 style="color:red">The Collection object was not created!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function collection_test_097($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $collection = $andisol_instance->get_single_data_record_by_id(10);
        
        if (isset($collection) && ($collection instanceof CO_Collection)) {
            echo('<h3 style="color:green">We have a collection!</h3>');
            echo('<h4 style="color:green;font-style:italic">This is the hierarchy before we change the read ID of item 9. Note that we cannot see item 3. This is because the \'norm\' ID does not have that token:</h4>');
            display_record($collection);
            $andisol_instance2 = make_andisol('asp', '', 'CoreysGoryStory');
            $item_9 = $andisol_instance2->get_single_data_record_by_id(9);
        
            if (isset($item_9) && ($item_9 instanceof CO_Collection)) {
                if (!$item_9->set_read_security_id(9)) {
                    echo('<h3 style="color:red">We were unable to change the security setting for Item 9!</h3>');
                    if (isset($item_9->error)) {
                        echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$item_9->error->error_code.') '.$item_9->error->error_name.' ('.$item_9->error->error_description.')</p>');
                    }
                    return;
                }
            }
            
            echo('<h4 style="color:green;font-style:italic">This is the hierarchy after we change the read ID of item 9 to one we can\'t see:</h4>');

            echo('<h5 style="color:green;font-style:italic">This is the cached copy. Note that we still see item 9:</h5>');
            display_record($collection);
            
            $collection->reload_collection();
            
            echo('<h5 style="color:green;font-style:italic">This is the cached copy, but after we reload the collection. Note that we no longer see item 9:</h5>');
            display_record($collection);
            
            $collection2 = $andisol_instance->get_single_data_record_by_id(10);
        
            if (isset($collection2) && ($collection2 instanceof CO_Collection)) {
                echo('<h5 style="color:green;font-style:italic">This is another copy that was loaded in (also was cached, but we don\'t know that):</h5>');
                display_record($collection2);
            }
        }
    }
}

function create_hierarchy_node($depth, $andisol_instance, $collection) {
    $ret = FALSE;
    
    if (0 < $depth) {
        for ($i = 1; $i < 6; $i++) {
            $collection_instance = $andisol_instance->create_collection();
            if (isset($collection_instance) && ($collection_instance instanceof CO_Collection)) {
                $d = 6 - $depth;
                $collection_instance->set_name($collection->name."->$d->$i");
                $collection_instance->set_read_security_id($i + 1);
                $collection->appendElement($collection_instance);
                create_hierarchy_node($depth - 1, $andisol_instance, $collection_instance);
            } else {
                echo('<h3 style="color:red">Error Setting Up Collections!</h3>');
                if (isset($andisol_instance->error)) {
                    echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
                }
                
                break;
            }
        }
    }
 
    return $ret;
}

function collection_test_098($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL, $tag) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $collection = $andisol_instance->create_collection();
    
        if (isset($collection) && ($collection instanceof CO_Collection)) {
            if ($collection->set_name("0")) {
                $st1 = microtime(true);
                create_hierarchy_node(5, $andisol_instance, $collection);
                $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
                echo("<h4>The test took $fetchTime seconds to create the hierarchy.</h4>");
            } else {
                echo('<h3 style="color:red">Error Setting Up Collections!</h3>');
                if (isset($collection->error)) {
                    echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$collection->error->error_code.') '.$collection->error->error_name.' ('.$collection->error->error_description.')</p>');
                }
            }
        }
    }
}

function collection_test_099($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL, $tag) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $st1 = microtime(true);
        $collection = $andisol_instance->get_single_data_record_by_id(11);
        $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
        echo("<h4>The test took $fetchTime seconds to get the collection object.</h4>");
    
        if (isset($collection) && ($collection instanceof CO_Collection)) {
            $st1 = microtime(true);
            $hierarchy = $collection->getHierarchy();
            $fetchTime = sprintf('%01.4f', microtime(true) - $st1);
            echo("<h4>The test took $fetchTime seconds to get the hierarchy.</h4>");
            display_login_report($collection);
            display_raw_hierarchy($hierarchy, $tag);
        } else {
            echo('<h3 style="color:red">Error Accessing Collections!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function collection_test_100($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    collection_test_099($in_login, $in_hashed_password, $in_password, 'test-2');
}

function collection_test_101($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    collection_test_099($in_login, $in_hashed_password, $in_password, 'test-3');
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
            $function_name($in_login, $in_hashed_password, $in_password, 'original');
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
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'basic-storage-collection-tests\')">BASIC INSTANTIATION AND CONCEALING TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                basic_collection_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="more-storage-collection-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'more-storage-collection-tests\')">HIERARCHY AND DELETION TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                more_collection_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
