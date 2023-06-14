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

        // fetch restaurant bookings
        if (isset($_GET['getRestaurantBookings'])) {
            echo json_encode([
                'restaurant_bookings' => $restaurateur->getAllRestaurantBookings()
            ]);
        }

        // fetch customer, table, and restaurant
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

        // notify customer for confirmation of booking
        else if (isset($_POST['confirmation'])) {
            require_once 'models/Customer.php';

            $customer = Customer::findById($_POST['customer_id']);
            $table_id = $_POST['table_id'];
            $code = $_POST['code'];
            $message = $_POST['message'];

            $restaurateur->notifyCustomer($customer, $message);
            $restaurateur->confirmBookingStatus($customer->getId(), $table_id, $code);
        }

        // notify customer for cancellation of booking
        else if (isset($_POST['cancellation'])) {
            require_once 'models/Customer.php';

            $customer = Customer::findById($_POST['customer_id']);
            $table_id = $_POST['table_id'];
            $code = $_POST['code'];
            $message = $_POST['message'];

            $restaurateur->notifyCustomer($customer, $message);
            $restaurateur->cancelBookingStatus($customer->getId(), $table_id, $code);
        }

        // mark booking status as pending
        else if (isset($_POST['pending'])) {
            require_once 'models/Customer.php';

            $customer = Customer::findById($_POST['customer_id']);
            $table_id = $_POST['table_id'];
            $code = $_POST['code'];

            $restaurateur->updateBookingStatusToPending($customer->getId(), $table_id, $code);
        }

        // scans qr and sets customer visibility
        else if (isset($_POST['decodedQr'])) {
            $restaurateur->setCustomerVisibility($_POST['decodedQr']);
        }

        else
            denyAccess();
    }
}
