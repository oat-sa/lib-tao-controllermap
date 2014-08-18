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

namespace oat\controllerMap\rdf;

use oat\controllerMap\ControllerDescription as iControllerDescription;
use core_kernel_classes_Resource;
use core_kernel_classes_Class;

/**
 * generis implementation of a controller description
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class ControllerDescription extends core_kernel_classes_Resource implements iControllerDescription
{
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getClassName()
     */
    public function getClassName() {
        return (string)$this->getOnePropertyValue(new \core_kernel_classes_Property(Factory::PROPERTY_ACL_CONTROLLER_CLASSNAME));
        return $this->getLabel();
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getActions()
     */
    public function getActions() {
        $moduleClass = new core_kernel_classes_Class(CLASS_ACL_ACTION);
        $resources = $moduleClass->searchInstances(array(
            PROPERTY_ACL_ACTION_MEMBEROF => $this->getUri()
        ), array(
        	'like' => false, 'recursive' => false
        ));
        
        $returnValue = array();
        foreach ($resources as $resource) {
            $returnValue[] = new ActionDescription($resource);
        }
        return (array) $returnValue;
    }
    
}