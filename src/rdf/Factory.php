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

use oat\controllerMap\Factory as iFactory;
use oat\controllerMap\ControllerDescription as iControllerDescription;
use oat\controllerMap\ActionDescription as iActionDescription;
use core_kernel_classes_Resource;
use core_kernel_classes_Class;

/**
 * 
 * @author bout
 *
 */
class Factory implements iFactory
{
    /**
     * Property to store the controllers classname
     * 
     * @todo Create property
     * @var string
     */
    const PROPERTY_ACL_CONTROLLER_CLASSNAME = 'http://www.tao.lu/Ontologies/taoFuncACL.rdf#controllerClassname';
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllers()
     */
    public function getControllers($extensionId) {

        $moduleClass = new core_kernel_classes_Class(CLASS_ACL_MODULE);
        $resources = $moduleClass->searchInstances(array(
            PROPERTY_ACL_MODULE_EXTENSION	=> $this->extension2Uri($extensionId)
        ), array(
            'like'	=> false
        ));
        
        $returnValue = array();
        foreach ($resources as $resource) {
            $returnValue[] = new ControllerDescription($resource);
        }
        return $returnValue;
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllerDescription()
     */
    public function getControllerDescription($controllerClassName) {
        return new ControllerDescription($this->controller2Uri($controllerClassName));
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getActionDescription()
     */
    public function getActionDescription($controllerClassName, $actionName) {
        $controllerUri = $this->controller2Uri($controllerClassName);
        list($prefix, $id ) = explode('#', $controllerUri);
        list($m, $extensionId, $controller) = explode('_', $id);
        $uri = $prefix.'a_'.$extId.'_'.$controller.'_'.$actionName;
        return new ActionDescription($uri);
    }

    /**
     * Map an extension to a resource
     * 
     * @param string $extensionId
     * @return string
     */
    private function extension2Uri($extensionId) {
        return FUNCACL_NS.'#e_'.$extensionId;
    }
    
    /**
     * Map a Controller to a resource
     * 
     * @param string $controllerClassName
     * @return string
     */
    private function controller2Uri($controllerClassName) {
        
        $namespaced = strpos($controllerClassName, '\\') !== false;
        if ($namespaced) {
            // @todo use routes to determin the responsible extension
            $parts = explode('\\', $controllerClassName);
            if (count($parts) < 3) {
                throw new  \common_exception_Error('Unsupported classname '.$controllerClassName);
            }
            $extensionId = $parts[1];
            $shortName = array_pop($parts);
        } else {
            list($extensionId, $directory, $shortName) = explode('_', $controllerClassName);
        }
        
        return FUNCACL_NS.'#m_'.$extensionId.'_'.$shortName;
    }
    
    /**
     * Stores the controllers in the ontology
     * 
     * assumes the controllers don't already exist
     * 
     * @param string $extensionId
     * @param array $controllers
     */
    public function setControllerDescriptions($extensionId, array $controllers) {
    
        $extensionResource = $this->storeExtension($extensionId);
        foreach ($controllers as $controller) {
            $controllerResource = $this->storeController($extensionResource, $controller);
            foreach ($controller->getActions() as $action) {
                $this->storeAction($controllerResource, $action);
            }
        }
    }
    
    /**
     * 
     * @param string $extensionId
     * @return core_kernel_classes_Resource
     */
    private function storeExtension($extensionId) {
        $specialURI = FUNCACL_NS.'#e_'.$extensionId;
        $resource = new core_kernel_classes_Resource($specialURI);
        if ($resource->exists()) {
            $extensionResource = $resource;
        } else {
            $extensionClass = new core_kernel_classes_Class(CLASS_ACL_EXTENSION);
            $extensionResource = $extensionClass->createInstance($extensionId,'',$specialURI);
            $extensionResource->setPropertiesValues(array(
                PROPERTY_ACL_COMPONENT_ID			=> $extensionId
            ));
        }
        
        return $extensionResource;
    }
    
    /**
     * 
     * @param core_kernel_classes_Resource $extensionResource
     * @param iControllerDescription $controller
     * @return core_kernel_classes_Resource
     */
    private function storeController(core_kernel_classes_Resource $extensionResource, iControllerDescription $controller) {

        list($prefix, $extensionName) = explode('_', substr($extensionResource->getUri(), strrpos($extensionResource->getUri(), '#')));
        preg_match("/[a-zA-Z]*$/", $controller->getClassName(), $matches);
        $shortName = $matches[0];
        $specialURI = FUNCACL_NS.'#m_'.$extensionName.'_'.$shortName;
        
        $controllerClass = new core_kernel_classes_Class(CLASS_ACL_MODULE);
        $controllerResource = $controllerClass->createInstance($shortName,'',$specialURI);
        $controllerResource->setPropertiesValues(array(
            PROPERTY_ACL_MODULE_EXTENSION       => $extensionResource,
            self::PROPERTY_ACL_CONTROLLER_CLASSNAME => $controller->getClassName(),
            PROPERTY_ACL_COMPONENT_ID              => $shortName
        ));
        
        return $controllerResource;
    }
    
    /**
     * 
     * @param core_kernel_classes_Resource $controllerResource
     * @param iActionDescription $action
     * @return core_kernel_classes_Resource
     */
    private function storeAction(core_kernel_classes_Resource $controllerResource, iActionDescription $action) {

        list($prefix, $extensionName, $controllerName) = explode('_', substr($controllerResource->getUri(), strrpos($controllerResource->getUri(), '#')));
        $specialURI = FUNCACL_NS.'#a_'.$extensionName.'_'.$controllerName.'_'.$action->getName();
        
        $actionClass = new core_kernel_classes_Class(CLASS_ACL_ACTION);
        $actionResource = $actionClass->createInstance($action->getName(),$action->getDescription(),$specialURI);
        $actionResource->setPropertiesValues(array(
            PROPERTY_ACL_ACTION_MEMBEROF	=> $controllerResource,
            PROPERTY_ACL_COMPONENT_ID			=> $action->getName()
        ));
        
        return $actionResource;
    }
}
