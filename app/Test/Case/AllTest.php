<?php

const TESTS_SELENIUM = TESTS . 'Case/Selenium/';

// ./app/Console/cake test app All
class AllTest extends CakeTestSuite
{
    public static function suite()
    {
        $suite = new CakeTestSuite('All scenario tests');
        $suite->addTestFile(TESTS_SELENIUM . '01NonLogin/S01A1InquiryTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '12EntryCreditCard/S12A1LoginTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '21CustomerCreditCard/S21A1LoginTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '21CustomerCreditCard/S21C1CustomerEmailTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '21CustomerCreditCard/S21C1CustomerPasswordTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '21CustomerCreditCard/S21C1ustomerCreditCardTest.php');
        $suite->addTestFile(TESTS_SELENIUM . '21CustomerCreditCard/S21D1OrderInboundOutboundTest.php');

        // $suite->addTestDirectory(TESTS_SELENIUM . '21CustomerCreditCard');
        // $suite->addTestFile(TESTS . '21CustomerCreditCard/S21D1OrderInboundOutboundTest.php');
        return $suite;
    }
}
