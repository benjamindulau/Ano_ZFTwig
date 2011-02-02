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
 * Resource for Twig
 *
 * @package    Ano_Application
 * @subpackage Resource
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_Application_Resource_Twig extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     * @var array
     */
    protected $_options = array();

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View
     */
    public function init()
    {
        $view = $this->getView();
        $viewSuffix = 'twig';
        if (array_key_exists('viewSuffix', $this->_options)
            && is_string($this->_options['viewSuffix'])) {
            $viewSuffix = $this->_options['viewSuffix'];
        }

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, array(
            'viewSuffix' => $viewSuffix
        ));        
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        
        return $view;
    }

    /**
     * Retrieve view object
     *
     * @return Zend_View
     */
    public function getView()
    {
        if (null === $this->_view) {            
            $this->_options = $this->getOptions();
            $this->_view = new Ano_ZFTwig_View($this->_options);
        }
        
        return $this->_view;
    }
}
