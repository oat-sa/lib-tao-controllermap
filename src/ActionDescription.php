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
 * Description of an action within a Tao controller
 * 
 * @author Joel Bout <joel@taotesting.com>
 */
interface ActionDescription
{
    /**
     * Get the name of the action, which corresponds
     * to the name of the called function
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Get a human readable description of what the action does
     * 
     * @return string
     */
    public function getDescription();
    
    /**
     * Returns an array of all rights required to execute the action
     * 
     * The array uses the name of the parmeter as key and the value is
     * a string identifying the right
     * 
     * @return string
     */
    public function getRequiredRights();
}