<?php
spl_autoload_register(function ($class) {
    $class_name = str_replace('\\', '/', $class);
    require_once '/Users/svyatoslav/Hosts/localhost/public_html/' . $class_name . '.php';
});


