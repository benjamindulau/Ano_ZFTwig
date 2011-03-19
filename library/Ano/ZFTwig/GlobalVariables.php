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
 * Global variables for twig templates
 *
 * @package    Ano_ZFTwig
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_GlobalVariables
{
    /**
     * Returns the current app environment.
     *
     * @return string The current environment string (e.g 'development')
     */
    public function getEnvironment()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap   = $frontController->getParam('bootstrap');
        $application = $bootstrap->getApplication();

        return $application->getEnvironment();
    }
}
