<?php
/**
 * UserRepository.php
 */
namespace Phizzle;

use \Phizzle\User;
use \Mattsmithdev\PdoCrud\DatabaseManager;
use \Mattsmithdev\PdoCrud\DatatbaseUtility;

/**
 * Class UserRepository
 * @package Phizzle
 */
class UserRepository
{

    /**
     * Adds a new User to the database and returns the 'id'
     * @param \Phizzle\User $user
     * @return int
     */
    public static function create(User $user)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // use DatatbaseUtility class to transform the User object to a usable array
        $objectAsArrayForSqlInsert = DatatbaseUtility::objectToArrayLessId($user);

        // execute the INSERT query
        $statement = $connection->prepare('INSERT INTO user (firstname, lastname, email, username, password, role) VALUES (:firstname, :lastname, :email, :username, :password, :role)');
        $statement->execute($objectAsArrayForSqlInsert);

        // check the result
        $queryWasSuccessful = ($statement->rowCount() > 0);

        // return the new id
        return ($queryWasSuccessful) ? $connection->lastInsertId() : -1;
    }

    /**
     * Updates the database record of a User
     * @param \Phizzle\User $user
     * @param int $id
     *
     * @return int
     */
    public function update(User $user, $id)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();
        // remove the id from the User object
        $objectAsArrayForSqlInsert = DatatbaseUtility::objectToArrayLessId($user);
        // prepare the update fields
        $fields = array_keys($objectAsArrayForSqlInsert);
        // create the update field list
        $updateFieldList = DatatbaseUtility::fieldListToUpdateString($fields);
        // create the UPDATE statement
        $sql = 'UPDATE user SET ' . $updateFieldList  . ' WHERE id=:id';
        // prepare the SQL statement
        $statement = $connection->prepare($sql);
        // add 'id' to parameters array
        $objectAsArrayForSqlInsert['id'] = $id;

        $queryWasSuccessful = $statement->execute($objectAsArrayForSqlInsert);

        return $queryWasSuccessful;
    }


    /**
     * Deletes a User record from the database
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the DELETE query
        $statement = $connection->prepare('DELETE FROM user WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        // check the result
        $queryWasSuccessful = $statement->execute();

        // return the result
        return $queryWasSuccessful;
    }

    /**
     * Gets all User records from the database
     * @return array
     */
    public function getAll()
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM user');
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\User');
        $statement->execute();

        // get the result set rows
        $users = $statement->fetchAll();

        return $users;
    }

    /**
     * Gets a single User record from the database using the provided 'id'
     * @param $id
     * @return \Phizzle\User|null
     */
    public function getOneById($id)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM user WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\User');
        $statement->execute();

        return ($user = $statement->fetch()) ? $user : null;
    }

    /**
     * Gets a single User record from the database using the provided 'username'
     * @param $username
     * @return \Phizzle\User|null
     */
    public function getOneByUsername($username)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM user WHERE username=:username');
        $statement->bindParam(':username', $username, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\User');
        $statement->execute();

        return ($user = $statement->fetch()) ? $user : null;
    }

    /**
     * Gets all records from the database with 'username' or 'email' that match the provided text
     * @param $searchText
     * @return array
     */
    public function searchByUsernameOrEmail($searchText)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // wrap wildcard '%' around the search text for the SQL query
        $searchText = '%' . $searchText . '%';

        $statement = $connection->prepare('SELECT * FROM user WHERE (username LIKE :searchText) or (email LIKE :searchText)');
        $statement->bindParam(':searchText', $searchText, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\User');
        $statement->execute();

        $users = $statement->fetchAll();

        return $users;
    }

    /**
     * Checks the database for the provided 'username' and 'password' combination
     * @param string $username
     * @param string $password
     *
     * @return int|bool
     */
    public function canFindMatchingUsernameAndPassword($username, $password)
    {
        $user = $this->getOneByUsername($username);

        // if no record has this username, return FALSE
        if (null == $user) {
            return false;
        }

        // hashed correct password
        $hashedStoredPassword = $user->getPassword();

        // return whether or not hash of input password matched stored hash
        return password_verify($password, $hashedStoredPassword);
    }

    /**
     * Checks the database for the provided 'username' and 'role' combination
     * @param string $username
     * @param int $role
     * @return bool
     */
    public function canFindUsernameWithAdminRole($username, $role)
    {

        // The Admin role is defined as 2
        $admin_role = 2;

        // If the provided role is not that of Admin then return FALSE
        if ($role <> $admin_role) {
            return false;
        }

        $user = $this->getOneByUsername($username);

        // If no record has this username, return FALSE
        if (null == $user) {
            return false;
        }

        // Return whether or not the provided User has the Role of Admin
        return ( ($admin_role == $user->getRole()) ? true : false );
    }

}