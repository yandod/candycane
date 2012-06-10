<?php
class AllTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All tests');
        $suite->addTestDirectory(TESTS . 'Case' . DS . 'behaviors');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'candycane');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'components');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'controllers');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'helpers');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'models');
		
        return $suite;
    }
}