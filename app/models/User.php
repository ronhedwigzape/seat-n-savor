<?php

require_once 'App.php';

class User extends App
{
    // table
    protected string $table;

    // properties
    protected int|null $id = null;
    protected mixed $username;
    protected mixed $password;
    protected mixed $name;
    protected mixed $email;
    protected mixed $phone;
    protected string $userType;

    /***************************************************************************
     * User constructor
     *
     * @param $username
     * @param $password
     * @param $userType
     */
    public function __construct($username, $password, $userType)
    {
        parent::__construct();
        $this->username = $username;
        $this->password = $password;
        $this->table = $userType . 's';
        $this->userType = $userType;

        // get other info
        if($username != '' && $password != '') {
            $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $this->username, $this->password);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->phone = $row['phone'];
            }
        }
    }


    /***************************************************************************
     * Convert user object to array
     *
     * @param array $append
     * @return array
     */
    public function toArray(array $append = []): array
    {
        $arr = [
            'id'       => $this->id,
            'name'     => $this->name,
            'username' => $this->username,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'userType' => $this->userType,
        ];

        // append
        foreach($append as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }


    /***************************************************************************
     * Get currently signed-in user
     *
     * @return array|null
     */
    public static function getUser(): ?array
    {
        $user_info = null;
        if(isset($_SESSION['user']) && isset($_SESSION['pass'])) {
            $authenticated = (new User(
                $_SESSION['user']['username'],
                $_SESSION['pass'],
                $_SESSION['user']['userType']
            ))->signIn();

            if($authenticated)
                $user_info = [...$authenticated->toArray()];
            else
                session_destroy();
        }
        return $user_info;
    }


    /***************************************************************************
     * Authenticated or not
     *
     * @return bool
     */
    public function authenticated(): bool
    {
        return (bool)$this->id;
    }


    /***************************************************************************
     * Sign in
     *
     * @return $this|false
     */
    public function signIn(): false|static
    {
        if($this->authenticated()) {
            $_SESSION['user'] = $this->toArray();
            $_SESSION['pass'] = $this->password;
            return $this;
        }
        return false;
    }


    /***************************************************************************
     * Sign out
     *
     * @return void
     */
    public function signOut(): void
    {
        if(isset($_SESSION['user']))
            session_destroy();
    }

    /***************************************************************************
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /***************************************************************************
     * Set username
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }


    /***************************************************************************
     * Set password
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    /***************************************************************************
     * Get id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /***************************************************************************
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /***************************************************************************
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /***************************************************************************
     * Get password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}
