<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ViewRendererTest.php 21141 2010-02-23 06:09:18Z ralph $
 */

// Call Zend_Controller_Action_Helper_ViewRendererTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Controller_Action_Helper_ViewRendererTest::main");
}

require_once 'Ano/Controller/Action/Helper/ViewRenderer.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Request/Http.php';
require_once 'Zend/Controller/Response/Http.php';
require_once 'Zend/Filter/Inflector.php';
require_once 'Ano/View.php';
require_once 'Ano/View/Engine/PhpEngine.php';

require_once dirname(__FILE__) . '/../../_files/modules/foo/controllers/IndexController.php';

/**
 * Test class for Zend_Controller_Action_Helper_ViewRenderer.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Controller
 * @group      Zend_Controller_Action
 * @group      Zend_Controller_Action_Helper
 */
class Ano_Controller_Action_Helper_ViewRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * Base path to controllers, views
     * @var string
     */
    public $basePath;

    /**
     * Front controller object
     * @var Zend_Controller_Front
     */
    public $front;

    /**
     * ViewRenderer helper
     * @var Zend_Controller_Action_Helper_ViewRenderer
     */
    public $helper;

    /**
     * Request object
     * @var Zend_Controller_Request_Http
     */
    public $request;

    /**
     * Response object
     * @var Zend_Controller_Response_Http
     */
    public $response;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Controller_Action_Helper_ViewRendererTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        $this->basePath = realpath(dirname(__FILE__) . str_repeat(DIRECTORY_SEPARATOR . '..', 2));
        $this->request  = new Zend_Controller_Request_Http();
        $this->response = new Zend_Controller_Response_Http();
        $this->front    = Zend_Controller_Front::getInstance();
        $this->front->resetInstance();
        $this->front->addModuleDirectory($this->basePath . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'modules')
                    ->setRequest($this->request)
                    ->setResponse($this->response);

        $this->helper   = new Ano_Controller_Action_Helper_ViewRenderer();
        Zend_Controller_Action_HelperBroker::addHelper($this->helper);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
        Zend_Controller_Action_HelperBroker::resetHelpers();
    }

    public function testGetRendererWithDefaultTemplateEngine()
    {
        $this->request->setModuleName('foo')
                      ->setControllerName('index');
        $controller = new Foo_IndexController($this->request, $this->response, array());
        $this->helper->setActionController($controller);

        $template = $this->helper->getRendererSwitcher('index');
        $this->assertEquals('php', $template);
    }

    public function testGetRendererWithTwigTemplateEngine()
    {
        $this->request->setModuleName('foo')
                      ->setControllerName('index');
        $controller = new Foo_IndexController($this->request, $this->response, array());
        $this->helper->setActionController($controller);

        $template = $this->helper->getRendererSwitcher('bar');
        $this->assertEquals('twig', $template);
    }

    public function testGetRendererWithTwigTemplateEngineWithOtherPossibleSyntax()
    {
        $this->request->setModuleName('foo')
                      ->setControllerName('index');
        $controller = new Foo_IndexController($this->request, $this->response, array());
        $this->helper->setActionController($controller);

        $template = $this->helper->getRendererSwitcher('baz');
        $this->assertEquals('twig', $template);
    }

    public function testPredispatchWithTwigTemplateEngine()
    {
        $this->request->setModuleName('foo')
                      ->setControllerName('index')
                      ->setActionName('bar');
        $controller = new Foo_IndexController($this->request, $this->response, array());
        $this->helper->setActionController($controller);
        $view = new Ano_View();
        $engine = new Ano_View_Engine_PhpEngine($view);
        $view->addTemplateEngine('twig', $engine);
        $this->helper->setView($view);

        $this->helper->preDispatch();
        $this->assertSame($engine, $this->helper->view->getTemplateEngine());
    }
}