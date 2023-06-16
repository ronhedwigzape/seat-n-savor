<?php
global $conn;
require_once '_init.php';

// get requests
if(isset($_GET['getUser'])) {
    echo json_encode([
        'user' => getUser()
    ]);

    exit;
}

// Create customer's account
else if (isset($_POST['createAccount'])) {
    require_once 'models/App.php';
    // todo: add a captcha at frontend if website is registered under a domain

    $username = $_POST['userName'];
    $password = $_POST['passWord'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $avatar = 'avatar.png';
    $customers_table = 'customers';

    if (!$username || !$password || !$name || !$email || !$phone || !$address)
        App::returnError('HTTP/1.1 404', 'Error: Account form is invalid.');

    $stmt = $conn->prepare("INSERT INTO $customers_table (username, password, avatar, name, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss",  $username, $password, $avatar, $name, $email, $phone, $address);
    $stmt->execute();
}

//
else if (isset($_POST['g-recaptcha-response'])) {
    $secret_key = "SECRET_KEY";
    $response = $_POST['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}";
    $verify = json_decode(file_get_contents($url));
    $solved = false;

    if ($verify->success) {
        // reCAPTCHA was correctly solved
        echo json_encode([
           'solved' => true
        ]);
    } else {
        // reCAPTCHA was not solved correctly
        echo json_encode([
            'solved' => false
        ]);
    }
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