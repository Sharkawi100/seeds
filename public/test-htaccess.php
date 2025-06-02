<?php
// File: test-htaccess.php
echo "PHP is working<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite is " . (in_array('mod_rewrite', $modules) ? 'enabled' : 'disabled');
} else {
    echo "Cannot determine if mod_rewrite is enabled";
}
?>