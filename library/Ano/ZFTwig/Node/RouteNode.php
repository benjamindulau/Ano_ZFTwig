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
 * Compiles route node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_RouteNode extends Twig_Node
{
    public function __construct(Twig_NodeInterface $route, Twig_Node_Expression $attributes = null, $lineno, $tag = null)
    {
        parent::__construct(array('route' => $route, 'route_attributes' => $attributes), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile($compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('echo $this->env->getView()->url(');

        $attr = $this->getNode('route_attributes');
        if ($attr) {
            $compiler->subcompile($attr);
        } else {
            $compiler->raw('array()');
        }
        $compiler->raw(', ')
            ->subcompile($this->getNode('route'))
            ->raw(");")
        ;
    }
}
