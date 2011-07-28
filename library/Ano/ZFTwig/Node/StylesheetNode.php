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
 * Compiles stylesheet node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_StylesheetNode extends Twig_Node
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
        $media = $options->hasNode('media') ? $options->getNode('media')->getAttribute('value') : 'screen';
        $attrs = $options->hasNode('attrs') ? $options->getNode('attrs') : false;
        $offset = $options->hasNode('offset') ? $options->getNode('offset') : false;
        $conditional = $options->hasNode('conditional') ? $options->getNode('conditional')->getAttribute('value') : false;

        $compiler
            ->addDebugInfo($this)
            ->write("\$this->env->getView()->headLink()->");

        if (false === $offset) {
            $method = ($mode == 'prepend') ? 'prependStylesheet' : 'appendStylesheet';
            $compiler
                ->write("$method(");
        }
        else {
            $compiler
                ->write('offsetSetStylesheet(')
                ->subcompile($offset)
                ->raw(', ');
        }

        $compiler
            ->subcompile($this->getNode('expr'))
            ->raw(', ')
            ->string($media)
            ->raw(', ')
            ->string($conditional);

        if ($attrs) {
            $compiler
                ->raw(', ')
                ->subcompile($attrs);
        }

        $compiler->raw(");\n");
    }
}
