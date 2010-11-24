<?php
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
    public function init($config = array())
    {
        $loader = new Ano_ZFTwig_Loader_FileLoader($this->getView()->getScriptPaths());
        $twig = new Ano_ZFTwig_Environment($this->getView(), $loader, $config);
        $twig->addExtension(new Twig_Extension_Escaper(true));
        $twig->addExtension(new Ano_ZFTwig_Extension_Helper());

        $this->setEnvironment($twig);
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
