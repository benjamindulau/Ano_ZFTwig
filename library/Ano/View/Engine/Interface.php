<?php
/**
 * This file is part of the Ano_ZFTwig package
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @copyright  Copyright (c) 2010-2011 Benjamin Dulau <benjamin.dulau@gmail.com>
 * @license    New BSD License
 */

/**
 * Interface class for Ano_View compatible template engine implementations.
 * Essentialy for polymorphism.
 *
 * @package    Ano_View
 * @subpackage Engine
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
interface Ano_View_Engine_Interface
{   
    /**
     * @param string $template the file script to render
     * @param array  $vars     array of vars to assign to the template
     */
    //public function render($template, $vars);

    /**
     * Sets the view suffix for the engine
     *
     * @param string $viewSuffix
     */
    public function setViewSuffix($viewSuffix);

    /**
     * Returns the view suffix
     *
     * @return string
     */
    public function getViewSuffix();
}
