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
 * Abstract class for view template engines
 *
 * @package     Ano_View
 * @subpackage  Engine
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */

require_once 'Ano/View/Engine/Interface.php';

abstract class Ano_View_Engine_Abstract implements Ano_View_Engine_Interface
{
    /**
     * @var Zend_View_Interface
     */
    protected $view;

    /**
     * @var string The view script suffix used by the engine
     */
    protected $viewSuffix = null;


    /**
     * Constructor - sets the view suffix
     * And call the child init() method.
     *
     * @param Zend_View_Interface $view    Needed for some template Engines
     *                                     to be able to access to the view helpers
     *                                     through the view object.
     * @param array               $config  Configuration key-value pairs.
     */
    public function __construct(Zend_View_Interface $view, array $config = array())
    {
        $this->view = $view;
        if (array_key_exists('viewSuffix', $config)) {
            $this->setViewSuffix($config['viewSuffix']);
            unset($config['viewSuffix']);
        }

        $this->init($config);
    }

    /**
     * Init method for specific engine configuration
     *
     * @param array $config Configuration key-value pairs.
     */
    public function init(array $config = array())
    {
    }

    /**
     * Returns the view
     *
     * @return Zend_View_Interface
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     * Sets the view suffix for the engine
     *
     * @param string $viewSuffix
     * @return Ano_View_Engine_Abstract
     */
    public function setViewSuffix($viewSuffix)
    {
        $this->viewSuffix = $viewSuffix;
        return $this;
    }

    /**
     * Returns the view suffix
     *
     * @return string
     */
    public function getViewSuffix()
    {
        return $this->viewSuffix;
    }


    /**
     * @param string $template the file script to render
     * @param array  $vars     array of vars to assign to the template
     */
    abstract public function render($template, $vars = null);

}
