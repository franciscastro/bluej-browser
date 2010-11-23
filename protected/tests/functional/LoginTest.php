<?php

class LoginTest extends WebTestCase
{	
	public function testLoginWrongUsername()
	{
		$this->open('');
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
		$this->type("LoginForm_username", "wrong");
		$this->type("LoginForm_password", "admin");
		$this->click("yt0");
		$this->waitForPageToLoad("30000");
		$this->assertTextPresent("Incorrect username or password.");
	}
	
	public function testLoginWrongPassword()
	{
		$this->open('');
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
		$this->type("LoginForm_username", "admin");
		$this->type("LoginForm_password", "wrong");
		$this->click("yt0");
		$this->waitForPageToLoad("30000");
		$this->assertTextPresent("Incorrect username or password.");
	}
	
	public function testLoginWrongUsernameAndPassword()
	{
		$this->open('');
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
		$this->type("LoginForm_username", "wrong");
		$this->type("LoginForm_password", "wrong");
		$this->click("yt0");
		$this->waitForPageToLoad("30000");
		$this->assertTextPresent("Incorrect username or password.");
	}
	
	public function testLoginSucceed()
	{
		$this->open('');
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
		$this->type("LoginForm_username", "admin");
		$this->type("LoginForm_password", "admin");
		$this->click("yt0");
		$this->waitForPageToLoad("30000");
		$this->assertTextPresent("Logout");
	}
}
?>