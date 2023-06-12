<?php

require_once 'App.php';

class Restaurants extends App
{

    protected $table = 'restaurants';

    protected $id;
    protected $name;
    protected $address;
    protected $image;
    protected $phone;


    /***************************************************************************
     * Restaurants constructor
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
     * @return Restaurants|false
     */
    private static function executeFind($stmt)
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Restaurants($row['id']);
        else
            return false;
    }


    /***************************************************************************
     * Find restaurant by id
     *
     * @param int $id
     * @return Restaurants|boolean
     */
    public static function findById($id)
    {
        $restaurant = new Restaurants();
        $stmt = $restaurant->conn->prepare("SELECT id FROM $restaurant->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }


    /***************************************************************************
     * Convert restaurant object to array
     *
     * @return array
     */
    public function toArray()
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
     * Get all restaurants as array of objects
     *
     * @return array
     */
    public static function all()
    {
        $restaurant = new Restaurants();

        $sql = "SELECT * FROM $restaurant->table ";
        $stmt = $restaurant->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $arr = [];
        while($row = $result->fetch_assoc()) {
            $arr[] = new Restaurants($row['id']);
        }
        return $arr;
    }


    /***************************************************************************
     * Get all restaurants as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $restaurants = [];
        foreach(self::all() as $restaurant) {
            $restaurants[] = $restaurant->toArray();
        }
        return $restaurants;
    }


    /***************************************************************************
     * Check if restaurant id exists
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
     * Get restaurant id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /***************************************************************************
     * Get restaurant name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /***************************************************************************
     * Get restaurant phone
     *
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /***************************************************************************
     * Get restaurant address
     *
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }


    /***************************************************************************
     * Get restaurant image
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }


    /***************************************************************************
     * Set restaurant id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set restaurant name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /***************************************************************************
     * Set restaurant image
     *
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    /***************************************************************************
     * Set restaurant phone
     *
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


    /***************************************************************************
     * Set restaurant address
     *
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

}