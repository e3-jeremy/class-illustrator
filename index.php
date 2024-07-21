<?php

require_once __DIR__ .  '\vendor\autoload.php';

use J2\ClassDebugger\ClassReflection;

function dd($data) {
//    try {
        ClassReflection::print($data);
//    } catch (\Exception $error) {
//        ClassReflection::print( $error->getMessage());
//    }
    exit;
}
function pr($data) {
    echo '<pre>';
    print_r($data);
    echo '<pre>'; exit;
}

dd(ClassReflection::class);

//$reflection = new ClassReflection;
//$reflection->print($reflection);

//ClassReflection::print('test');
//ClassReflection::print([
//    'key'  => 'value',
//    'key2' => 'value2',
//    'key3' => 'value3',
//]);
//ClassReflection::print(ClassReflection::class);
//ClassReflection::print( new ClassReflection);
//ClassReflection::print( new ClassReflection, '', 'method only');
//ClassReflection::getMethods( new ClassReflection);