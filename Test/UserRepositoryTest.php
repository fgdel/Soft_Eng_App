<?php

namespace Phizzle\Test;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Generate a fake User record with option to provide username, email, password and role values
     * @param string $username
     * @param string $email
     * @param string $password
     * @param int $role
     * @return int
     */
    public function setupFakeUser($username = '', $email='', $password='', $role=1)
    {
        // use the Faker\Factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();
        // set username, email and password
        $username = (!empty($username)) ? $username : $faker->userName;
        $email = (!empty($email)) ? $email : $faker->email;
        $password = (!empty($password)) ? $password : $faker->password(8,20);
        // setup the test user
        $test_user = new \Phizzle\User;
        $test_user->setUsername($username);
        $test_user->setEmail($email);
        $test_user->setFirstname($faker->firstName);
        $test_user->setLastname($faker->lastName);
        $test_user->setRole($role);
        $set_result = $test_user->setPassword($password);
        // create User in database
        $db = new \Phizzle\UserRepository;
        // return the id for use in a test
        $id = $db::create($test_user);

        return $id;
    }

    /**
     * Delete all records in the Users database table
     */
    public function setupEmptyUsersTable()
    {
        // get database connection
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();
        // delete all records in the users table
        $statement = $connection->prepare('DELETE FROM user');
        $statement->execute();
    }

    public function testCanCreateUser()
    {
        // test expectation
        $expectedResult = 1;
        // empty the database table
        $this->setupEmptyUsersTable();
        // create a test user
        $temp = $this->setupFakeUser();
        // get database connection
        $db = new \Mattsmithdev\PdoCrud\DatabaseManager();
        $connection = $db->getDbh();
        // execute the COUNT query
        $statement = $connection->query('SELECT COUNT(*) AS counted FROM user');
        $num = $statement->fetch(\PDO::FETCH_OBJ);
        // check the result
        $result = $num->counted;
        // there should be 1 User object returned array
        $this->assertEquals($expectedResult, $result);
    }

    public function testCanUpdateUser()
    {
        // test expectation
        $expectedFirstname = 'Abcdefghijklm123';
        // empty the database table
        $this->setupEmptyUsersTable();
        // create a test user
        $originalId = $this->setupFakeUser();
        // get database connection
        $db = new \Phizzle\UserRepository;
        // get the User object from the database
        $db_user = $db->getOneById($originalId);
        // change the Firstname field value
        $db_user->setFirstname($expectedFirstname);
        // update the database
        $result = $db->update($db_user, $originalId);
        // there should be 1 row updated
        $this->assertEquals(1, $result);
        // get the updated User object from the database
        $updated_user = $db->getOneById($originalId);
        // get the Firstname field value
        $resultFirstname = $updated_user->getFirstname();
        // the Firstname strings should match
        $this->assertSame($expectedFirstname, $resultFirstname);
    }

    public function testCanGetAllUsers()
    {
        // empty the database table
        $this->setupEmptyUsersTable();
        // generate 5 users
        for ($i=0; $i<5; $i++) {
            $temp = $this->setupFakeUser();
        }
        // get all Users in database
        $db = new \Phizzle\UserRepository;
        $result = $db->getAll();
        // there should be 5 User objects in the returned array
        $this->assertCount(5, $result);
    }

    public function testCanSearchByUsername()
    {
        // test expectation
        $originalUsername = 'test_user';
        $expectedResult = 1;
        // empty the database table
        $this->setupEmptyUsersTable();
        // add fake User with 'test_user' username
        $expectedId = $this->setupFakeUser($originalUsername);
        // generate 5 additional users
        for ($i=0; $i<5; $i++) {
            $temp = $this->setupFakeUser();
        }
        // find all Users in database using 'test' string
        $db = new \Phizzle\UserRepository;
        $results = $db->searchByUsernameOrEmail($originalUsername);
        // there should be a single User object in the returned array
        $this->assertCount($expectedResult, $results);
    }

    public function testCanSearchByEmail()
    {
        // test expectation
        $originalEmail = 'user@test.iam';
        $expectedResult = 1;
        // empty the database table
        $this->setupEmptyUsersTable();
        // add fake User with 'test_user' username
        $expectedId = $this->setupFakeUser('', $originalEmail);
        // generate 5 additional users
        for ($i=0; $i<5; $i++) {
            $temp = $this->setupFakeUser();
        }
        // find all Users in database using 'test' string
        $db = new \Phizzle\UserRepository;
        $results = $db->searchByUsernameOrEmail($originalEmail);
        // there should be a single User object in the returned array
        $this->assertCount($expectedResult, $results);
    }

    public function testCanFindMatchingUsernameAndPassword()
    {
        // test expectations
        $originalUsername = 'my_test_user';
        $originalEmail = 'user@test.iam';
        $originalPassword = 'testPassword';
        // empty the database table
        $this->setupEmptyUsersTable();
        // add User with 'my_test_user' username
        $expectedId = $this->setupFakeUser($originalUsername, $originalEmail, $originalPassword);
        // canFindMatchingUsernameAndPassword($username, $password)
        $db = new \Phizzle\UserRepository;
        $result = $db->canFindMatchingUsernameAndPassword($originalUsername, $originalPassword);
        // there should be a matching User object in the returned database
        $this->assertTrue($result);
    }

    public function testCannotFindNonExistingUsernameAndPassword()
    {
        // test expectations
        $originalUsername = 'my_test_user';
        $originalPassword = 'testPassword';
        // empty the database table
        $this->setupEmptyUsersTable();
        $db = new \Phizzle\UserRepository;
        $result = $db->canFindMatchingUsernameAndPassword($originalUsername, $originalPassword);
        // there should not be a matching User object in the database
        $this->assertFalse($result);
    }

    public function testCannotFindUsernameWithInvalidRole()
    {
        // test expectations
        $originalUsername = 'my_test_user';
        $originalRole = 123;
        // empty the database table
        $this->setupEmptyUsersTable();
        $db = new \Phizzle\UserRepository;
        $result = $db->canFindUsernameWithAdminRole($originalUsername, $originalRole);
        // the role is not that of Admin so the result should be FALSE
        $this->assertFalse($result);
    }

    public function testCannotFindInvalidUsernameWithAdminRole()
    {
        // test expectations
        $originalUsername = 'my_test_user';
        $originalRole = 2;
        // empty the database table
        $this->setupEmptyUsersTable();
        $db = new \Phizzle\UserRepository;
        $result = $db->canFindUsernameWithAdminRole($originalUsername, $originalRole);
        // the Username does not exist so the result should be FALSE
        $this->assertFalse($result);
    }

    public function testCanFindUsernameWithAdminRole()
    {
        // test expectations
        $originalUsername = 'my_test_user';
        $originalRole = 2;
        // empty the database table
        $this->setupEmptyUsersTable();
        // add User with 'my_test_user' username and Admin role
        $expectedId = $this->setupFakeUser($originalUsername, '', '', $originalRole);
        $db = new \Phizzle\UserRepository;
        $result = $db->canFindUsernameWithAdminRole($originalUsername, $originalRole);
        // the Username and role are correct so the result should be TRUE
        $this->assertTrue($result);
    }

}