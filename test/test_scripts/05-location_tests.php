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

function data_storage_place_run_tests() {
    kvp_run_test(81, 'FAIL - Attempt Create Empty Place', 'In this test, we simply try creating a place, with no parameters, which should fail.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(82, 'PASS - Create a Place From Only Long/Lat (Auto-Geocode)', 'In this test, we create a place object, giving it only the long/lat of the Empire State Building, in NY, then expect the object to auto-geocode for the address.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(83, 'PASS - Create a Place From Only Long/Lat', 'Same thing, but this time, we squash the auto-geocode.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(84, 'PASS - Create a Place From Only Address (Auto-Lookup)', 'In this test, we create a place object, giving it only the street address of the Empire State Building, in NY, then expect the object to auto-lookup the long/lat.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(85, 'PASS - Create a Place From Only Address', 'Same thing, but this time, we squash the auto-lookup.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(86, 'FAIL - Create a Place From Only Long/Lat (Auto-Geocode)', 'In this test, we create a place object, giving it only the long/lat of the middle of the Atlantic Ocean, then expect the object to auto-geocode for the address (which should fail).', 'norm', '', 'CoreysGoryStory');
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

function kvp_test_81($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $place = $andisol_instance->create_place();
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place (which should not have happened)!</h3>');
        } else {
            echo('<h3 style="color:red">We could not create a place (which is as it should be)!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_82($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_ny_empire_state = Array('longitude' => -73.9854245, 'latitude' => 40.7484799);
    
        $place = $andisol_instance->create_ll_place($long_lat_ny_empire_state['longitude'], $long_lat_ny_empire_state['latitude']);
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place!</h3>');
            display_record($place);
        } else {
            echo('<h3 style="color:red">We could not create a place!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_83($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_ny_empire_state = Array('longitude' => -73.9854245, 'latitude' => 40.7484799);
    
        $place = $andisol_instance->create_place(FALSE, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $long_lat_ny_empire_state['longitude'], $long_lat_ny_empire_state['latitude']);
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place!</h3>');
            display_record($place);
        } else {
            echo('<h3 style="color:red">We could not create a place!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_84($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $place = $andisol_instance->create_place(TRUE, 'Empire State Building', '350 5th Avenue', 'New York', NULL, 'NY', '10118', 'US');
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place!</h3>');
            display_record($place);
        } else {
            echo('<h3 style="color:red">We could not create a place!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_85($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $place = $andisol_instance->create_place(FALSE, 'Empire State Building', '350 5th Avenue', 'New York', NULL, 'NY', '10118', 'US');
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place!</h3>');
            display_record($place);
        } else {
            echo('<h3 style="color:red">We could not create a place!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_86($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance = make_andisol($in_login, $in_hashed_password, $in_password);
    
    if (isset($andisol_instance) && ($andisol_instance instanceof Andisol)) {
        $long_lat_mid_atlantic = Array('longitude' => -39.014456, 'latitude' => 35.025099);
    
        $place = $andisol_instance->create_ll_place($long_lat_mid_atlantic['longitude'], $long_lat_mid_atlantic['latitude']);
        
        if (isset($place) && ($place instanceof CO_Place)) {
            echo('<h3 style="color:green">We successfully instantiated a place!</h3>');
            display_record($place);
        } else {
            echo('<h3 style="color:red">We could not create a place!</h3>');
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
        echo('<h1 class="header">LOCATION TESTS</h1>');
        echo('<div id="data-storage-basic-ll-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'data-storage-basic-ll-tests\')">BASIC LONG/LAT TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                data_storage_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="data-storage-basic-place-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'data-storage-basic-place-tests\')">BASIC GENERIC PLACE TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(TRUE);
                
                data_storage_place_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(TRUE) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
