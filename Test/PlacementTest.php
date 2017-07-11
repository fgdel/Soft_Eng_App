<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 15/08/16
 * Time: 09:03
 */

namespace Phizzle\Test;

class PlacementTest extends \PHPUnit_Framework_TestCase
{

    public function testNameIsSet()
    {
        $faker = \Faker\Factory::create();
        // test expectations
        $originalName = $faker->slug(2);
        $expectedResult = $originalName;
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the Name
        $placement->setName( $originalName );
        // get the Name
        $result = $placement->getName();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testRoleIsSet()
    {
        $faker = \Faker\Factory::create();
        // test expectations
        $originalRole = $faker->word;
        $expectedResult = $originalRole;
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the platform
        $placement->setRole( $originalRole );
        // get the Role
        $result = $placement->getRole();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testCompanyIsSet($company = '')
    {
        // test expectations
        $originalCompany = 'Test-company';
        $expectedResult = 'Test-company';
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the placement title
        $placement->setCompany( $originalCompany );
        // get the placement company
        $result = $company->getCompany();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testCompanyUrlIsSet()
    {
        $faker = \Faker\Factory::create();
        // test expectations
        $originalCompanyUrl = $faker->url;
        $expectedResult = $originalCompanyUrl;
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the company url
        $placement->setCompany_url( $originalCompanyUrl );
        // get the released year
        $result = $placement->getCompany_url();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testDeadlineIsSet()
    {
        $faker = \Faker\Factory::create();
        // test expectations
        $originalDeadline = $faker-> date;
        $expectedResult = $originalDeadline;
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the deadline
        $placement->setDeadline( $originalDeadline );
        // get the deadline
        $result = $placement->getDeadline();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testDescriptionIsSet()
    {
        $faker = \Faker\Factory::create();
        // test expectations
        $originalDescription = $faker->realText(200);
        // instantiate placement
        $placement = new \Phizzle\Placement;
        // set the placement price
        $placement->setDescription( $originalDescription );
        // get the placement price
        $result = $placement->getDescription();
        // result should equal the expectation
        $this->assertEquals($originalDescription, $result);
    }

    public function testCanGetId()
    {
        // setup test placement
        $faker = \Faker\Factory::create();
        $obj = new \Phizzle\Placement;
        $obj->setName($faker->company);
        $obj->setRole($faker->jobTitle);
        $obj->setCompany($faker->company);
        $obj->setCompany_url($faker->url);
        $obj->setDeadline($faker->date);
        $obj->setDescription($faker->sentences(3, true));
        // create Placement object in the database
        $db = new \Phizzle\PlacementRepository;
        // returned id is the test expectation
        $originalId = $db::create($obj);
        // get Placement object from database
        $placement = $db->getOneById($originalId);
        // get the id from the Placement object
        $result = $placement->getId();
        // result should equal the expectation
        $this->assertEquals($result, $originalId);
        // delete Placement object from the database
        $deleted = $db->delete($result);
        // A single row should have been deleted from the database table
        $this->assertEquals(1, $deleted);
    }


}