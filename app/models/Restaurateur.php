<?php

require_once 'User.php';

class Restaurateur extends User
{

    protected string $table_notifications = 'notifications';

    /***************************************************************************
     * Restaurateur constructor
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username = '', string $password = '')
    {
        parent::__construct($username, $password, 'restaurateur');
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Restaurateur|false
     */
    private static function executeFind($stmt): Restaurateur|false
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Restaurateur($row['username'], $row['password']);
        else
            return false;
    }


    /***************************************************************************
     * Find restaurateur by id
     *
     * @param int $id
     * @return Restaurateur|boolean
     */
    public static function findById(int $id): Restaurateur|bool
    {
        $restaurateur = new Restaurateur();
        $stmt = $restaurateur->conn->prepare("SELECT username, password FROM $restaurateur->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Convert restaurateur object to array
     *
     * @param array $append
     * @return array
     */
    public function toArray(array $append = []): array
    {
        return parent::toArray($append);
    }


    /***************************************************************************
     * Get all restaurateurs as array of objects
     *
     * @return Restaurateur[]
     */
    public static function all(): array
    {
        $restaurateur = new Restaurateur();
        $sql = "SELECT username, password FROM $restaurateur->table ORDER BY number";
        $stmt = $restaurateur->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $restaurateurs = [];
        while($row = $result->fetch_assoc()) {
            $restaurateurs[] = new Restaurateur($row['username'], $row['password']);
        }
        return $restaurateurs;
    }


    /***************************************************************************
     * Get all restaurateurs as array of arrays
     *
     * @return array
     */
    public static function rows(): array
    {
        $restaurateurs = [];
        foreach(self::all() as $restaurateur) {
            $restaurateurs[] = $restaurateur->toArray();
        }
        return $restaurateurs;
    }


    /***************************************************************************
     * Check if restaurateur id exists
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
     * Check if restaurateur username exists
     *
     * @param string $username
     * @param int $id
     * @return bool
     */
    public static function usernameExists(string $username, int $id = 0): bool
    {
        $restaurateur = new Restaurateur();
        $stmt = $restaurateur->conn->prepare("SELECT id FROM $restaurateur->table WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result->num_rows > 0);
    }


    /***************************************************************************
     * Insert restaurateur
     *
     * @return void
     */
    public function insert(): void
    {
        // check id
        if(self::exists($this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: restaurateur [id = ' . $this->id . '] already exists.');

        // check username
        if(trim($this->username) == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: restaurateur username is required.');
        else if(self::usernameExists($this->username))
            App::returnError('HTTP/1.1 409', 'Insert Error: restaurateur [username = ' . $this->username . '] already exists.');

        // check password
        if($this->password == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: restaurateur password is required.');

        // proceed with insert
        $stmt = $this->conn->prepare("INSERT INTO $this->table(name, username, password) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $this->name,  $this->username, $this->password);
        $stmt->execute();
        $this->id = $this->conn->insert_id;
    }


    /***************************************************************************
     * Update restaurateur
     *
     * @return void
     */
    public function update(): void
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Update Error: restaurateur [id = ' . $this->id . '] does not exist.');

        // check username
        if(trim($this->username) == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: restaurateur username is required.');
        else if(self::usernameExists($this->username, $this->id))
            App::returnError('HTTP/1.1 409', 'Insert Error: restaurateur [username = ' . $this->username . '] already exists.');

        // check password
        if($this->password == '')
            App::returnError('HTTP/1.1 422', 'Insert Error: restaurateur password is required.');

        // proceed with update
        $stmt = $this->conn->prepare("UPDATE $this->table SET name = ?, username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $this->name, $this->username, $this->password, $this->id);
        $stmt->execute();
    }


    /***************************************************************************
     * Delete restaurateur
     *
     * @return void
     */
    public function delete(): void
    {
        // check id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Delete Error: restaurateur [id = ' . $this->id . '] does not exist.');

        // proceed with delete
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }

    /***************************************************************************
     * Notify the customer that the booking has been confirmed
     *
     * @param Customer $recipient
     * @param string $message
     * @return void
     */
    public function notifyCustomer(Customer $recipient, string $message): void
    {

        // check sender id
        if(!self::exists($this->id))
            App::returnError('HTTP/1.1 404', 'Error: restaurateur [id = ' . $this->id . '] does not exist.');

        // check recipient id
        $recipient_id = $recipient->getId();
        if (!$recipient_id)
            App::returnError('HTTP/1.1 404', 'Error: recipient [id = ' . $recipient . '] does not exist.');

        // check message
        if (!$message)
            App::returnError('HTTP/1.1 404', 'Error: message does not exist.');

        $stmt = $this->conn->prepare("INSERT INTO $this->table_notifications(sender_id, recipient_id, message) VALUES(?, ?, ?)");
        $stmt->bind_param("iis", $this->id,  $recipient, $message);
        $stmt->execute();
    }

    public function updateBookingStatus($booking, $customer, $status) {
        require_once 'Booking.php';



    }

    public function setCustomerVisibility($is_shown) {

    }

}
