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

use \core_kernel_classes_Resource;
use oat\controllerMap\ActionDescription as iActionDescription;

/**
 * generis implementation of an action description
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class ActionDescription extends core_kernel_classes_Resource implements iActionDescription
{
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ActionDescription::getName()
     */
    public function getName() {
        return substr($this->getUri(), strrpos($this->getUri(), '_')+1);
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ActionDescription::getDescription()
     */
    public function getDescription() {
        return parent::getDescription();
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\ActionDescription::getRequiredRights()
     */
    public function getRequiredRights() {
        return array();
    }
}