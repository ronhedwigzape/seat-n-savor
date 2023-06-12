<?php

require_once 'App.php';

class Tables extends App
{
    protected $table = 'tables';

    protected $id;
    protected $number;
    protected $image;
    protected $capacity;
    protected $description;


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
                $this->number = $row['number'];
                $this->capacity = $row['capacity'];
                $this->image = $row['image'];
                $this->description = $row['description'];
            }
        }
    }


    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Tables|false
     */
    private static function executeFind($stmt)
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Tables($row['id']);
        else
            return false;
    }


    /***************************************************************************
     * Find table by id
     *
     * @param int $id
     * @return Tables|boolean
     */
    public static function findById($id)
    {
        $table = new Tables();
        $stmt = $table->conn->prepare("SELECT id FROM $table->table WHERE id = ?");
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
            'number'     => $this->number,
            'image'    => $this->image,
            'capacity'  => $this->capacity,
            'description'    => $this->description
        ];
    }


    /***************************************************************************
     * Get all tables as array of objects
     *
     * @return array
     */
    public static function all()
    {
        $table = new Tables();

        $sql = "SELECT * FROM $table->table ";
        $stmt = $table->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $arr = [];
        while($row = $result->fetch_assoc()) {
            $arr[] = new Tables($row['id']);
        }
        return $arr;
    }


    /***************************************************************************
     * Get all tables as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $tables = [];
        foreach(self::all() as $table) {
            $tables[] = $table->toArray();
        }
        return $tables;
    }


    /***************************************************************************
     * Check if table id exists
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
     * Get table id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /***************************************************************************
     * Get table capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }


    /***************************************************************************
     * Get table description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


    /***************************************************************************
     * Get table number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }


    /***************************************************************************
     * Get table image
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }


    /***************************************************************************
     * Set table id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set table capacity
     *
     * @param int $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }


    /***************************************************************************
     * Set table number
     *
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }


    /***************************************************************************
     * Set table description
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /***************************************************************************
     * Set table image
     *
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

}