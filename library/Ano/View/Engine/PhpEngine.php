<?php
/**
 * Template Engine for phtml
 *
 * @package    Ano_View
 * @subpackage Engine
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_View_Engine_PhpEngine extends Ano_View_Engine_Abstract
{
    /**
     * @var string
     */
    protected $viewSuffix = 'phtml';

    /**
     * Whether or not to use streams to mimic short tags
     * @var bool
     */
    private $_useViewStream = false;

    /**
     * Whether or not to use stream wrapper if short_open_tag is false
     * @var bool
     */
    private $_useStreamWrapper = false;

    /**
     * @param array $config Configuration key-value pairs.
     */
    public function init($config = array())
    {
        $this->_useViewStream = (bool) ini_get('short_open_tag') ? false : true;
        if ($this->_useViewStream) {
            if (!in_array('zend.view', stream_get_wrappers())) {
                require_once 'Zend/View/Stream.php';
                stream_wrapper_register('zend.view', 'Zend_View_Stream');
            }
        }

        if (array_key_exists('useStreamWrapper', $config)) {
            $this->setUseStreamWrapper($config['useStreamWrapper']);
        }
    }

    /**
     * Set flag indicating if stream wrapper should be used if short_open_tag is off
     *
     * @param  bool $flag
     * @return Zend_View
     */
    public function setUseStreamWrapper($flag)
    {
        $this->_useStreamWrapper = (bool) $flag;
        return $this;
    }

    /**
     * Should the stream wrapper be used if short_open_tag is off?
     *
     * @return bool
     */
    public function useStreamWrapper()
    {
        return $this->_useStreamWrapper;
    }

    /**
     * Renders the template
     *
     * @param string $template The script view filename (fullpath)
     * @param mixed  $vars     The vars to assign to the template
     */
    public function render($template, $vars = null)
    {                
        if ($this->_useViewStream && $this->useStreamWrapper()) {
            include 'zend.view://' . $template;
        } else {
            include $template;
        }
    }

    /**
     * Wrapper for the Zend View escape method
     * (should be an helper but ZF team's decided otherwise :) )
     * @see Zend_View_Abstract::escape()
     *
     * @param mixed $var The output to escape
     * @return mixed The escaped value.
     */
    public function escape($var)
    {
        return $this->getView()->escape($var);
    }

    /**
     * Wrapper for magic calls to zend view helpers
     * @see Zend_View_Abstract::__call()
     *
     * @param string $name The helper name.
     * @param array $args The parameters for the helper.
     * @return string The result of the helper output.
     */
    public function __call($name, $args)
    {        
        return $this->getView()->__call($name, $args);
    }

    /**
     * Wrapper for magic calls to any vars of the view
     * @see Zend_View_Abstract::__get()
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {        
        return $this->getView()->$key;
    }

    /**
     * Wrapper for empty() and isset() inside templates
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->getView()->__isset($key);
    }
}
