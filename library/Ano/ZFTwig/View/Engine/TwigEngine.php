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
 * Template Engine for Twig
 *
 * @package    Ano_ZFTwig
 * @subpackage View
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_View_Engine_TwigEngine extends Ano_View_Engine_Abstract
{
    /**
     * @var Twig_Environment
     */
    protected $environment = null;

    /**
     * @var string
     */
    protected $viewSuffix = 'twig';

    /**
     * @param array $config Configuration key-value pairs.
     */
    public function init(array $config = array())
    {
        $options = array();
        if (array_key_exists('options', $config) && is_array($config['options'])) {
            $options = $config['options'];
        }
        
        $loader = new Ano_ZFTwig_Loader_FileLoader($this->getView()->getScriptPaths());
        $twigEnvironment = new Ano_ZFTwig_Environment($this->getView(), $loader, $options);

        if (array_key_exists('auto_escape', $options)) {
            $twigEnvironment->addExtension(new Twig_Extension_Escaper((bool)$options['auto_escape']));
        }

        if (array_key_exists('extensions', $config) && is_array($config['extensions'])) {
            foreach($config['extensions'] as $extension) {
                $extensionClass = $extension['class'];
                if (!class_exists($extensionClass)) {
                    throw new Ano_ZFTwig_View_Engine_TwigEngine_Exception('Extension class doesn\'t exist: ' . $extension['class']);
                }
                if (array_key_exists('options', $extension) && is_array($extension['options'])) {
                    $twigEnvironment->addExtension(new $extensionClass($extension['options']));
                }
                else {
                    $twigEnvironment->addExtension(new $extensionClass());
                }
            }
        }

        // Globals
        $twigEnvironment->addGlobal('zf', $twigEnvironment->getView());

        $globalsClass = '';
        $globalsName = '';
        if (array_key_exists('globals', $config) && is_array($config['globals'])) {
            if (array_key_exists('class', $config['globals'])) {
                $globalsClass = $config['globals']['class'];

                if (!array_key_exists('name', $config['globals']) || empty($config['globals']['name'])) {
                    throw new Ano_ZFTwig_View_Engine_TwigEngine_Exception('You must provide a name for globals (e.g. "app")');
                }
                $globalsName = $config['globals']['name'];
                
                if (!class_exists($globalsClass)) {
                    throw new Ano_ZFTwig_View_Engine_TwigEngine_Exception('Class for globals doesn\'t exist: ' . $globalsClass);
                }
            }
        }
        if ($globalsClass != '' && $globalsName != '') {
            $twigEnvironment->addGlobal($globalsName, new $globalsClass());
        }
        else {
            $twigEnvironment->addGlobal('app', new Ano_ZFTwig_GlobalVariables());
        }

        $this->setEnvironment($twigEnvironment);
    }

    /**
     * Sets the twig environment
     *
     * @param  Twig_Environment $environment
     * @return Ano_ZFTwig_View_Engine_TwigEngine
     */
    public function setEnvironment(Twig_Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return Twig_Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Renders the template
     *
     * @param string $template The script view filename (fullpath)
     * @param mixed  $vars     The vars to assign to the template
     */
    public function render($template, $vars = null)
    {
        $path = dirname($template);
        $templateFile = basename($template);

        $this->getEnvironment()->getLoader()->addPath($path);
        $template = $this->getEnvironment()->loadTemplate($templateFile);
        $template->display($vars);
    }
}
