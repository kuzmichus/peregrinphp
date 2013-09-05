<?php
/*
  +------------------------------------------------------------------------+
  | Peregrin Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2012 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  |                                                                        |
  +------------------------------------------------------------------------+
  | Authors: Sergey Kuzin <sergey@kuzin.name>                              |
  +------------------------------------------------------------------------+
*/

namespace Peregrin\Lock\Adapter {

    abstract class Base
    {
        /**
         * Default options for cache backend.
         *
         * @var array
         */
        protected static $defaults = array(
            'lifetime' => 2592000,
            'prefix'   => '',
        );

        /**
         * Backend's options.
         *
         * @var array
         */
        protected $options = null;

        /**
         * Class constructor.
         *
         * @param  null|array                   $options
         * @throws \Phalcon\Mvc\Model\Exception
         */
        public function __construct(array $options = null)
        {
            if (is_array($options)) {
                if (!isset($options['lifetime'])) {
                    $options['lifetime'] = self::$defaults['lifetime'];
                }

                if (!isset($options['prefix'])) {
                    $options['prefix'] = self::$defaults['prefix'];
                }
            }

            $this->options = $options;
        }

        public function getOptions()
        {
            return $this->options;
        }

        public function save($key, $data)
        {
            $option = $this->getOptions();
            $this->getCacheBackend()->save($key, $data, $option['lifetime']);
            return $this;
        }

        public function get($key)
        {
            return $this->getCacheBackend()->get($key);
        }

        public function exists($key)
        {
            return $this->getCacheBackend()->exists($key);
        }

        public function delete($key)
        {
            return $this->getCacheBackend()->delete($key);
        }

        /**
         * Returns cache backend instance.
         *
         * @return \Phalcon\Cache\BackendInterface
         */
        abstract protected function getCacheBackend();
    }

}
