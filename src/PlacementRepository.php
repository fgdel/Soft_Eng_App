<?php

namespace Phizzle;

use \Phizzle\Placement;
use \Mattsmithdev\PdoCrud\DatabaseManager;
use \Mattsmithdev\PdoCrud\DatatbaseUtility;

/**
 * Class PlacementRepository
 * @package Phizzle
 */
class PlacementRepository
{

    /**
     * @param \Phizzle\Placement $placement
     * @return int
     */
    public static function create(Placement $placement)
    {
        // get database connection
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();

        // use DatatbaseUtility class to transform the User object to a usable array
        $objectAsArrayForSqlInsert = \Mattsmithdev\PdoCrud\DatatbaseUtility::objectToArrayLessId($placement);

        // execute the INSERT query
        $statement = $connection->prepare('INSERT INTO placement (name, role, company, company_url, deadline, description) VALUES (:name, :role, :company, :company_url, :deadline, :description)');
        $statement->execute($objectAsArrayForSqlInsert);

        // check the result
        $queryWasSuccessful = ($statement->rowCount() > 0);

        // return the new id
        return ($queryWasSuccessful) ? $connection->lastInsertId() : -1;
    }

    /**
     * @param \Phizzle\Placement $placement
     * @param int $id
     *
     * @return int
     */
    public function update(\Phizzle\Placement $placement, $id)
    {
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();
        // remove the id from the User object
        $objectAsArrayForSqlInsert = \Mattsmithdev\PdoCrud\DatatbaseUtility::objectToArrayLessId($placement);
        // prepare the update fields
        $fields = array_keys($objectAsArrayForSqlInsert);
        // create the update field list
        $updateFieldList = \Mattsmithdev\PdoCrud\DatatbaseUtility::fieldListToUpdateString($fields);
        // create the UPDATE statement
        $sql = 'UPDATE placement SET ' . $updateFieldList  . ' WHERE id=:id';
        // prepare the SQL statement
        $statement = $connection->prepare($sql);
        // add 'id' to parameters array
        $objectAsArrayForSqlInsert['id'] = $id;

        $queryWasSuccessful = $statement->execute($objectAsArrayForSqlInsert);

        return $queryWasSuccessful;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the DELETE query
        $statement = $connection->prepare('DELETE FROM placement WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        // check the result
        $queryWasSuccessful = $statement->execute();

        // return the result
        return $queryWasSuccessful;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM placement');
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\Placement');
        $statement->execute();

        // get the result set rows
        $placements = $statement->fetchAll();

        return $placements;
    }

    /**
     * @param string $company
     * @return array
     */
    public function getAllByCompany($company)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM placement WHERE company=:company');
        $statement->bindParam(':company', $company, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\Placement');
        $statement->execute();

        // get the result set rows
        $companys = $statement->fetchAll();

        return $companys;
    }

    /**
     * @param int $id
     * @return \Phizzle\Placement|null
     */
    public function getOneById($id)
    {
        // get database connection
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // execute the SELECT query
        $statement = $connection->prepare('SELECT * FROM placement WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, '\\Phizzle\\Placement');
        $statement->execute();

        return ($placement = $statement->fetch()) ? $placement : null;
    }

}