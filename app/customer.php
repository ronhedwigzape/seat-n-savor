<?php
require_once '_init.php';
require_once '../vendor/autoload.php';

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

            $restaurant = Restaurants::findById($booking['restaurant_id']);
            $table = Tables::findById($booking['table_id']);
            $table_number = $table->getNumber();
            $reference_number = $customer->generateReferenceNumber();
            $code = $customer->generateQrCode($booking['restaurant_id']);
            $restaurant_name = $restaurant->getName();
            $booking_date = $booking['date'];
            $booking_time = $booking['time'];
            $party_size = $booking['party_size'];

            $customer->makeBooking($restaurant, $table, $reference_number, $code, $booking_date, $booking_time, $party_size);

//            $to = 'PHONE_NUMBER';
//            $message = "Reservation Confirmation - $restaurant_name
//
//                        Reservation Details:
//                        - Restaurant: $restaurant_name
//                        - Date: $booking_date
//                        - Time: $booking_time
//                        - Table Number: $table_number
//
//                        Remember to bring your QR code and present it to the restaurant manager upon arrival.
//
//                        Please arrive on time for a seamless experience. Notify us ASAP for any changes or cancellations.
//
//                        Contact us for assistance.
//
//                        Enjoy your dining experience at $restaurant_name!
//
//                        Thank you,
//                        Seat N' Savor";

//            define("VONAGE_API_KEY", 'VONAGE_API_KEY');
//            define("VONAGE_API_SECRET", 'VONAGE_API_SECRET');
//
//            $basic  = new \Vonage\Client\Credentials\Basic(VONAGE_API_KEY, VONAGE_API_SECRET);
//            $client = new \Vonage\Client($basic);
//
//            try {
//                $response = $client->sms()->send(
//                    new \Vonage\SMS\Message\SMS($to, 'VONAGE', $message)
//                );
//
//                $messages = $response->current();
//
//                if ($messages->getStatus() == 0) {
//                    echo "The message was sent successfully\n";
//                } else {
//                    echo "The message failed with status: " . $messages->getStatus() . "\n";
//                }
//            } catch (Exception $e) {
//                echo "Error: " . $e->getMessage() . "\n";
//            }

        }

        // get all customer bookings
        else if (isset($_GET['getBookings'])) {
            echo json_encode([
                'bookings' => $customer->getAllCustomerBookings()

            ]);
        }

        // get all customer notifications
        else if (isset($_GET['getCustomerNotifications'])) {
            require_once 'models/Restaurateur.php';
            echo json_encode([
                'notifications' => $customer->getCustomerNotifications(),
                'restaurateurs' => Restaurateur::rows()
            ]);
        }


        else
            denyAccess();
    }
}
