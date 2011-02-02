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
 * View resource for Ano_View
 * Supports multiple template engines
 *
 * @package    Ano_Application
 * @subpackage Resource
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_Application_Resource_View extends Zend_Application_Resource_ResourceAbstract
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

        $viewSuffix = 'phtml';        
        if (array_key_exists('engines', $this->_options)
                && is_array($this->_options['engines'])) {            

            foreach($this->_options['engines'] as $key => $engineConfig) {
                if (array_key_exists('class', $engineConfig)) {
                    $engineClass = $engineConfig['class'];
                    unset($engineConfig['class']);
                    
                    $engine = new $engineClass($view, $engineConfig);
                    
                    $view->addTemplateEngine($key, $engine);
                    if (array_key_exists('isDefault', $engineConfig)
                        && true === (bool)$engineConfig['isDefault']) {                        
                        $view->setDefaultTemplateEngine($key);
                        $viewSuffix = $engine->getViewSuffix();
                    }
                }               
            }

        }

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        $viewRenderer->setViewSuffix($viewSuffix);
        
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
            $this->_view = new Ano_View($this->_options);
        }
        
        return $this->_view;
    }
}
