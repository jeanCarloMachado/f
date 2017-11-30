<?php

require_once __DIR__.'/../vendor/autoload.php';

class Database
{
    public function query($id)
    {
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
    return $database->query("Select * from User where id = %id " $userId);
}

$concreteGetProfile = \f\partial('getProfile')(new Database);

print_r($concreteGetProfile(666));
/* function logService($serviceName, callable $service, $params) { */
/*     echo "Service called ".$serviceName." with params ".serialize($params); */

/*     return call_user_func_array($service, [$params]); */
/* } */

/* $loggedGetProfile = \f\partial('logService')('getProfile' )($concreteGetProfile); */

/* print_r($loggedGetProfile(666)); */
