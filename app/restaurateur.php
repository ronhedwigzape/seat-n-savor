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

    else {

        if (isset($_GET['getRestaurantBookings'])) {
            require_once 'models/Restaurateur.php';

            echo json_encode([
                'restaurant_bookings' => $restaurateur->getAllRestaurantBookings()
            ]);
        }

        else if (isset($_GET['getCustomerTablesAndRestaurant'])) {
            require_once 'models/Customer.php';
            require_once 'models/Tables.php';
            require_once 'models/Restaurants.php';

            $restaurant = Restaurants::findById($restaurateur->getRestaurantId());

            echo json_encode([
                'customers' => Customer::rows(),
                'tables' => Tables::rows(),
                'restaurant' => $restaurant->toArray()
            ]);
        }

        else
            denyAccess();
    }
}
