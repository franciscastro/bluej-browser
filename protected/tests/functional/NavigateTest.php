 <?php
	
	class NavigateTest extends WebTestCase
	{
		public function testUnloggedNavigateCompilationLogs()
		{
			$this->open('');
			$this->click("link=Compilation Logs");
			$this->waitForPageToLoad("30000");
			$this->assertTextPresent("Please fill out the following form with your login credentials:");
		}
		
		public function testUnloggedNavigateImportLogs()
		{
			$this->open('');
			$this->click("link=Import Logs");
			$this->waitForPageToLoad("30000");
			$this->assertTextPresent("Please fill out the following form with your login credentials:");
		}
		
		public function testNavigateCompileList()
		{
			$this->open('');
			$this->click("link=Login");
			$this->waitForPageToLoad("30000");
			$this->type("LoginForm_username", "admin");
			$this->type("LoginForm_password", "admin");
			$this->click("yt0");
			$this->waitForPageToLoad("30000");
			$this->click("link=Compilation Logs");
			$this->waitForPageToLoad("30000");
			
//			$this->click("link=Next >");
//			$this->waitForPageToLoad("30000");
//			$this->assertTextPresent("2010/07/02 12:57:52 PM");
//			$this->assertTextPresent("2010/07/02 12:45:46 PM");
//			$this->assertTextPresent("2010/07/02 12:46:11 PM");
//			$this->assertTextPresent("2010/07/02 12:56:43 PM");
//			$this->assertTextPresent("2010/08/12 06:02:14 PM");
//			$this->assertTextPresent("2010/07/02 03:47:22 PM");
//			$this->assertTextPresent("2010/07/02 03:49:29 PM");
//			$this->assertTextPresent("2010/07/02 03:51:10 PM");
//			$this->assertTextPresent("2010/07/02 03:52:11 PM");
//			$this->assertTextPresent("2010/07/02 03:42:30 PM");
//			$this->assertTextPresent("2010/07/02 03:57:17 PM");
//			$this->assertTextPresent("2010/08/12 06:42:48 PM");
//			$this->assertTextPresent("2010/07/02 03:47:22 PM");
//			$this->assertTextPresent("2010/07/02 03:49:29 PM");
//			$this->assertTextPresent("2010/07/02 03:51:10 PM");
//			$this->assertTextPresent("2010/07/02 03:52:11 PM");
//			$this->assertTextPresent("2010/07/02 03:42:30 PM");
//			$this->assertTextPresent("2010/07/02 03:57:17 PM");
//			$this->assertTextPresent("2010/08/12 06:54:46 PM");
//			$this->assertTextPresent("2010/07/02 03:47:22 PM");
			
			//$this->click("link=< Previous");
//			$this->waitForPageToLoad("30000");
			$this->assertTextPresent("2010/07/02 01:24:01 PM");
			$this->assertTextPresent("2010/07/02 12:57:47 PM");
			$this->assertTextPresent("2010/07/02 01:22:07 PM");
			$this->assertTextPresent("2010/07/02 01:30:25 PM");
			$this->assertTextPresent("2010/07/02 01:13:12 PM");
			$this->assertTextPresent("2010/07/02 01:11:35 PM");
			$this->assertTextPresent("2010/07/02 01:00:05 PM");
			$this->assertTextPresent("2010/07/02 01:28:10 PM");
			$this->assertTextPresent("2010/07/02 01:10:01 PM");
			$this->assertTextPresent("2010/07/02 12:43:48 PM");
			$this->assertTextPresent("2010/07/02 12:54:07 PM");
			$this->assertTextPresent("2010/07/02 12:48:05 PM");
			$this->assertTextPresent("2010/07/02 01:06:14 PM");
			$this->assertTextPresent("2010/07/02 12:59:39 PM");
			$this->assertTextPresent("2010/07/02 01:26:43 PM");
			$this->assertTextPresent("2010/07/02 01:27:35 PM");
			$this->assertTextPresent("2010/07/02 01:01:18 PM");
			$this->assertTextPresent("2010/07/02 01:29:33 PM");
			$this->assertTextPresent("2010/07/02 01:25:18 PM");
			$this->assertTextPresent("2010/07/02 01:21:37 PM");
		}			
		
	}
?>