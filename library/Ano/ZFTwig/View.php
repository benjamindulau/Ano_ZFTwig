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
 * Zend Framework view object for Twig
 *
 * @package     Ano_ZFTwig
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_View extends Zend_View_Abstract
                      implements Zend_View_Interface
{
    /**
     * @var Twig_Environment
     */
    protected $twig = null;

    /**
     * Constructor.
     *
     * @param array $config Configuration key-value pairs.
     */
    public function __construct($config = array())
    {
        $options = array();
        if (array_key_exists('options', $config)) {
            $options = $config['options'];
        }

        $viewPaths = array();
        if (array_key_exists('viewPaths', $config)) {
            $viewPaths = $config['viewPaths'];
        }

        // user-defined helper path
        if (array_key_exists('helperPath', $config)) {
            if (is_array($config['helperPath'])) {
                foreach ($config['helperPath'] as $prefix => $path) {
                    $this->addHelperPath($path, $prefix);
                }
            } else {
                $prefix = 'Zend_View_Helper';
                if (array_key_exists('helperPathPrefix', $config)) {
                    $prefix = $config['helperPathPrefix'];
                }
                $this->addHelperPath($config['helperPath'], $prefix);
            }
        }
        
        $loader = new Ano_ZFTwig_Loader_FileLoader($viewPaths);
        $twig = new Ano_ZFTwig_Environment($this, $loader, $options);
        $twig->addExtension(new Twig_Extension_Escaper(true));
        $twig->addExtension(new Ano_ZFTwig_Extension_HelperExtension());

        $this->setEngine($twig);
    }

    /**
     * Add to the stack of view paths.
     *
     * @param string $path The directory to add
     * @return Ano_ZFTwig_View
     */
    public function addViewPath($path)
    {
        $this->twig->getLoader()->addPath($path);
        return $this;
    }

    /**
     * Reset the stack of view paths.
     *
     * @param string|array $viewPaths The directory (-ies) to set as the path.
     * @return Ano_ZFTwig_View
     */
    public function setViewPaths($viewPaths)
    {
        $this->twig->getLoader()->setPaths($viewPaths);
        return $this;
    }

    /**
     * Returns an array of all currently set view paths.
     *
     * @return array
     */
    public function getViewPaths()
    {
        return $this->twig->getLoader()->getPaths();
    }

    /**     
     * Sets the view engine
     *
     * @param Twig_Environment $twig The Twig engine
     * @return Ano_ZFTwig_View
     */
    public function setEngine(Twig_Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * @see Zend_View_Interface::getEngine()
     */
    public function getEngine()
    {
        return $this->twig;
    }

    /**
     * Add dynamically the path to the view file being rendered
     * and renders the template.
     *
     * @param string The view script to execute.
     */
    protected function _run()
    {
        $filename = func_get_arg(0);
        $path = dirname($filename);
        $templateFile = basename($filename);

        $this->twig->getLoader()->addPath($path);
        $template = $this->twig->loadTemplate($templateFile);
        $template->display(get_object_vars($this));
    }
}