<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:51
 */
require_once("Model.php");
/*
 * Represents a user, with all of its might (not right now)
 * Should contain everything that a user has, such as username, password, quizes done and quizes left
 * Everything that you would need to check and handle stuff for the user
 */

class UserModel extends Model {
    private $id;
    private $errors;
    private $username;
    private $password;
    private $quizes;
    private $email;

    public function __construct(){
        parent::__construct();
    }

    static public function getCurrentUser()
    {
        if (isset($_SESSION["userid"]))
            return UserModel::getUserById($_SESSION["userid"]);

        return new UserModel();
    }

    static public function getUserById($id)
    {
        $conn = self::getConnection();
        try {
            $sth = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $sth->execute(array($id));
            $user = $sth->fetchObject("UserModel");
            return $user;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function registerUser($username, $password, $email)
    {
        //Get the connection and register the user
        //Return this which will be the new user, or throw an exception if it failed
        $conn = $this->getConnection();
        try {
            $sth = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?,?,?)");
            $sth->execute(array($username, password_hash($password, PASSWORD_DEFAULT), $email));
        } catch (PDOException $e) {
            if ($e->getCode() === "23505") {
                throw new Exception("That username or email already exists, please try another one");
            }
        }
        try {
            $sth = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $sth->execute(array($username));
            $usermodel = $sth->fetchObject("UserModel");
        } catch (Exception $e) {
            throw $e;
        }
        return $usermodel;
        //throw new NotImplementedException("registerUser is not implemented yet and as such will not have done anything");
    }

    public function usernameExists($username)
    {
        $conn = $this->getConnection();

        $sth = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
        $sth->execute(array($username));

        if ($sth->fetch()) {
            return true;
        }
        return false;
    }
    /*
     * @todo: Implement this function properly
     */

    public function isLoggedIn(){
        return isset($_SESSION["loggedin"]);
    }
    /*
     * @todo: Implement this function properly
     */
    public function validateInput(array $data)
    {
        $username = $data["username"];
        $password = $data["password"];
        $repeatedpassword = $data["repeatedpassword"];
        $email = $data["email"];
        return true;
    }
    /*
     * @todo: Implement this function properly
     */
    public function validateLogin(array $data)
    {
        if ($this->userExists($data["username"], $data["password"])) {
            session_regenerate_id(true);
            $_SESSION["loggedin"] = true;
            $_SESSION["userid"] = $this->id;
            $_SESSION["username"] = $this->username;
            return true;
        } else {
            return false;
        }
    }

    public function userExists($username, $password)
    {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $sth->execute(array($username));
        $user = $sth->fetchObject("UserModel");
        $this->id = $user->getId();
        $this->username = $user->getUsername();
        $this->email = $user->email;

        if (password_verify($password, $user->getPassword())) {
            return true;
        }
        return false;
    }

    public function logout(){
        session_destroy();
        session_regenerate_id(true);
    }
    public function getErrors(){
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
}