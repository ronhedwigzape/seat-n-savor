<?php

require_once 'User.php';

class Customer extends User
{

    /***************************************************************************
     * Customer constructor
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username = '', string $password = '')
    {
        parent::__construct($username, $password, 'customer');
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Customer|false
     */
    private static function executeFind($stmt): Customer|false
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Customer($row['username'], $row['password']);
        else
            return false;
    }


    /***************************************************************************
     * Find customer by id
     *
     * @param int $id
     * @return Customer|boolean
     */
    public static function findById(int $id): Customer|bool
    {
        $customer = new Customer();
        $stmt = $customer->conn->prepare("SELECT username, password FROM $customer->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Get all customers as array of objects
     *
     * @return Customer[]
     */
    public static function all(): array
    {
        $customer = new Customer();
        $sql = "SELECT username, password FROM $customer->table ORDER BY number";
        $stmt = $customer->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $customers = [];
        while($row = $result->fetch_assoc()) {
            $customers[] = new Customer($row['username'], $row['password']);
        }
        return $customers;
    }


    /***************************************************************************
     * Get all customers as array of arrays
     *
     * @return array
     */
    public static function rows(): array
    {
        $customers = [];
        foreach(self::all() as $customer) {
            $customers[] = $customer->toArray();
        }
        return $customers;
    }


    /***************************************************************************
     * Check if customer id exists
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        if(!$id)
            return false;

        return (self::findById($id) != false);
    }


    /***************************************************************************
     * Check if customer username exists
     *
     * @param string $username
     * @param int $id
     * @return bool
     */
    public static function usernameExists(string $username, int $id = 0): bool
    {
        $customer = new Customer();
        $stmt = $customer->conn->prepare("SELECT id FROM $customer->table WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result->num_rows > 0);
    }


    /***************************************************************************
     * Insert customer
     *
     * @return void
     */
    public function insert(): void
    {
        // check id
        if(self::exists($this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: customer [id = ' . $this->id . '] already exists.');

        // check username
        if(trim($this->username) == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: customer username is required.');
        else if(self::usernameExists($this->username))
            App::returnError('HTTP/1.1 409', 'Insert Error: customer [username = ' . $this->username . '] already exists.');

        // check password
        if($this->password == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: customer password is required.');

        // proceed with insert
        $stmt = $this->conn->prepare("INSERT INTO $this->table(name, username, password, avatar, email, phone, address) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $this->name, $this->username, $this->password, $this->avatar, $this->email, $this->phone, $this->address);
        $stmt->execute();
    }


    /***************************************************************************
     * Update customer
     *
     * @return void
     */
    public function update(): void
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Update Error: customer [id = ' . $this->id . '] does not exist.');

        // check username
        if(trim($this->username) == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: customer username is required.');
        else if(self::usernameExists($this->username, $this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: customer [username = ' . $this->username . '] already exists.');

        // check password
        if($this->password == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: customer password is required.');

        // proceed with update
        $stmt = $this->conn->prepare("UPDATE $this->table SET name = ?, username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $this->username, $this->password, $this->id);
        $stmt->execute();
    }


    /***************************************************************************
     * Delete customer
     *
     * @return void
     */
    public function delete(): void
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Delete Error: customer [id = ' . $this->id . '] does not exist.');

        // proceed with delete
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }


    /***************************************************************************
     * Make customer new booking
     *
     * @param Restaurant $restaurant
     * @param Table $table
     * @param string $code
     * @param string $date
     * @param string $time
     * @param int $party_size
     * @return void
     */
    public function makeBooking(Restaurant $restaurant, Table $table,  string $code, string $date, string $time, int $party_size): void
    {
        require_once 'Booking.php';
        $status = 'pending';

        $restaurant_id = $restaurant->getId();
        $table_id = $table->getId();

        $booking = new Booking();
        if (Booking::stored($this->id, $restaurant_id, $table_id))
            $booking = Booking::find($this->id, $restaurant_id, $table_id);

        $booking->setCustomerId($this->id);
        $booking->setRestaurantId($restaurant_id);
        $booking->setTableId($table_id);
        $booking->setCode($code);
        $booking->setDate($date);
        $booking->setTime($time);
        $booking->setPartySize($party_size);
        $booking->setStatus($status);

        if (Booking::stored($this->id, $restaurant_id, $table_id))
            $booking->update();
        else
            $booking->insert();
    }


    /***************************************************************************
     * Generates a random qr code value
     *
     * @return string
     */
    public function generateQrCode(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        $characterCount = strlen($characters);

        for ($i = 0; $i < 15; $i++) {
            $randomIndex = mt_rand(0, $characterCount - 1);
            $code .= $characters[$randomIndex];
        }

        return $code;
    }


    public function getAllCustomerBookings() {

    }


//    public function makeBookingCancellation() {
//
//    }


}
