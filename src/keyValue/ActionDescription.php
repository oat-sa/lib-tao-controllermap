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

use oat\controllerMap\ActionDescription as iActionDescription;

/**
 * keyvalue implementation of an action description
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
class ActionDescription extends Element implements iActionDescription
{
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ActionDescription::getName()
     */
    public function getName() {
        return $this->getData('name');
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\model\ActionDescription::getDescription()
     */
    public function getDescription() {
        return $this->getData('description');
    }
    
    /**
     * (non-PHPdoc)
     * @see \oat\controllerMap\ActionDescription::getRequiredRights()
     */
    public function getRequiredRights() {
        return $this->getData('priviledge');
    }
    
    /**
     * @param iActionDescription $action
     * @return array
     */
    public static function toArray(iActionDescription $action) {
        return array(
            'name' => $action->getName(),
            'desc' => $action->getDescription(),
            'privileges' => $action->getRequiredRights()
        );
    }
}