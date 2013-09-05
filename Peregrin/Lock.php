<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
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

namespace Phalcon {

    class Lock
    {
        private $_key = null;
        /** @var \Phalcon\Cache\Backend null */
        private $_backend = null;

        const LOCK_DEFAULT_ADAPTER = 'File';

        /**
         *
         *
         * @param array $options
         */
        public function __construct(array $options)
        {
            if (!isset($options['adapter'])) {
                $options['adapter'] = 'file';
            }
            $adapter = '\\Phalcon\\Lock\\Adapter\\' . ucfirst($options['adapter']);
            $this->_backend = new $adapter( $options);
        }

        /**
         * @param int $wait
         *
         * @return bool
         * @throws Phalcon\Lock\Exception
         */
        public function lock($wait = 0)
        {
            if ($this->isLocked()) {
                $start = time();
                while (time() - $start < $wait && $this->isLocked()) {
                    usleep(50000);
                }
                if ($this->isLocked()) {
                    throw new \Phalcon\Lock\Exception('Resource busy');
                }
            }

            $pid = $this->getPid();
            $key = $this->getKey();
            $this->_backend->save($key, $pid);

            return true;
        }

        /**
         * @return bool
         */
        public function isLocked()
        {
            $locked = false;

            $key = $this->getKey();
            if ($this->_backend->exists($key)) {
                $pid    = $this->_backend->get($key);
                $locked = $this->proccessExists($pid);

                if (!$locked) {
                    $this->_backend->delete($key);
                }
            }
            return $locked;
        }

        /**
         * @return null|string
         */
        public function getKey()
        {
            global $argv;
            if (!$this->_key) {
                $this->_key = basename($argv[0]). '.lock';
            }

            return $this->_key;
        }

        /**
         * @param $key
         *
         * @return $this
         */
        public function setKey($key)
        {
            $this->_key = $key . '.lock';
            return $this;
        }

        /**
         * @param $pid
         *
         * @return bool
         */
        private function proccessExists($pid)
        {
            $exists = false;

            if ($pid) {
                $exists = file_exists('/proc/' . $pid);
            }
            return $exists;
        }

        /**
         * @return int
         */
        private function getPid()
        {
            return getmypid();
        }

        /**
         * @return $this
         */
        public function unlock()
        {
            if ($this->isLocked()) {
                $this->_backend->delete($this->getKey());
            }
            return $this;
        }
    }
}
