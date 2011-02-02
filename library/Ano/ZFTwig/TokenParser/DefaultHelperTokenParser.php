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
 * Wrapper for native Zend Framework 1.1x view helpers
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  TokenParser
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_TokenParser_DefaultHelperTokenParser extends Twig_SimpleTokenParser
{
    protected $helper;
    protected $method;
    protected $grammar;
    protected $tag;

    public function __construct($tag = null, $grammar = null, $helper = null, $method = null)
    {
        $this->tag = $tag;
        $this->grammar = $grammar;
        $this->helper = $helper;
        $this->method = $method;
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Gets the grammar as an object or as a string.
     *
     * @return string|Twig_Grammar A Twig_Grammar instance or a string
     */
    protected function getGrammar()
    {
        return $this->grammar;
    }

    protected function getNode(array $values, $line)
    {
        return new Ano_ZFTwig_Node_DefaultHelperNode(
                $this->helper,
                $this->method,
                new Twig_Node_Expression_Array($this->getNodeValues($values), $line),
                $line
        );
    }
}
