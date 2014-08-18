<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA;
 *
 *
 */

namespace oat\controllerMap\keyValue;

use common_persistence_KeyValuePersistence;

/**
 * A generic container for lazy loading
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
abstract class Element
{
    /**
     * @var Factory
     */
    private $factory;
    
    /**
     * Unique identifier of the element
     *
     * @var string
     */
    private $serial;
    
    /**
     * An array containing all data of the element
     * 
     * @var array
     */
    private $data = null;
    
    /**
     * 
     * @param Factory $factory
     * @param mixed $data
     */
    public function __construct(Factory $factory, $serial) {
        $this->factory = $factory;
        $this->serial = $serial;
    }
    
    /**
     * @return Factory
     */
    public function getFactory() {
       return $this->factory; 
    }
    
    /**
     * Lazy loading access to the data
     * 
     * @param string $key
     * @return mixed
     */
    protected function getData($key) {
        if (is_null($this->data)) {
            $this->data = $this->getFactory()->loadReference($this->serial);
        }
        
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            \common_Logger::w('Undefined key '.$key);
            return null;
        }
    }
}