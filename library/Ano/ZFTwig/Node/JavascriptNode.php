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
 * Compiles javascripts node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_JavascriptNode extends Twig_Node
{
    protected $contentKey;

    public function __construct(Twig_Node_Expression $expr, Twig_Node_Expression $options, $lineno, $tag = null)
    {
        parent::__construct(array('expr' => $expr, 'options' => $options), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $options = $this->getNode('options');
        $mode = $options->hasNode('mode') ? $options->getNode('mode')->getAttribute('value') : 'append';
        $type = $options->hasNode('type') ? $options->getNode('type')->getAttribute('value') : 'text/javascript';
        $attrs = $options->hasNode('attrs') ? $options->getNode('attrs') : false;
        $offset = $options->hasNode('offset') ? $options->getNode('offset') : false;

        $compiler
            ->addDebugInfo($this)
            ->write("\$this->env->getView()->headScript()->");

        if (false === $offset) {
            $method = ($mode == 'prepend') ? 'prependFile' : 'appendFile';
            $compiler
                ->write("$method(");
        }
        else {
            $compiler
                ->write('offsetSetFile(')
                ->subcompile($offset)
                ->raw(', ');
        }
//
        $compiler
            ->subcompile($this->getNode('expr'))
            ->raw(', ')
            ->string($type);

        if ($attrs) {
            $compiler
                ->raw(', ')
                ->subcompile($attrs);
        }

        $compiler->raw(");\n");
    }
}
