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

use oat\controllerMap\Factory as iFactory;
use common_persistence_KeyValuePersistence;
use oat\controllerMap\ControllerDescription as iControllerDescription;
use oat\controllerMap\ActionDescription as iActionDescription;

/**
 * Fectory to create the description of the controllers and
 * actions from the source code
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class Factory implements iFactory
{
    /**
     * Persistence used to store and retrieve map
     * 
     * @var common_persistence_KeyValuePersistence
     */
    private $persistence;
    
    public function __construct(common_persistence_KeyValuePersistence $persistence) {
        $this->persistence = $persistence;
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllers()
     */
    public function getControllers($extensionId) {

        $struct = $this->persistence->get($extensionId);
        
        $controllers = array();
        foreach ($struct['controllers'] as $className) {
            $controllers[] = $this->getControllerDescription($className);
        }
        return $controllers;
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllerDescription()
     */
    public function getControllerDescription($controllerClassName) {
        return new ControllerDescription($this, $controllerClassName);
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getActionDescription()
     */
    public function getActionDescription($controllerClassName, $actionName) {
        return new ActionDescription($this, $controllerClassName.'@'.$actionName);
    }
    
    public function setControllerDescriptions($extensionId, array $controllers) {

        $this->storeExtensionMap($extensionId, $controllers);
        foreach ($controllers as $controller) {
            $this->storeController($controller);
            foreach ($controller->getActions() as $action) {
                $this->storeAction($controller->getClassName(), $action);
            }
        }
    }
    
    private function storeExtensionMap($extensionId, $controllers) {
        $classNames = array();
        foreach ($controllers as $controller) {
            $classNames[] = $controller->getClassName();
        }
        $this->persistence->set($extensionId, array('controllers' => $classNames));
    }
    
    private function storeController(iControllerDescription $controller) {
        $key = $controller->getClassName();
        $this->persistence->set($key, ControllerDescription::toArray($controller));
    }
    
    private function storeAction($controllerClassName, iActionDescription $action) {
        $key = $controllerClassName.'@'.$action->getName();
        $this->persistence->set($key, ActionDescription::toArray($action));
    }
    
    public function loadReference($serial) {
        return $this->persistence->get($serial);
    }
}
