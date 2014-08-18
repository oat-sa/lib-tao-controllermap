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

namespace oat\controllerMap;

/**
 * Abstract factory of the controller and action descriptions
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
interface Factory
{

    /**
     * 
     * @param unknown $extensionId
     */
    public function getControllers($extensionId);
    
    /**
     * Get a description of the controller
     *
     * @param string $controllerClassName
     * @return ControllerDescription
     */
    public function getControllerDescription($controllerClassName);
    
    /**
     * Get a description of the action
     * 
     * @param string $controllerClassName
     * @param string $actionName
     * @return ActionDescription
     */
    public function getActionDescription($controllerClassName, $actionName);
}