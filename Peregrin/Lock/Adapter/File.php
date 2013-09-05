<?php
/*
  +------------------------------------------------------------------------+
  | Peregrin Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2012 Peregrin Team (http://www.phalconphp.com)       |
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
    use Phalcon\Cache\Backend\File as CacheBackend;
    use Phalcon\Cache\Frontend\Data as CacheFrontend;
    use Peregrin\Lock\Adapter\Base;
    use Peregrin\Lock\Exception;

    class File extends Base
    {
        private $cacheBackend = null;
        /**
         * Class constructor.
         *
         * @param  null|array                   $options
         * @throws \Peregrin\Lock\Exception
         */
        public function __construct(array $options)
        {
            if (is_array($options)) {
                if (!isset($options['lockDir'])) {
                    throw new Exception('No lockDir given in options');
                } else {
                    $options['cacheDir'] = $options['lockDir'];
                    unset($options['lockDir']);
                }
            } else {
                throw new Exception('No configuration given');
            }

            parent::__construct($options);
        }

        /**
         * Returns cache backend instance.
         *
         * @return \Phalcon\Cache\BackendInterface
         */
        protected function getCacheBackend()
        {
            if (null === $this->cacheBackend) {
                $options = $this->getOptions();
                $this->cacheBackend = new CacheBackend(
                    new CacheFrontend(array('lifetime' => $options['lifetime'])),
                    $options
                );
            }

            return $this->cacheBackend;
        }
    }
}
