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

use oat\controllerMap\ControllerDescription as iControllerDescription;
use ReflectionClass;
use ReflectionMethod;

/**
 * keyvalue implementation of an controller description
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class ControllerDescription extends Element implements iControllerDescription
{
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getClassName()
     */
    public function getClassName() {
        return $this->getData('className');
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getActions()
     */
    public function getActions() {
        $actions = array();
        foreach ($this->getData('actions') as $actionName) {
            $actions[] = $this->getFactory()->getActionDescription($this->getClassName(), $actionName);
        }
        return $actions;
    }
    
    /**
     * @param iControllerDescription $controller
     * @return array
     */
    public static function toArray(iControllerDescription $controller) {
        $actions = array();
        foreach ($controller->getActions() as $action) {
            $actions[] = $action->getName();
        }
        
        return array(
            'className' => $controller->getClassName(),
            'actions' => $actions
        );
    }
}