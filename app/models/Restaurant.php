<?php

require_once 'App.php';

class Restaurant extends App
{

    protected string $table = 'restaurants';

    protected int $id;
    protected mixed $name;
    protected mixed $address;
    protected mixed $image;
    protected mixed $phone;


    /***************************************************************************
     * Restaurant constructor
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
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->address = $row['address'];
                $this->image = $row['image'];
                $this->phone = $row['phone'];
            }
        }
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Restaurant|false
     */
    private static function executeFind($stmt): Restaurant|bool
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Restaurant($row['id']);
        else
            return false;
    }


    /***************************************************************************
     * Find restaurant by id
     *
     * @param int $id
     * @return Restaurant|boolean
     */
    public static function findById(int $id): Restaurant|bool
    {
        $restaurant = new Restaurant();
        $stmt = $restaurant->conn->prepare("SELECT id FROM $restaurant->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Convert restaurant object to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'address'  => $this->address,
            'image'    => $this->image,
            'phone'    => $this->phone
        ];
    }


    /***************************************************************************
     * Check if restaurant id exists
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
     * Get restaurant id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /***************************************************************************
     * Get restaurant name
     *
     * @return mixed
     */
    public function getName(): mixed
    {
        return $this->name;
    }


    /***************************************************************************
     * Get restaurant phone
     *
     * @return mixed
     */
    public function getPhone(): mixed
    {
        return $this->phone;
    }


    /***************************************************************************
     * Get restaurant address
     *
     * @return mixed
     */
    public function getAddress(): mixed
    {
        return $this->address;
    }


    /***************************************************************************
     * Get restaurant image
     *
     * @return mixed
     */
    public function getImage(): mixed
    {
        return $this->image;
    }


    /***************************************************************************
     * Set restaurant id
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set restaurant name
     *
     * @param mixed $name
     */
    public function setName(mixed $name): void
    {
        $this->name = $name;
    }


    /***************************************************************************
     * Set restaurant image
     *
     * @param mixed $image
     */
    public function setImage(mixed $image): void
    {
        $this->image = $image;
    }


    /***************************************************************************
     * Set restaurant phone
     *
     * @param mixed $phone
     */
    public function setPhone(mixed $phone): void
    {
        $this->phone = $phone;
    }


    /***************************************************************************
     * Set restaurant address
     *
     * @param mixed $address
     */
    public function setAddress(mixed $address): void
    {
        $this->address = $address;
    }

}