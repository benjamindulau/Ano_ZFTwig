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
 * Compiles holder node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_HolderNode extends Twig_Node
{
    protected $placeholder;

    public function __construct($placeholder, $attributes, $lineno, $tag = null)
    {
        $this->placeholder = $placeholder;
        parent::__construct(array(), array('holder_attributes' => $attributes), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile($compiler)
    {
        $attributes = $this->getAttribute('holder_attributes');
        
        if ($attributes) {
            $compiler->addDebugInfo($this)
                     ->write('$this->env->getView()->placeholder(')
                     ->string($this->placeholder)
                     ->raw(')->set(')
                     ->subcompile($attributes)
                     ->raw(');');
        }
        else {
            $compiler->addDebugInfo($this)
                     ->write('echo $this->env->getView()->placeholder(')
                     ->string($this->placeholder)
                     ->raw(');');
        }
    }
}
