<?php

require_once 'App.php';

class Booking extends App
{
    protected $table = 'bookings';

    protected $id;
    protected $customer_id;
    protected $restaurant_id;
    protected $table_id;
    protected $code;
    protected $date;
    protected $time;
    protected $party_size;
    protected $status;
    protected $cancellation_reason;
    protected $is_shown;


    /***************************************************************************
     * Booking constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {
        parent::__construct();

        // get other info
        if($id > 0) {
            $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->id                   = $row['id'];
                $this->customer_id          = $row['customer_id'];
                $this->restaurant_id        = $row['restaurant_id'];
                $this->table_id             = $row['table_id'];
                $this->code                 = $row['code'];
                $this->date                 = $row['date'];
                $this->time                 = $row['time'];
                $this->party_size           = $row['party_size'];
                $this->status               = $row['status'];
                $this->cancellation_reason  = $row['cancellation_reason'];
                $this->is_shown             = ($row['is_shown'] == 1);
            }
        }
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Booking|boolean
     */
    private static function executeFind($stmt)
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Booking($row['id']);
        else
            return false;
    }


    /***************************************************************************
     * Find booking by customer_id, restaurant_id, and table_id
     *
     * @param int $customer_id
     * @param int $restaurant_id
     * @param int $table_id
     * @return Booking|boolean
     */
    public static function find($customer_id, $restaurant_id, $table_id, $code)
    {
        $booking = new Booking();
        $stmt = $booking->conn->prepare("SELECT id FROM $booking->table WHERE customer_id = ? AND restaurant_id = ? AND table_id = ? AND code = ?");
        $stmt->bind_param("iiis", $customer_id, $restaurant_id, $table_id, $code);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Find booking by id
     *
     * @param int $id
     * @return Booking|boolean
     */
    public static function findById($id)
    {
        $rating = new Booking();
        $stmt = $rating->conn->prepare("SELECT id FROM $rating->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Convert booking object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'                    => $this->id,
            'customer_id'           => $this->customer_id,
            'restaurant_id'         => $this->restaurant_id,
            'table_id'              => $this->table_id,
            'code'                  => $this->code,
            'date'                  => $this->date,
            'time'                  => $this->time,
            'party_size'            => $this->party_size,
            'status'                => $this->status,
            'cancellation_reason'   => $this->cancellation_reason,
            'is_shown'              => $this->is_shown
        ];
    }


    /***************************************************************************
     * Get all bookings as array of objects
     *
     * @return array
     */
    public static function all()
    {
        $booking = new Booking();

        $sql = "SELECT * FROM $booking->table ";
        $stmt = $booking->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $arr = [];
        while($row = $result->fetch_assoc()) {
            $arr[] = new Booking($row['id']);
        }
        return $arr;
    }


    /***************************************************************************
     * Get all bookings as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $bookings = [];
        foreach(self::all() as $booking) {
            $bookings[] = $booking->toArray();
        }
        return $bookings;
    }


    /***************************************************************************
     * Check if booking id exists
     *
     * @param int $id
     * @return bool
     */
    public static function exists($id)
    {
        if(!$id)
            return false;

        return (self::findById($id) != false);
    }

    /***************************************************************************
     * Check if booking for customer, restaurant, and table is already stored
     *
     * @param $customer_id
     * @param $restaurant_id
     * @param $table_id
     * @return bool
     */
    public static function stored($customer_id, $restaurant_id, $table_id, $code)
    {
        if(!$customer_id || !$restaurant_id || !$table_id || !$code)
            return false;

        return (self::find($customer_id, $restaurant_id, $table_id, $code) != false);
    }


    /***************************************************************************
     * Checks if customer_id, restaurant_id, and table_id is present
     *
     * @return void
     */
    public function checkId()
    {
        require_once 'Customer.php';
        if (!Customer::exists($this->customer_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: customer [id = ' . $this->customer_id . '] does not exist.');

        // check restaurant_id
        require_once 'Restaurants.php';
        if (!Restaurants::exists($this->restaurant_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: restaurant [id = ' . $this->restaurant_id . '] does not exist.');

        // check table_id
        require_once 'Tables.php';
        if (!Tables::exists($this->table_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: table [id = ' . $this->table_id . '] does not exist.');
    }


    /***************************************************************************
     * Insert booking
     *
     * @return void
     */
    public function insert()
    {
        // check id
        if(self::exists($this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: booking [id = ' . $this->id . '] already exists.');

        // check id
        $this->checkId();

        // proceed with insert if not yet stored
        if(!self::stored($this->customer_id, $this->restaurant_id, $this->table_id, $this->code)) {

            // proceed with insert
            $stmt = $this->conn->prepare("INSERT INTO $this->table(customer_id, restaurant_id, table_id, code, date, time, party_size, status, cancellation_reason) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisssiss", $this->customer_id, $this->restaurant_id, $this->table_id, $this->code,$this->date, $this->time, $this->party_size, $this->status, $this->cancellation_reason);
            $stmt->execute();
            $this->id = $this->conn->insert_id;
        }
    }


    /***************************************************************************
     * Update booking
     *
     * @param bool $toggle_shown
     * @return void
     */
    public function update($toggle_shown = false)
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Update Error: booking [id = ' . $this->id . '] does not exist.');

        // check is_locked
        if(!$toggle_shown) {
            $stored_rating = self::findById($this->id);
            if($stored_rating->is_shown)
                App::returnError('HTTP/1.1 409', 'Update Error: booking [id = ' . $this->id . '] is already locked.');
        }

        // check id
        $this->checkId();

        // proceed with update
        $stmt = $this->conn->prepare("UPDATE $this->table SET customer_id = ?,  restaurant_id = ?, table_id = ?, is_shown = ?, code = ?, date = ?, time = ?, party_size = ?, status = ? WHERE id = ?");
        $is_shown = $this->is_shown ? 1 : 0;
        $stmt->bind_param("iiiisssisi", $this->customer_id, $this->restaurant_id, $this->table_id, $is_shown, $this->code, $this->date, $this->time, $this->party_size, $this->status, $this->id);
        $stmt->execute();
    }


    /***************************************************************************
     * Delete booking
     *
     * @return void
     */
    public function delete()
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 500', 'Delete Error: booking [id = ' . $this->id . '] does not exist.');

        // proceed with delete
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }


    /***************************************************************************
     * Set booking id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set customer_id
     *
     * @param int $customer_id
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }


    /***************************************************************************
     * Set restaurant_id
     *
     * @param int $restaurant_id
     */
    public function setRestaurantId($restaurant_id)
    {
        $this->restaurant_id = $restaurant_id;
    }


    /***************************************************************************
     * Set table_id
     *
     * @param int $table_id
     */
    public function setTableId($table_id)
    {
        $this->table_id = $table_id;
    }


    /***************************************************************************
     * Set cancellation_reason
     *
     * @param mixed $cancellation_reason
     */
    public function setCancellationReason($cancellation_reason)
    {
        $this->cancellation_reason = $cancellation_reason;
    }


    /***************************************************************************
     * Set code
     *
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }


    /***************************************************************************
     * Set date
     *
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }


    /***************************************************************************
     * Set time
     *
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


    /***************************************************************************
     * Set party_size
     *
     * @param int $party_size
     */
    public function setPartySize($party_size)
    {
        $this->party_size = $party_size;
    }


    /***************************************************************************
     * Set is_shown
     *
     * @param bool $is_shown
     */
    public function setIsShown($is_shown)
    {
        $this->is_shown = $is_shown;
    }


    /***************************************************************************
     * Set status
     *
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /***************************************************************************
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /***************************************************************************
     * Get customer_id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }


    /***************************************************************************
     * Get restaurant_id
     *
     * @return int
     */
    public function getRestaurantId()
    {
        return $this->restaurant_id;
    }


    /***************************************************************************
     * Get table_id
     *
     * @return int
     */
    public function getTableId()
    {
        return $this->table_id;
    }


    /***************************************************************************
     * Get code
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }


    /***************************************************************************
     * Get date
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /***************************************************************************
     * Get time
     *
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }


    /***************************************************************************
     * Get party_size
     *
     * @return int
     */
    public function getPartySize()
    {
        return $this->party_size;
    }


    /***************************************************************************
     * Get cancellation_reason
     *
     * @return mixed
     */
    public function getCancellationReason()
    {
        return $this->cancellation_reason;
    }


    /***************************************************************************
     * Get status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /***************************************************************************
     * Get is_shown
     *
     * @return bool
     */
    public function getIsShown()
    {
        return $this->is_shown;
    }

    /***************************************************************************
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        require_once 'Customer.php';
        return Customer::findById($this->customer_id);
    }


    /***************************************************************************
     * Get restaurant
     *
     * @return Restaurants
     */
    public function getRestaurant()
    {
        require_once 'Restaurants.php';
        return new Restaurants($this->restaurant_id);
    }


    /***************************************************************************
     * Get table
     *
     * @return Tables
     */
    public function getTable()
    {
        require_once 'Tables.php';
        return new Tables($this->table_id);
    }
}