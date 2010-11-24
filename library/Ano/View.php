<?php
/**
 * Concrete class for handling view scripts.
 * Support multiple template engines. If none, render phtml
 *
 * @category   Ano
 * @package    Ano_View
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_View extends Zend_View_Abstract
{

    /**
     * @var array Array Template engines list, key-value pairs
     */
    protected $templateEngines = array();

    /**
     * @var string Key in $templateEngines of the default engine
     */
    protected $defaultTemplateEngine = null;
    

    /**
     * @param string $key Set the key in $templateEngines of the
     *                    default Engine.
     * @return Ano_View
     */
    public function setDefaultTemplateEngine($key)
    {
        $this->defaultTemplateEngine = $key;
        return $this;
    }

    /**
     * @return Ano_View_Engine_Interface
     */
    public function getDefaultTemplateEngine()
    {
        return $this->defaultTemplateEngine;
    }

    /**
     * Checks if the template engine with key $key exists
     * in $templateEngines.
     *
     * @param string $key
     * @return bool
     */
    public function hasTemplateEngine($key)
    {
        return array_key_exists($key, $this->templateEngines);
    }

    /**
     * Gets the template engine instance with key $key
     *
     * @param string $key the registered engine key
     * @return Ano_View_Engine_Interface
     */
    public function getTemplateEngine($key)
    {
        if ($this->hasTemplateEngine($key)) {
            return $this->templateEngines[$key];
        }

        throw new Zend_View_Exception(sprintf('Template engine "%s" not found', $key));
    }

    /**
     * Adds a template instance with key $key into
     * $templateEngines.
     *
     * @param string                    $key
     * @param Ano_View_Engine_Interface $templateEngine
     * @return Ano_View
     */
    public function addTemplateEngine($key, Ano_View_Engine_Interface $templateEngine)
    {
        $this->templateEngines[$key] = $templateEngine;
        return $this;
    }

    /**
     * Processes a view script and returns the output.
     * Hack for Zend_Layout => force the view suffix
     * depending on template engine
     *
     * @param string $name The script name to process.
     * @return string The script output.
     */
    public function render($name)
    {
        // hack for Zend_Layout which has its own view suffix handling
        $engine = $this->getDefaultTemplateEngine();
        $filename = $name;
        if ($this->hasTemplateEngine($engine)) {
            $suffix = $this->getTemplateEngine($engine)->getViewSuffix();
            $fileParts = pathinfo($name);
            $filename = str_replace('.' . $fileParts['extension'], '.' . $suffix, $name);
        }
        
        return parent::render($filename);
    }

    /**
     * Tells to the default template engine to render the template
     * if one is set.
     * Include the file otherwise (default behaviour)
     *
     * @param string The script filename to render
     */
    public function _run()
    {
        $filename = func_get_arg(0);
        $engine = $this->getDefaultTemplateEngine();
        if ($this->hasTemplateEngine($engine)) {
            $this->getTemplateEngine($engine)->render($filename, get_object_vars($this));
        }
        else {
            include $filename;
        }
    }
}