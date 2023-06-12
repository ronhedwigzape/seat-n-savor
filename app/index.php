<?php
require_once '_init.php';

// get requests
if(isset($_GET['getUser'])) {
    echo json_encode([
        'user' => getUser()
    ]);

    exit;
}

// Create customer's account
else if (isset($_POST['name']) && isset($_POST['phone'])) {
    require_once 'Customer.php';

    $name = $_POST['name'];
    $username = $_POST['userName'];
    $password = $_POST['passWord'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $avatar = 'avatar.png';

    $customer = new Customer();

    $customer->setUsername($username);
    $customer->setPassword($password);
    $customer->setAvatar($avatar);
    $customer->setName($name);
    $customer->setEmail($email);
    $customer->setPhone($phone);
    $customer->setAddress($address);
    $customer->insert();

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