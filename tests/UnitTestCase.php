<?php
use Phalcon\DI;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

/**
 * User: Sergey V. Kuzin <sergey@kuzin.name>
 * Date: 03.09.13
 * Time: 0:23
 */

abstract class UnitTestCase extends PhalconTestCase
{

    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    protected function setUp()
    {

        // Load any additional services that might be required during testing
        $di = DI::getDefault();

        // get any DI components here, if you have a config, be sure to pass it to the parent

        parent::setUp($di);

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}