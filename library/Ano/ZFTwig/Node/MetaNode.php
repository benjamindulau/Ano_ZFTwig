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
 * Compiles meta node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_MetaNode extends Twig_Node
{
    protected $contentKey;

    public function __construct(Twig_Node_Expression $options, $lineno, $tag = null)
    {
        parent::__construct(array('options' => $options), array(), $lineno, $tag);
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
        $name = $options->hasNode('name') ? $options->getNode('name') : false;
        $httpEquiv = $options->hasNode('http-equiv') ? $options->getNode('http-equiv') : false;
        $content = $options->hasNode('content') ? $options->getNode('content') : '';
        $offset = $options->hasNode('offset') ? $options->getNode('offset') : false;

        $compiler
            ->addDebugInfo($this)
            ->write("\$this->env->getView()->headMeta()->");

        $method = '';
        $key = null;
        if (false === $offset) {
            if (false !== $name) {
                $method = ($mode == 'prepend') ? 'prependName' : 'appendName';
                $key = $name;
            }
            else if (false !== $httpEquiv) {
                $method = ($mode == 'prepend') ? 'prependHttpEquiv' : 'appendHttpEquiv';
                $key = $httpEquiv;
            }

            $compiler
                ->write("$method(");
        }
        else {
            if (false !== $name) {
                $method = 'offsetSetName';
                $key = $name;
            }
            else if (false !== $httpEquiv) {
                $method = 'offsetSetHttpEquiv';
                $key = $httpEquiv;
            }
            
            $compiler
                ->write("$method(")
                ->subcompile($offset)
                ->raw(', ');
        }

        $compiler
            ->subcompile($key)
            ->raw(', ')
            ->subcompile($content)
            ->raw(");\n");
    }
}
