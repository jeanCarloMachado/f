<?php

require_once __DIR__.'/../vendor/autoload.php';

class Database
{
    public function query($query, $id)
    {
        echo PHP_EOL."DATABASE_CALL".PHP_EOL;
        $data = [['id'  => 666, 'name' => 'Gandalf' ], ['id'  => 777, 'name' => 'Saruman' ]];
        return array_filter(
            $data,
            function($candidate) use ($id) {
                return $candidate['id'] == $id;
            }
        );
    }
}

function getProfile($database, $userId) {
    return $database->query("Select * from User where id = %id ", $userId);
}

$getProfileConcrete = \f\partial('getProfile')(new Database);

//=== memoize

$memoize = function($func) {
    static $results = [];
    return function ($a) use ($func, &$results) {
        $key = serialize($a);
        if (empty($results[$key]))  {
            $results[$key] = call_user_func($func, $a);
            return $results[$key];
        }

        return $results[$key];
    };
};

$memoizedProfile = $memoize($getProfileConcrete);

//=== log
$logger = function($str) {
    echo $str;
};

function logService($logger, $serviceName, callable $service, $arg) {
    $logger("Service called ".$serviceName." with params ".serialize($arg));
    return call_user_func($service, $arg);
};

$loggedGetProfile = \f\partial('logService')($logger)('getProfile')($memoizedProfile); 
//===


print_r($loggedGetProfile(666));
print_r($loggedGetProfile(666));



