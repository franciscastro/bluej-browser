 <?php
	
	class ViewTest extends WebTestCase
	{
		public function testViewSource()
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
			$this->click("//img[@alt='View']");
			$this->waitForPageToLoad("30000");
			$this->click("//img[@alt='View']");
			$this->waitForPageToLoad("30000");
			
			$this->assertTextPresent("       /**");
			$this->assertTextPresent("        * Write a description of class CoffeeMachine here");
			$this->assertTextPresent("        * ");
			$this->assertTextPresent("        * @author JR Novales");
			$this->assertTextPresent("        * @version July 2, 2010");
			$this->assertTextPresent("        */");
			$this->assertTextPresent("       public class CoffeeMachine");
			$this->assertTextPresent("       {");
			$this->assertTextPresent("           {");
			$this->assertTextPresent("               private double liters = 7;");
			$this->assertTextPresent("               private double sales = 0;");
			$this->assertTextPresent("               private int coffeecupsdispensed =0;");
			$this->assertTextPresent("               private double cost = 15.25;");
			$this->assertTextPresent("           }");
			$this->assertTextPresent("        public CoffeeMachine");
			$this->assertTextPresent("        {");
			$this->assertTextPresent("           public void addCoffee ( double refill )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters + refill");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public void sellCoffee ( int cups )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters - (cups*0.250)");
			$this->assertTextPresent("                   sales = liters + (cups*15.25)");
			$this->assertTextPresent("                   coffeecupsdispensed = coffeecupsdispensed + cups");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getCoffeeLeft()");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public int getCupsSold");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getSales");
			$this->assertTextPresent("         }");
			$this->assertTextPresent("       }");
		}
		
		public function testViewCompileSession()
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
			$this->click("//img[@alt='View']");
			$this->waitForPageToLoad("30000");
			
			$this->assertTextPresent("Not set");
			$this->assertTextPresent("2010/07/02");
			$this->assertTextPresent("20060907");
			$this->assertTextPresent("2.6");
			$this->assertTextPresent("c79a9d40971a30909becc3748c66e60");
			$this->assertTextPresent("C:\Users\c79a9d40971a30909becc3748c66e60");
			$this->assertTextPresent("Windows 7");
			$this->assertTextPresent("6.1");
			$this->assertTextPresent("x86");
			$this->assertTextPresent("10.2.3.96");
			$this->assertTextPresent("F227-10");
			$this->assertTextPresent("F227_10");
			$this->assertTextPresent("1278047710994782");
			$this->assertTextPresent("1278045716");
			$this->assertTextPresent("C:\Users\c79a9d40971a30909becc3748c66e60\Desktop\Novales Java\CoffeeMachine");
			$this->assertTextPresent("C:\Users\c79a9d40971a30909becc3748c66e60\Desktop\Novales Java\CoffeeMachine");
			$this->assertTextPresent("CompileData");
			
		}
		
		public function testViewCompileList()
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
		
		public function testViewCompare()
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
			$this->click("//img[@alt='View']");
			$this->waitForPageToLoad("30000");
			$this->click("//img[@alt='Compare with next']");
			$this->waitForPageToLoad("30000");
			
			$this->assertTextPresent("2010/07/02 01:24:01 PM");
			$this->assertTextPresent("2010/07/02 01:24:14 PM");
			
			
			
			$this->assertTextPresent("       /**");
			$this->assertTextPresent("        * Write a description of class CoffeeMachine here");
			$this->assertTextPresent("        * ");
			$this->assertTextPresent("        * @author JR Novales");
			$this->assertTextPresent("        * @version July 2, 2010");
			$this->assertTextPresent("        */");
			$this->assertTextPresent("       public class CoffeeMachine");
			$this->assertTextPresent("       {");
			$this->assertTextPresent("           {");
			$this->assertTextPresent("               private double liters = 7;");
			$this->assertTextPresent("               private double sales = 0;");
			$this->assertTextPresent("               private int coffeecupsdispensed =0;");
			$this->assertTextPresent("               private double cost = 15.25;");
			$this->assertTextPresent("           }");
			$this->assertTextPresent("        public CoffeeMachine");
			$this->assertTextPresent("        {");
			$this->assertTextPresent("           public void addCoffee ( double refill )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters + refill");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public void sellCoffee ( int cups )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters - (cups*0.250)");
			$this->assertTextPresent("                   sales = liters + (cups*15.25)");
			$this->assertTextPresent("                   coffeecupsdispensed = coffeecupsdispensed + cups");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getCoffeeLeft()");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public int getCupsSold");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getSales");
			$this->assertTextPresent("         }");
			$this->assertTextPresent("       }");
			
			
			$this->assertTextPresent("       /**");
			$this->assertTextPresent("        * Write a description of class CoffeeMachine here.");
			$this->assertTextPresent("        * ");
			$this->assertTextPresent("        * @author JR Novales");
			$this->assertTextPresent("        * @version July 2, 2010");
			$this->assertTextPresent("        */");
			$this->assertTextPresent("       public class CoffeeMachine");
			$this->assertTextPresent("           {");
			$this->assertTextPresent("               private double liters = 7;");
			$this->assertTextPresent("               private double sales = 0;");
			$this->assertTextPresent("               private int coffeecupsdispensed =0;");
			$this->assertTextPresent("               private double cost = 15.25;");
			$this->assertTextPresent("           }");
			$this->assertTextPresent("        public CoffeeMachine");
			$this->assertTextPresent("        {");
			$this->assertTextPresent("           public void addCoffee ( double refill )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters + refill");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public void sellCoffee ( int cups )");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("                   liters = liters - (cups*0.250)");
			$this->assertTextPresent("                   sales = liters + (cups*15.25)");
			$this->assertTextPresent("                   coffeecupsdispensed = coffeecupsdispensed + cups");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getCoffeeLeft()");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public int getCupsSold");
			$this->assertTextPresent("               {");
			$this->assertTextPresent("               }");
			$this->assertTextPresent("           public double getSales");
			$this->assertTextPresent("         }");
		}
		
	}
?>