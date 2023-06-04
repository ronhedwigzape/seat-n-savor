<?php
require_once '_init.php';

// get requests
if(isset($_GET['getUser'])) {
    echo json_encode([
        'user' => getUser()
    ]);

    exit;
}

// user sign-in
else if(isset($_POST['username']) && isset($_POST['password'])) {
    require_once 'models/Admin.php';
    require_once 'models/Customer.php';
    require_once 'models/Restaurateur.php';

    // todo: validate input
    $username = trim(strtolower($_POST['username']));
    $password = $_POST['password'];

    $user = (new Admin($username, $password))->signIn();
    if(!$user) {
        $user = (new Customer($username, $password))->signIn();
        if(!$user)
            $user = (new Restaurateur($username, $password))->signIn();
    }

    if($user) {
        // successfully logged in
        echo json_encode([
            'user' => [...$user->toArray(),]
        ]);
    }
    else
        App::returnError('HTTP/1.1 401', 'Invalid Username or Password');

    exit;
}


// user sign out
else if(isset($_POST['signOut'])) {
    if($user_info = getUser()) {
        require_once 'models/User.php';
        $user = new User($user_info['username'], $_SESSION['pass'], $user_info['userType']);
        $user->signOut();
    }
    echo json_encode([
        'signedOut' => true
    ]);

    exit;
}