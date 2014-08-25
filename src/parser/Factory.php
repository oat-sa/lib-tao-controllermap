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

namespace oat\controllerMap\parser;

use oat\controllerMap\Factory as iFactory;
use common_Logger;
use ReflectionClass;
use RecursiveDirectoryIterator;
use DirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RecursiveRegexIterator;
use helpers_PhpTools;

/**
 * Fectory to create the description of the controllers and
 * actions from the source code
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class Factory implements iFactory
{
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllers()
     */
    public function getControllers($extensionId) {

        $ext = \common_ext_ExtensionsManager::singleton()->getExtensionById($extensionId);
        $controllers = array();
        foreach ($this->getControllerClasses($ext) as $className) {
            $controllers[] = $this->getControllerDescription($className);
        }
        return $controllers;
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getControllerDescription()
     */
    public function getControllerDescription($controllerClassName) {
        $reflector = new ReflectionClass($controllerClassName);
        return new ControllerDescription($reflector);
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\Factory::getActionDescription()
     */
    public function getActionDescription($controllerClassName, $actionName) {
        $reflector = new \ReflectionMethod($controllerClassName, $actionName);
        return new ActionDescription($reflector);
    }
    
    /**
     * Helper to find all controllers
     * 
     * @param \common_ext_Extension $extension
     * @return array
     * @ignore
     */
    private function getControllerClasses(\common_ext_Extension $extension) {
        $returnValue = array();
    
        // routes
        $namespaces = array();
        foreach ($extension->getManifest()->getRoutes() as $mapedPath => $ns) {
            $namespaces[] = trim($ns, '\\');
        }
        if (!empty($namespaces)) {
            common_Logger::t('Namespace found in routes for extension '. $extension->getId() );
            $classes = array();
            $recDir = new RecursiveDirectoryIterator($extension->getDir());
            $recIt = new RecursiveIteratorIterator($recDir);
            $regexIt = new RegexIterator($recIt, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
            foreach ($regexIt as $entry) {
                $info = helpers_PhpTools::getClassInfo($entry[0]);
                if (!empty($info['ns'])) {
                    $ns = trim($info['ns'], '\\');
                    if (!empty($info['ns']) && in_array($ns, $namespaces)) {
                        $returnValue[$info['class']] = $ns.'\\'.$info['class'];
                    }
                }
            }
        }
    
        // legacy
        if ($extension->hasConstant('DIR_ACTIONS') && file_exists($extension->getConstant('DIR_ACTIONS'))) {
            $dir = new DirectoryIterator($extension->getConstant('DIR_ACTIONS'));
            foreach ($dir as $fileinfo) {
                if(preg_match('/^class\.[^.]*\.php$/', $fileinfo->getFilename())) {
                    $module = substr($fileinfo->getFilename(), 6, -4);
                    $returnValue[$module] = $extension->getId().'_actions_'.$module;
                }
            }
        }
    
        // validate the classes
        foreach (array_keys($returnValue) as $key) {
            $class = $returnValue[$key];
            if (!class_exists($class)) {
                common_Logger::w($class.' not found');
                unset($returnValue[$key]);
            } elseif (!is_subclass_of($class, 'Module')) {
                common_Logger::w($class.' does not inherit Module');
                unset($returnValue[$key]);
            }
        }
    
        return (array) $returnValue;
    }
}
