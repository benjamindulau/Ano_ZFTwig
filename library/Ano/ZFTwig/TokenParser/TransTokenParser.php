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
 * Wrapper for translate Zend Framework 1.1x view helper
 * Syntax : {% trans "message" %}
 *
 * @package     Ano_ZFTwig
 * @subpackage  TokenParser
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_TokenParser_TransTokenParser extends Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param  Twig_Token $token A Twig_Token instance
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();        

        $body = null;
        if (!$stream->test(Twig_Token::BLOCK_END_TYPE)) {
            // {% trans "message" %}
            //$body = $stream->expect(Twig_Token::STRING_TYPE)->getValue();
            $body = $this->parser->getExpressionParser()->parseExpression();
        }

        if (null === $body) {
            // {% trans %}message{% endtrans %}
            $stream->expect(Twig_Token::BLOCK_END_TYPE);
            $body = $this->parser->subparse(array($this, 'decideTransFork'), true);
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Ano_ZFTwig_Node_TransNode($body, $lineno, $this->getTag());
    }

    public function decideTransFork($token)
    {
        return $token->test(array('endtrans'));
    }


    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'trans';
    }
}