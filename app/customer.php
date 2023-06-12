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

        // fetch restaurants
        if (isset($_GET['getRestaurants'])) {
            require_once 'models/Restaurant.php';

            echo json_encode([
                'restaurants' => Restaurant::rows(),
            ]);
        }

        // fetch tables
        else if (isset($_GET['getTables'])) {
            require_once 'models/Table.php';

            echo json_encode([
                'tables' => Table::rows()
            ]);
        }

        // make booking
        else if (isset($_POST['booking'])) {
            require_once 'models/Restaurant.php';
            require_once 'models/Booking.php';
            require_once 'models/Table.php';

            $booking = $_POST['booking'];

            $customer->makeBooking(
                Restaurant::findById($booking['restaurant_id']),
                Table::findById($booking['table_id']),
                $customer->generateQrCode(),
                $booking['date'],
                $booking['time'],
                $booking['party_size']
            );

        }






        else
            denyAccess();
    }
}
