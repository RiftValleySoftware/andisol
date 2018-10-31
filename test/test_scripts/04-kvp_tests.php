<?php
/***************************************************************************************************************************/
/**
    COBRA Security Administration Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
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

    Little Green Viper Software Development: https://littlegreenviper.com
*/
require_once(dirname(dirname(__FILE__)).'/functions.php');
    
// -------------------------------- TEST DISPATCHER ---------------------------------------------

function kvp_small_run_tests() {
    kvp_run_test(63, 'FAIL - Store and Retrieve a Simple Numerical Value', 'In this test, we do not log in, and attempt to store a simple numeric value to the database.');
    kvp_run_test(64, 'PASS - Store and Retrieve a Simple Numerical Value', 'Try again, but this time, logged in.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(65, 'PASS - Store and Retrieve a Simple Text Value', 'In this test, we log in as a regular user, and store a simple text value to the database, then read it back (not logged in), to make sure we have it.');
    kvp_run_test(66, 'PASS - Store and Retrieve a Simple Image Value', 'We do the same thing, but this time, we use a small GIF image.');
    kvp_run_test(67, 'PASS - Change a Simple Text Value', 'We go back in, and change the value we previously stored, and make sure it comes out proper.');
    kvp_run_test(68, 'FAIL - Get Value', 'No login. We try to retrieve a value for which a key does ot exist.');
    kvp_run_test(69, 'FAIL - Get KVP Instance Object', 'No login. We try to retrieve an object for which a key does ot exist.');
    kvp_run_test(70, 'PASS - Get KVP Instance Object', 'No login. We simply make sure that we can retrieve an object via its key.');
    kvp_run_test(71, 'PASS - Delete Text Value', 'We simply delete the text value, logged in with the account that created it.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(72, 'FAIL - Delete Image Value', 'We try to delete the image value, logged in with another account, which does not have write permissions.', 'king-cobra', '', 'CoreysGoryStory');
    kvp_run_test(73, 'PASS - Delete Image Value', 'We simply delete the image value, logged in with another account, which has write permissions.', 'asp', '', 'CoreysGoryStory');
}

function kvp_large_run_tests() {
    kvp_run_test(74, 'PASS - Store Large Text Value', 'We log in as a legit user, and store a big text item.', 'asp', '', 'CoreysGoryStory');
    kvp_run_test(75, 'PASS - Store Large Image Value', 'We log in as a legit user, and store a big image item.', 'bob', '', 'CoreysGoryStory');
    kvp_run_test(76, 'PASS - Store Large Audio Value', 'We log in as a legit user, and store a big audio item.', 'norm', '', 'CoreysGoryStory');
    kvp_run_test(77, 'PASS - Store Large Video Value', 'We log in as a legit user, and store a big video item. We won\'t display this, because we\'re using data URIs for our elements, and it\'s just too big.', 'asp', '', 'CoreysGoryStory');
}

// -------------------------------- TESTS ---------------------------------------------

function kvp_test_63($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'The Answer';
    $value = 42;
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
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
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = intval($andisol_instance2->get_value_for_key($key));
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_64($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    kvp_test_63($in_login, $in_hashed_password, $in_password);
}

function kvp_test_65() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'keymaster';
    $value = 'Rick Moranis';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
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
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_66() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'Honey Badger Don\'t Care';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/honey_badger_dont_care.gif');
    $image_1_base64_data = base64_encode ($value);
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
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
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
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
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_67() {
    $andisol_instance1 = make_andisol('norm', '', 'CoreysGoryStory');
    
    $key = 'keymaster';
    $value = 'ZOOL\'S BITCH';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
        $original_value = $andisol_instance1->get_value_for_key($key);
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The value \''.$original_value .'\' was successfully changed to \''.$value.'\' for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match! \''.$fetched_value.'\' is not what we expected to get!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_68() {
    $key = 'I Don\'t Exist!';
        
    $andisol_instance = make_andisol();
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The object for the key \''.$key.'\' has a value of \''.fetched_value.'\'</h3>');
        } else {
            echo('<h3 style="color:red">The value for the key \''.$key.'\' was not found!</h3>');
        }
    }
}

function kvp_test_69() {
    $key = 'I Don\'t Exist!';
        
    $andisol_instance = make_andisol();
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $object_instance = $andisol_instance->get_object_for_key($key);
        if ($object_instance && ($object_instance instanceof CO_KeyValue_CO_Collection)) {
            echo('<h3 style="color:green">The object for the key \''.$key.'\', containing \''.$object_instance->get_value().'\' was successfully fetched!</h3>');
        } else {
            echo('<h3 style="color:red">The object for the key \''.$key.'\' was not found!</h3>');
        }
    }
}

function kvp_test_70() {
    $key = 'keymaster';
        
    $andisol_instance = make_andisol();
    
    if (isset($andisol_instance) && ($andisol_instance instanceof CO_Andisol)) {
        $object_instance = $andisol_instance->get_object_for_key($key);
        if ($object_instance && ($object_instance instanceof CO_KeyValue_CO_Collection)) {
            echo('<h3 style="color:green">The object for the key \''.$key.'\', containing \''.$object_instance->get_value().'\' was successfully fetched!</h3>');
        } else {
            echo('<h3 style="color:red">The object for the key \''.$key.'\' was not found!</h3>');
            if (isset($andisol_instance->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance->error->error_code.') '.$andisol_instance->error->error_name.' ('.$andisol_instance->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_71($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'keymaster';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
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
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:red">PROBLEM! This should be gone!</h3>');
        } else {
            echo('<h3 style="color:green">The value is gone, as it should be!</h3>');
        }
    }
}

function kvp_test_72($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'Honey Badger Don\'t Care';
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
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
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:red">PROBLEM! This should be gone!</h3>');
        } else {
            echo('<h3 style="color:green">The value is gone, as it should be!</h3>');
        }
    }
}

function kvp_test_73($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    kvp_test_72($in_login, $in_hashed_password, $in_password);
}

function kvp_test_74($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'The Great Shadow';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/TheGreatShadow.txt');
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The value was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance2->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The text value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
                echo('<div id="kvp-the-great-shadow" class="closed">');
                    echo('<h2 class="header"><a href="javascript:toggle_main_state(\'kvp-the-great-shadow\')">'.$key.'</a></h2>');
                    echo('<div class="container">');
                        echo('<pre>');
                        echo(htmlspecialchars($value));
                        echo('</pre>');
                    echo('</div>');
                echo('</div>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_75($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'Yo! Smitty!';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/Yosemite.jpg');
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The image value was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The image value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
                $image_base64_data = base64_encode ($fetched_value);
                echo('<img src="data:image/jpeg;base64,'.$image_base64_data.'" alt="Nice View" />');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_76($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'The Bricklayer';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/TheBricklayer.mp3');
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The audio value was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The audio value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
                $audio_base64_data = base64_encode ($fetched_value);
                echo('<audio controls>');
                echo('<source type="audio/mpeg" src="data:audio/mpeg;base64,'.$audio_base64_data.'" />');
                echo('</audio>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
            }
        }
    }
}

function kvp_test_77($in_login = NULL, $in_hashed_password = NULL, $in_password = NULL) {
    $andisol_instance1 = make_andisol($in_login, $in_hashed_password, $in_password);
    
    $key = 'Yankee Doodle Mouse';
    $value = file_get_contents(dirname(dirname(__FILE__)).'/config/TJ1.mp4');
        
    if (isset($andisol_instance1) && ($andisol_instance1 instanceof CO_Andisol)) {
        if ($andisol_instance1->set_value_for_key($key, $value)) {
            echo('<h3 style="color:green">The video value was successfully stored for the key \''.$key.'\'!</h3>');
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance1->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance1->error->error_code.') '.$andisol_instance1->error->error_name.' ('.$andisol_instance1->error->error_description.')</p>');
            }
        }
        
    }
    
    $andisol_instance2 = make_andisol();
    
    if (isset($andisol_instance2) && ($andisol_instance2 instanceof CO_Andisol)) {
        $fetched_value = $andisol_instance1->get_value_for_key($key);
        if ($fetched_value) {
            echo('<h3 style="color:green">The video value was successfully fetched!</h3>');
            if ($fetched_value == $value) {
                echo('<h3 style="color:green">The values match!</h3>');
            } else {
                echo('<h3 style="color:red">There was a problem! The values don\'t match!</h3>');
            }
        } else {
            echo('<h3 style="color:red">There was a problem!</h3>');
            if (isset($andisol_instance2->error)) {
                echo('<p style="margin-left:1em;color:red;font-weight:bold">Error: ('.$andisol_instance2->error->error_code.') '.$andisol_instance2->error->error_name.' ('.$andisol_instance2->error->error_description.')</p>');
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
            $st1 = microtime(true);
            $function_name = sprintf('kvp_test_%02d', $in_num);
            $function_name($in_login, $in_hashed_password, $in_password);
            $fetchTime = sprintf('%01.3f', microtime(true) - $st1);
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
            
                $start = microtime(true);
                
                kvp_small_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
        
        echo('<div id="kvp-large-tests" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\'kvp-large-tests\')">LARGE VALUE TESTS</a></h2>');
            echo('<div class="container">');
                echo('<p class="explain"></p>');
            
                $start = microtime(true);
                
                kvp_large_run_tests();
                
                echo('<h5>The entire set of tests took '. sprintf('%01.3f', microtime(true) - $start) . ' seconds to complete.</h5>');
                
            echo('</div>');
        echo('</div>');
    echo('</div>');
$buffer = ob_get_clean();
die($buffer);
?>
