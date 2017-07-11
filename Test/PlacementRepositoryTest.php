<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 15/08/16
 * Time: 08:26
 */

namespace Phizzle\Test;


class PlacementRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Generate a fake Placement record with option to provide a title
     * @param string $title
     * @return int
     */
    public function setupFakePlacement($name = '')
    {
        // use the Faker\Factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();
        // set name
        $name = (!empty($name)) ? $name : $faker->word;

        // instantiate the Placement object
        $obj = new \Phizzle\Placement;
        $obj->setName($name);
        $obj->setRole($faker->jobTitle);
        $obj->setCompany($faker->company);
        $obj->setCompany_url($faker->url);
        $obj->setDeadline($faker->date);
        $obj->setDescription($faker->sentences(3, true));
        // create Placement object in the database
        $db = new \Phizzle\PlacementRepository;
        // return the id for use in a test
        $id = $db::create($obj);

        return $id;
    }

    /**
     * Delete all records in the Placement database table
     */
    public function setupEmptyPlacementTable()
    {
        // get database connection
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();
        // delete all records in the placement table
        $statement = $connection->prepare('DELETE FROM placement');
        $statement->execute();
    }

    public function testCanCreatePlacement()
    {
        // test expectation
        $expectedResult = 1;
        // empty the database table
        $this->setupEmptyPlacementTable();
        // create a test Placement object
        $temp = $this->setupFakePlacement();
        // get database connection
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();
        // execute the COUNT query
        $statement = $connection->query('SELECT COUNT(*) AS counted FROM placement');
        $num = $statement->fetch(\PDO::FETCH_OBJ);
        // check the result
        $result = $num->counted;
        // there should be 1 object returned array
        $this->assertEquals($expectedResult, $result);
    }

    public function testCanUpdatePlacement()
    {
        // test expectation
        $expectedName = 'Abcdefghijk';
        // empty the database table
        $this->setupEmptyPlacementTable();
        // create a Placement object
        $originalName = $this->setupFakePlacement();
        // get database connection
        $db = new \Phizzle\PlacementRepository;
        // get the Placement object from the database
        $obj = $db->getOneById($originalName);
        // change the Title field value
        $obj->setName($expectedName);
        // update the database
        $result = $db->update($obj, $originalName);
        // there should be 1 row updated
        $this->assertEquals(1, $result);
        // get the updated Placement object from the database
        $updated = $db->getOneById($originalName);
        // get the Name field value
        $resultName = $updated->getName();
        // the Name strings should match
        $this->assertSame($expectedName, $resultName);
    }

    public function testCanGetAllPlacements()
    {
        $faker = \Faker\Factory::create();
        // empty the database table
        $this->setupEmptyPlacementTable();
        // generate random number of Placement objects
        $expectedNum = $faker->randomDigitNotNull;
        for ($i = 0; $i < $expectedNum; $i++) {
            $temp = $this->setupFakePlacement();
        }
        // get all Placement objects in the database
        $db = new \Phizzle\PlacementRepository;
        $result = $db->getAll();
        // there should be a matching number of objects in the returned array
        $this->assertCount($expectedNum, $result);
    }


}