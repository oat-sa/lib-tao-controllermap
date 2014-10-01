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

use oat\controllerMap\ControllerDescription as iControllerDescription;
use ReflectionClass;
use ReflectionMethod;

/**
 * Description of a Tao Controller
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class ControllerDescription implements iControllerDescription
{
    private static $BLACK_LIST = array('forward', 'redirect', 'forwardUrl', 'setView');
    /**
     * Reflection of the controller
     * 
     * @var ReflectionClass
     */
    private $class;
    
    /**
     * Create a new lazy parsing controller description
     * 
     * @param ReflectionClass $controllerClass
     */
    public function __construct(ReflectionClass $controllerClass) {
        $this->class = $controllerClass;
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getClassName()
     */
    public function getClassName() {
        return $this->class->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ControllerDescription::getActions()
     */
    public function getActions() {
        $actions = array();
        foreach ($this->class->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if (!$m->isConstructor() && !$m->isDestructor() && is_subclass_of($m->class, 'Module') && !in_array($m->name, self::$BLACK_LIST)) {
                $actions[] = new ActionDescription($m);
            }
        }
        return $actions;
    }
    
}