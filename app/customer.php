<?php
require_once '_init.php';

// get authenticated user
$authUser = getUser();

if(!$authUser)
    denyAccess();

else if($authUser['userType'] !== 'customer')
    denyAccess();

else {
    require_once 'models/Customer.php';
    $customer = new Customer($authUser['username'], $_SESSION['pass']);

    if(!$customer->authenticated())
        denyAccess();

    else {

        // fetch restaurants, tables, and restaurateurs
        if (isset($_GET['getRestaurants'])) {
            require_once 'models/Restaurants.php';
            require_once 'models/Tables.php';
            require_once 'models/Restaurateur.php';

            echo json_encode([
                'restaurants' => Restaurants::rows(),
                'tables' => Tables::rows(),
                'restaurateurs' => Restaurateur::rows()
            ]);
        }

        // make booking
        else if (isset($_POST['booking'])) {
            require_once 'models/Restaurants.php';
            require_once 'models/Booking.php';
            require_once 'models/Tables.php';

            $booking = $_POST['booking'];

            $customer->makeBooking(
                Restaurants::findById($booking['restaurant_id']),
                Tables::findById($booking['table_id']),
                $customer->generateQrCode($booking['restaurant_id']),
                $booking['date'],
                $booking['time'],
                $booking['party_size']
            );
        }

        // get all customer bookings
        else if (isset($_GET['getBookings'])) {
            echo json_encode([
                'bookings' => $customer->getAllCustomerBookings()

            ]);
        }

        // get all customer notifications
        else if (isset($_GET['getCustomerNotifications'])) {
            echo json_encode([
                'notifications' => $customer->getCustomerNotifications()
            ]);
        }


        else
            denyAccess();
    }
}
