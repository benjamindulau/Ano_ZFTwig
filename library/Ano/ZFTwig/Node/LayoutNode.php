<?php
/**
 * Compiles url node to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_LayoutNode extends Twig_Node
{
    protected $contentKey;

    public function __construct($contentKey, $lineno, $tag = null)
    {
        $this->contentKey = $contentKey;
        parent::__construct(array(), array(), $lineno, $tag);
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
            ->write('echo $this->env->getView()->layout()->')
            ->raw($this->contentKey)
            ->raw(';');
    }
}
