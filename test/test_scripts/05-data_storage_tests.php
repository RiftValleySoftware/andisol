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

function data_storage_run_tests() {
    kvp_run_test(78, 'PASS - Create Default Long/Lat', 'In this test, we simply create a very basic long/lat object, pointed at the Lincoln Memorial, in Washington DC.', 'asp', '', 'CoreysGoryStory');
    kvp_run_test(79, 'PASS - Create Fuzzy Long/Lat', 'In this test, we simply create a very basic long/lat object, pointed at the Lincoln Memorial, in Washington DC. However, this time, we give it a 10Km "fuzz factor. We then make sure that each peek is "fuzzed."', 'asp', '', 'CoreysGoryStory');
    kvp_run_test(80, 'PASS - Create Fuzzy Long/Lat', 'Same thing, but this time, we set the read ID to 0 (anyone can see the "fuzzed" value), and try looking at it from several non-logged-in instances.', 'asp', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function kvp_test_78($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $long_lat_dc_lincoln_memorial = Array('longitude' => -77.0502, 'latitude' => 38.8893);
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_instance = $andisol_instance->create_ll_location($long_lat_dc_lincoln_memorial['longitude'], $long_lat_dc_lincoln_memorial['latitude']);
        
        if (isset($long_lat_instance) && ($long_lat_instance instanceof CO_LL_Location)) {
            echo('<h3 style="color:green">Long lat object instantiated!</h3>');
            
            if (($long_lat_instance->longitude() == $long_lat_dc_lincoln_memorial['longitude']) && ($long_lat_instance->latitude() == $long_lat_dc_lincoln_memorial['latitude'])) {
                echo('<h3 style="color:green">The long/lat matches!</h3>');
            } else {
                echo('<h3 style="color:red">Long lat do not match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">Long lat object failed to instantiate!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_79($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $long_lat_dc_lincoln_memorial = Array('longitude' => -77.0502, 'latitude' => 38.8893);
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_instance = $andisol_instance->create_ll_location($long_lat_dc_lincoln_memorial['longitude'], $long_lat_dc_lincoln_memorial['latitude'], 10.0);
        
        if (isset($long_lat_instance) && ($long_lat_instance instanceof CO_LL_Location)) {
            echo('<h3 style="color:green">Long lat object instantiated!</h3>');
            echo('<p style="color:green">The initial longitude is '.$long_lat_dc_lincoln_memorial['longitude'].', and the initial latitude is '.$long_lat_dc_lincoln_memorial['latitude'].'.</p>');
            
            for ($test = 1; $test < 11; $test++) {
                $test_long = $long_lat_instance->longitude();
                $test_lat = $long_lat_instance->latitude();
            
                if (($test_long == $long_lat_dc_lincoln_memorial['longitude']) && ($test_lat == $long_lat_dc_lincoln_memorial['latitude'])) {
                    echo('<h3 style="color:red">TEST '.$test.' Fails! The long/lat should not match!</h3>');
                } else {
                    echo('<h3 style="color:green">TEST '.$test.' Succeeds! The long/lat should not match!</h3>');
                    echo('<p style="color:green">The returned longitude is '.$test_long.', and the returned latitude is '.$test_lat.'.</p>');
                }
            }
        } else {
            echo('<h3 style="color:red">Long lat object failed to instantiate!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_80($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $long_lat_dc_lincoln_memorial = Array('longitude' => -77.0502, 'latitude' => 38.8893);
    
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_instance = $andisol_instance->create_ll_location($long_lat_dc_lincoln_memorial['longitude'], $long_lat_dc_lincoln_memorial['latitude'], 10.0, NULL, 0);
        
        if (isset($long_lat_instance) && ($long_lat_instance instanceof CO_LL_Location)) {
            echo('<h3 style="color:green">Long lat object instantiated!</h3>');
            echo('<p style="color:green">The initial longitude is '.$long_lat_dc_lincoln_memorial['longitude'].', and the initial latitude is '.$long_lat_dc_lincoln_memorial['latitude'].'.</p>');
            
            $instance_id = $long_lat_instance->id();
            
            for ($test = 1; $test < 11; $test++) {
                $andisol_instance = make_andisol();
    
                if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
                    $long_lat_instance = $andisol_instance->get_single_data_record_by_id($instance_id);
                    if (isset($long_lat_instance) && ($long_lat_instance instanceof CO_LL_Location)) {
                        $test_long = $long_lat_instance->longitude();
                        $test_lat = $long_lat_instance->latitude();
            
                        if (($test_long == $long_lat_dc_lincoln_memorial['longitude']) && ($test_lat == $long_lat_dc_lincoln_memorial['latitude'])) {
                            echo('<h3 style="color:red">TEST '.$test.' Fails! The long/lat should not match!</h3>');
                        } else {
                            echo('<h3 style="color:green">TEST '.$test.' Succeeds! The long/lat should not match!</h3>');
                            echo('<p style="color:green">The returned longitude is '.$test_long.', and the returned latitude is '.$test_lat.'.</p>');
                        }
                    } else {
                        echo('<h3 style="color:red">TEST '.$test.' Fails! We can\'t instantiate a new long/lat instance!</h3>');
                    }
                } else {
                    echo('<h3 style="color:red">TEST '.$test.' Fails! We can\'t instantiate a new ANDISOL instance!</h3>');
                }
            }
        } else {
            echo('<h3 style="color:red">Long lat object failed to instantiate!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
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
    prepare_databases('data_storage_tests');
    
    echo('<div class="test-wrapper" style="display:table;margin-left:auto;margin-right:auto;text-align:left">');
        echo('<h1 class="header">DATA STORAGE TESTS</h1>');
        echo('<div id="data-storage-basic-ll-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'data-storage-basic-ll-tests\')">BASIC LONG/LAT TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                data_storage_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
