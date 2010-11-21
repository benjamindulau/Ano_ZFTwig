<?php
/**
 * ZF Helper Extension for Twig's Zend Framework Integration
 * Handles some native ZF view helpers
 *
 * Inspired from Symfony 2 Twig Bundle
 *
 * @package     Ano_ZFTwig
 * @subpackage  Extension
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Extension_Helper extends Twig_Extension
{
    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(            
            // {% headTitle 'My page title' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('headTitle', '<title>', 'headTitle', ''),

            // {% title %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('title', '', 'headTitle', 'render'),

            // {% javascript 'js/blog.js' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('javascript', '<js> [with <arguments:array>]', 'headScript', 'appendFile'),

            // {% javascripts %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('javascripts', '', 'headScript', 'render'),

            // {% stylesheet 'css/blog.css' with ['media': 'screen'] %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('stylesheet', '<css> [with <constant>]', 'headLink', 'appendStylesheet'),

            // {% stylesheets %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('stylesheets', '', 'headLink', 'render'),

            // {% metaName 'description' 'My super website SEO description' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metaName', '<metaName> [with <constant>]', 'headMeta', 'appendName'),

            // {% metaHttpEquiv 'Content-Type' 'text/html; charset=utf-8' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metaHttpEquiv', '<metaHttpEquiv> [with <constant>]', 'headMeta', 'appendHttpEquiv'),

            // {% metas %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metas', '', 'headMeta', 'render'),

            // {% url 'my_route' with ['id': post.id] %}
            new Ano_ZFTwig_TokenParser_UrlTokenParser(),

            // {% hlp 'helper' with [with <arguments:array>] %}
            new Ano_ZFTwig_TokenParser_HelperTokenParser(),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'helper';
    }
}