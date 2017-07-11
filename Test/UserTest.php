<?php

namespace Phizzle\Test;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo);
    }

    public function testUsernameIsSet()
    {
        // test expectations
        $originalUsername = 'jane_test';
        $expectedResult = 'jane_test';
        // instantiate user
        $user = new \Phizzle\User;
        // set the username
        $user->setUsername( $originalUsername );
        // get the username
        $result = $user->getUsername();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testFirstnameIsSet()
    {
        // test expectations
        $originalFirstname = 'Jane';
        $expectedResult = 'Jane';
        // instantiate user
        $user = new \Phizzle\User;
        // set the first name
        $user->setFirstname( $originalFirstname );
        // get the first name
        $result = $user->getFirstname();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testLastnameIsSet()
    {
        // test expectations
        $originalLastname = 'Doe';
        $expectedResult = 'Doe';
        // instantiate user
        $user = new \Phizzle\User;
        // set the last name
        $user->setLastname( $originalLastname );
        // get the last name
        $result = $user->getLastname();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testEmailIsSet()
    {
        // test expectations
        $originalEmail = 'jane@doe.ie';
        $expectedResult = 'jane@doe.ie';
        // instantiate user
        $user = new \Phizzle\User;
        // set the email
        $user->setEmail( $originalEmail );
        // get the email
        $result = $user->getEmail();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testRoleIsSet()
    {
        // test expectations
        $originalRole = 1;
        $expectedResult = 1;
        // instantiate user
        $user = new \Phizzle\User;
        // set the role
        $user->setRole( $originalRole );
        // get the role
        $result = $user->getRole();
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testPasswordIsSet()
    {
        // test expectations
        $originalPass = 'password';
        $expectedResult = 0;
        // instantiate user
        $user = new \Phizzle\User;
        // set the password
        $result = $user->setPassword( $originalPass, $originalPass );
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testPasswordIsNotSet()
    {
        // test expectations
        $originalPass = 'password';
        $originalConfirm = 'Password';
        $expectedResult = -1;
        // instantiate user
        $user = new \Phizzle\User;
        // attempt to set the password
        $result = $user->setPassword( $originalPass, $originalConfirm );
        // result should equal the expectation
        $this->assertEquals($expectedResult, $result);
    }

    public function testCanGetPassword()
    {
        // test expectations
        $originalPass = 'password';
        $originalConfirm = 'password';
        // instantiate user
        $user = new \Phizzle\User;
        // set the password
        $set_result = $user->setPassword( $originalPass, $originalConfirm );
        // get the password hash
        $result = $user->getPassword();
        // result should equal the expectation
        $this->assertEquals($result, crypt($originalPass, $result));
    }

    public function testCanGetId()
    {
        // setup test user
        $test_user = new \Phizzle\User;
        $test_user->setUsername('test_user');
        $test_user->setEmail('jane@test.ie');
        $test_user->setFirstname('Jane');
        $test_user->setLastname('Test');
        $test_user->setRole(1);
        $set_result = $test_user->setPassword('password');
        // create User in database
        $db = new \Phizzle\UserRepository;
        // returned id is the test expectation
        $originalId = $db::create($test_user);
        // get User object from database
        $db_user = $db->getOneById($originalId);
        // get the id from the User object
        $result = $db_user->getId();
        // result should equal the expectation
        $this->assertEquals($result, $originalId);
        // delete user from the database
        $deleted = $db->delete($result);
        // A single row should have been deleted from the database table
        $this->assertEquals(1, $deleted);
    }

}