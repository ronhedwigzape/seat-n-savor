<?php
require_once '_init.php';

// get authenticated user
$authUser = getUser();

if(!$authUser)
    denyAccess();

else if($authUser['userType'] !== 'restaurateur')
    denyAccess();

else {
    require_once 'models/Restaurateur.php';
    $restaurateur = new Restaurateur($authUser['username'], $_SESSION['pass']);

    if(!$restaurateur->authenticated())
        denyAccess();

//    else {
//
//        else
//            denyAccess();
//    }
}
