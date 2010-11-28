<?php
/**
 * ViewRenderer
 * Supports template engine switching by annotation system
 * Syntax : @render php
 * 
 *
 * @package    Ano_Controller_Action
 * @subpackage Helper
 * @author     Mickael Perraud <perraud.mickael@orange.fr>
 */
class Ano_Controller_Action_Helper_ViewRenderer extends Zend_Controller_Action_Helper_ViewRenderer
{

    /**
     * Choose and affect template engine to use
     * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
     */
    public function preDispatch()
    {
        $controller = $this->getActionController();
        $request    = $this->getRequest();
        $action     = $request->getActionName();
        $rendererName = $this->getRendererSwitcher($action);
        $this->view->setTemplateEngine($rendererName);
    }

    /**
     * Search in annotations for template engine
     * @param string $action
     */
    public function getRendererSwitcher($action)
    {
        $controller = $this->getActionController();
        $dispatcher = $this->getFrontController()->getDispatcher();
        $action     = (string) $dispatcher->formatActionName($action);
        $rc = new ReflectionMethod($controller, $action);
        $renderer = $this->parseDocComment($rc->getDocComment(), 'render', 'php');
        return $renderer;
    }

    /**
     * Parse PHPDoc to find a special tag and associated value
     * @param string $string String to parse
     * @param string $tag Tag to search
     * @param string $default Default value if no tag found
     */
    public function parseDocComment($string, $tag, $default = '')
    {
        $matches = array();
        preg_match("/".$tag.":?\s+(.*)(\\r\\n|\\r|\\n)/U", $string, $matches);
        return (isset($matches[1])) ? trim($matches[1]) : $default;
    }
}
