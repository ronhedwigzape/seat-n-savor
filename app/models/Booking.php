<?php

require_once 'App.php';

class Booking extends App
{
    protected string $table = 'bookings';

    protected ?int $id = 0;
    protected int $customer_id;
    protected int $restaurant_id;
    protected int $table_id;
    protected mixed $code;
    protected mixed $date;
    protected mixed $time;
    protected int $party_size;
    protected mixed $status;
    protected mixed $cancellation_reason;
    protected bool $is_shown;


    /***************************************************************************
     * Booking constructor
     *
     * @param int $id
     */
    public function __construct(int $id = 0)
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
    private static function executeFind($stmt): Booking|bool
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
    public static function find(int $customer_id, int $restaurant_id, int $table_id): Booking|bool
    {
        $booking = new Booking();
        $stmt = $booking->conn->prepare("SELECT id FROM $booking->table WHERE customer_id = ? AND restaurant_id = ? AND table_id = ?");
        $stmt->bind_param("iii", $customer_id, $restaurant_id, $table_id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Find booking by id
     *
     * @param int $id
     * @return Booking|boolean
     */
    public static function findById(int $id): Booking|bool
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
    public function toArray(): array
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
     * Check if booking id exists
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
     * Check if booking for customer, restaurant, and table is already stored
     *
     * @param $customer_id
     * @param $restaurant_id
     * @param $table_id
     * @return bool
     */
    public static function stored($customer_id, $restaurant_id, $table_id): bool
    {
        if(!$customer_id || !$restaurant_id || !$table_id)
            return false;

        return (self::find($customer_id, $restaurant_id, $table_id) != false);
    }


    /***************************************************************************
     * Checks if customer_id, restaurant_id, and table_id is present
     *
     * @return void
     */
    public function checkId(): void
    {
        require_once 'Customer.php';
        if (!Customer::exists($this->customer_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: customer [id = ' . $this->customer_id . '] does not exist.');

        // check restaurant_id
        require_once 'Restaurant.php';
        if (!Restaurant::exists($this->restaurant_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: restaurant [id = ' . $this->restaurant_id . '] does not exist.');

        // check table_id
        require_once 'Table.php';
        if (!Table::exists($this->table_id))
            App::returnError('HTTP/1.1 404', 'Insert Error: table [id = ' . $this->table_id . '] does not exist.');
    }


    /***************************************************************************
     * Insert booking
     *
     * @return void
     */
    public function insert(): void
    {
        // check id
        if(self::exists($this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: booking [id = ' . $this->id . '] already exists.');

        // check id
        $this->checkId();

        // proceed with insert if not yet stored
        if(!self::stored($this->customer_id, $this->restaurant_id, $this->table_id)) {

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
    public function update(bool $toggle_shown = false): void
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
    public function delete(): void
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
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set customer_id
     *
     * @param int $customer_id
     */
    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }


    /***************************************************************************
     * Set restaurant_id
     *
     * @param int $restaurant_id
     */
    public function setRestaurantId(int $restaurant_id): void
    {
        $this->restaurant_id = $restaurant_id;
    }


    /***************************************************************************
     * Set table_id
     *
     * @param int $table_id
     */
    public function setTableId(int $table_id): void
    {
        $this->table_id = $table_id;
    }


    /***************************************************************************
     * Set cancellation_reason
     *
     * @param mixed $cancellation_reason
     */
    public function setCancellationReason(mixed $cancellation_reason): void
    {
        $this->cancellation_reason = $cancellation_reason;
    }


    /***************************************************************************
     * Set code
     *
     * @param mixed $code
     */
    public function setCode(mixed $code): void
    {
        $this->code = $code;
    }


    /***************************************************************************
     * Set date
     *
     * @param mixed $date
     */
    public function setDate(mixed $date): void
    {
        $this->date = $date;
    }


    /***************************************************************************
     * Set time
     *
     * @param mixed $time
     */
    public function setTime(mixed $time): void
    {
        $this->time = $time;
    }


    /***************************************************************************
     * Set party_size
     *
     * @param int $party_size
     */
    public function setPartySize(int $party_size): void
    {
        $this->party_size = $party_size;
    }


    /***************************************************************************
     * Set is_shown
     *
     * @param bool $is_shown
     */
    public function setIsShown(bool $is_shown): void
    {
        $this->is_shown = $is_shown;
    }


    /***************************************************************************
     * Set status
     *
     * @param mixed $status
     */
    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }


    /***************************************************************************
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /***************************************************************************
     * Get customer_id
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }


    /***************************************************************************
     * Get restaurant_id
     *
     * @return int
     */
    public function getRestaurantId(): int
    {
        return $this->restaurant_id;
    }


    /***************************************************************************
     * Get table_id
     *
     * @return int
     */
    public function getTableId(): int
    {
        return $this->table_id;
    }


    /***************************************************************************
     * Get code
     *
     * @return mixed
     */
    public function getCode(): mixed
    {
        return $this->code;
    }


    /***************************************************************************
     * Get date
     *
     * @return mixed
     */
    public function getDate(): mixed
    {
        return $this->date;
    }

    /***************************************************************************
     * Get time
     *
     * @return mixed
     */
    public function getTime(): mixed
    {
        return $this->time;
    }


    /***************************************************************************
     * Get party_size
     *
     * @return int
     */
    public function getPartySize(): int
    {
        return $this->party_size;
    }


    /***************************************************************************
     * Get cancellation_reason
     *
     * @return mixed
     */
    public function getCancellationReason(): mixed
    {
        return $this->cancellation_reason;
    }


    /***************************************************************************
     * Get status
     *
     * @return mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /***************************************************************************
     * Get is_shown
     *
     * @return bool
     */
    public function getIsShown(): bool
    {
        return $this->is_shown;
    }

    /***************************************************************************
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        require_once 'Customer.php';
        return Customer::findById($this->customer_id);
    }


    /***************************************************************************
     * Get restaurant
     *
     * @return Restaurant
     */
    public function getRestaurant(): Restaurant
    {
        require_once 'Restaurant.php';
        return new Restaurant($this->restaurant_id);
    }


    /***************************************************************************
     * Get table
     *
     * @return Table
     */
    public function getTable(): Table
    {
        require_once 'Table.php';
        return new Table($this->table_id);
    }
}