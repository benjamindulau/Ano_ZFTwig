<?php
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
