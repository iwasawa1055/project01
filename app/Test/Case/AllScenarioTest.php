<?php

// ./app/Console/cake test app AllScenario
class AllScenarioTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All scenario tests');
        $suite->addTestDirectory(TESTS . 'Case/ScenarioReg');
        // $suite->addTestFile(TESTS . 'Case/Selenium/LoginTest.php');
        // $suite->addTestDirectory(TESTS . 'Case/Selenium');
        // $suite->addTestDirectoryRecursive(TESTS . 'Case/Selenium');
        return $suite;
    }
}
