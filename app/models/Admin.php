<?php

require_once 'User.php';

class Admin extends User
{
    /***************************************************************************
     * Admin constructor
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        parent::__construct($username, $password, 'admin');
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Admin|false
     */
    private static function executeFind($stmt)
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Admin($row['username'], $row['password']);
        else
            return false;
    }


    /***************************************************************************
     * Find admin by id
     *
     * @param int $id
     * @return Admin|boolean
     */
    public static function findById($id)
    {
        $admin = new Admin();
        $stmt = $admin->conn->prepare("SELECT username, password FROM $admin->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Convert admin object to array
     *
     * @param array $append
     * @return array
     */
    public function toArray($append = [])
    {
        return parent::toArray($append);
    }


    /***************************************************************************
     * Get all admins as array of objects
     *
     * @return Admin[]
     */
    public static function all()
    {
        $admin = new Admin();
        $sql = "SELECT username, password FROM $admin->table ORDER BY id";
        $stmt = $admin->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $admins = [];
        while($row = $result->fetch_assoc()) {
            $admins[] = new Admin($row['username'], $row['password']);
        }
        return $admins;
    }


    /***************************************************************************
     * Get all admins as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $admins = [];
        foreach(self::all() as $admin) {
            $admins[] = $admin->toArray();
        }
        return $admins;
    }

    /***************************************************************************
     * Get all restaurant bookings in array
     *
     * @return array
     */
    public function getAllBookings() {
        $bookings_table = 'bookings';

        $stmt = $this->conn->prepare("SELECT * FROM $bookings_table ");
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $booking = [
                    'booking_id' => $row['id'],
                    'restaurant_id' => $row['restaurant_id'],
                    'customer_id' => $row['customer_id'],
                    'table_id' => $row['table_id'],
                    'code' => $row['code'],
                    'date' => $row['date'],
                    'time' => $row['time'],
                    'party_size' => $row['party_size'],
                    'status' => $row['status'],
                    'cancellation_reason' => $row['cancellation_reason'],
                    'is_shown' => $row['is_shown']
                ];
                $bookings[] = $booking;
            }
        }
        return $bookings;
    }



}
