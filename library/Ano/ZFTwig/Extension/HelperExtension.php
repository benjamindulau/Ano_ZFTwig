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
class Ano_ZFTwig_Extension_HelperExtension extends Twig_Extension
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
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('javascript', '<js> [with <arguments:array>]', 'headScript', '%mode%File'),

            // {% javascripts %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('javascripts', '', 'headScript', 'render'),

            // {% stylesheet 'css/blog.css' with ['media': 'screen'] %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('stylesheet', '<css> [with <arguments:array>]', 'headLink', '%mode%Stylesheet'),

            // {% stylesheets %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('stylesheets', '', 'headLink', 'render'),

            // {% metaName 'description' 'My super website SEO description' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metaName', '<metaName> [with <constant>]', 'headMeta', '%mode%Name'),

            // {% metaHttpEquiv 'Content-Type' 'text/html; charset=utf-8' %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metaHttpEquiv', '<metaHttpEquiv> [with <constant>]', 'headMeta', '%mode%HttpEquiv'),

            // {% metas %}
            new Ano_ZFTwig_TokenParser_DefaultHelperTokenParser('metas', '', 'headMeta', 'render'),

            // {% route 'my_route' with ['id': post.id] %}
            new Ano_ZFTwig_TokenParser_RouteTokenParser(),

            // {% hlp 'helper' with [with <arguments:array>] %}
            new Ano_ZFTwig_TokenParser_HelperTokenParser(),

            // {% layout 'content' %}
            new Ano_ZFTwig_TokenParser_LayoutTokenParser(),

            // {% holder 'pageTitle' %}
            // {% holder 'pageTitle' with 'My wonderful web page' %}
            new Ano_ZFTwig_TokenParser_HolderTokenParser(),
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