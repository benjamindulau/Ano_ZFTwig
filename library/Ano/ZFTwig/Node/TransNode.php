<?php
/**
 * Compiles ZF translate view helper to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_TransNode extends Twig_Node
{

    public function __construct(Twig_NodeInterface $body, $lineno, $tag = null)
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile($compiler)
    {
        $compiler->addDebugInfo($this)
                 ->write('echo $this->env->getView()->translate(')
                 ->subcompile($this->getNode('body'))
                 ->raw(');');

        //$compiler->string($this->getNode('body'));

    }
}