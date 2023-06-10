<?php

require_once 'App.php';

class Table extends App
{
    protected string $table = 'tables';

    protected int $id;
    protected int $number;
    protected mixed $image;
    protected int $capacity;
    protected mixed $description;


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
     * @return Table|false
     */
    private static function executeFind($stmt): Table|bool
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Table($row['id']);
        else
            return false;
    }


    /***************************************************************************
     * Find table by id
     *
     * @param int $id
     * @return Table|boolean
     */
    public static function findById(int $id): Table|bool
    {
        $table = new Table();
        $stmt = $table->conn->prepare("SELECT id FROM $table->table WHERE id = ?");
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
    public static function all(): array
    {
        $table = new Table();

        $sql = "SELECT * FROM $table->table ";
        $stmt = $table->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $arr = [];
        while($row = $result->fetch_assoc()) {
            $arr[] = new Table($row['id']);
        }
        return $arr;
    }


    /***************************************************************************
     * Get all tables as array of arrays
     *
     * @return array
     */
    public static function rows(): array
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
    public static function exists(int $id): bool
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
    public function getId(): int
    {
        return $this->id;
    }


    /***************************************************************************
     * Get table capacity
     *
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }


    /***************************************************************************
     * Get table description
     *
     * @return mixed
     */
    public function getDescription(): mixed
    {
        return $this->description;
    }


    /***************************************************************************
     * Get table number
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }


    /***************************************************************************
     * Get table image
     *
     * @return mixed
     */
    public function getImage(): mixed
    {
        return $this->image;
    }


    /***************************************************************************
     * Set table id
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /***************************************************************************
     * Set table capacity
     *
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }


    /***************************************************************************
     * Set table number
     *
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }


    /***************************************************************************
     * Set table description
     *
     * @param mixed $description
     */
    public function setDescription(mixed $description): void
    {
        $this->description = $description;
    }


    /***************************************************************************
     * Set table image
     *
     * @param mixed $image
     */
    public function setImage(mixed $image): void
    {
        $this->image = $image;
    }

}