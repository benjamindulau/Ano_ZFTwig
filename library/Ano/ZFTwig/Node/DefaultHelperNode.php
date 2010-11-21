<?php
/**
 * Compiles native ZF's view helpers tags to PHP.
 * @see Ano_ZFTwig_Extension_Helper
 *
 * @package     Ano_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Node_DefaultHelperNode extends Twig_Node
{
    public function __construct($helper, $method, Twig_Node_Expression_Array $values, $lineno, $tag = null)
    {
        parent::__construct(array('values' => $values), array('helper' => $helper, 'method' => $method), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile($compiler)
    {
        $helper = $this->getAttribute('helper');
        $method = $this->getAttribute('method');

        $compiler->addDebugInfo($this);
        if ('render' == $method) {
            $compiler->write('echo $this->env->getView()->')
                     ->raw($helper)
                     ->raw('();');
        }
        else {
            $compiler->write('$this->env->getView()->')
                     ->raw($helper);

            if ('' != $method) {
                $compiler->raw('()->')
                         ->raw($method);
            }
            $compiler->raw('(');

            foreach ($this->getNode('values') as $i => $value) {
                $compiler->subcompile($value);
                if ($i !== count($this->getNode('values')) - 1) {
                    $compiler->raw(', ');
                }
            }
            
            $compiler->raw(");");

        }
    }
}
