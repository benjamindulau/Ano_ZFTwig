Ano_ZFTwig
==========

Ano_ZFTwig allows the use of twig with Zend Framework 1.1x.

It contains:

* A Zend Application resource plugin
* A Zend View object
* A twig tag to invoke any ZF view helper
* Twig tags wrapper for some very used native ZF view helpers like url, headScript, headMeta, ...

The use of Zend_Layout is supported but is optional.

Ano_ZFTwig allows the use of multiple template engines.

Installation
============

* Add "Ano" package to your library folder
* Add the following line to your application.ini :

    autoloaderNamespaces[] = "Ano_"
    pluginPaths.Ano_Application_Resource = APPLICATION_PATH "/../library/Ano/Application/Resource"
	


Configuration
=============

The following is a example of Twig view configuration to put into your application.ini file :

    resources.view.engines.php.class = "Ano_View_Engine_Php"
    resources.view.engines.php.viewSuffix = "phtml"

    resources.view.engines.twig.class = "Ano_ZFTwig_View_Engine_TwigEngine"
    resources.view.engines.twig.viewSuffix = "twig"
    resources.view.engines.twig.isDefault = 1
    resources.view.engines.twig.options.charset = "utf-8"
    resources.view.engines.twig.options.strict_variables = 0
    resources.view.engines.twig.options.cache = APPLICATION_PATH "/../var/cache/twig"
    resources.view.engines.twig.options.auto_reload = 1
    resources.view.engines.twig.options.debug = 0
    resources.view.engines.twig.options.trim_blocks = 1
    resources.view.engines.twig.options.viewSuffix = twig

    resources.view.helperPath.My_View_Helper_ = "My/View/Helper"


If you use Zend_Layout :

    resources.layout.layout = "layout" 
    resources.layout.layoutPath = APPLICATION_PATH "/views/layouts"
	

Usage
=====

You have nothing to change into your controllers to render twig templates.
The view renderer will automatically render a twig template from files with the extension you previously configured (i.e index.twig).

For general usage, read the documentation from the official website : http://www.twig-project.org/documentation

You can use Zend_Layout with Twig but be aware that you won't be able to use twig templates inheritance with the layout, because in ZF, the view is rendered before the layout. So, some tags with depends on twig context won't work, like "block", "set".


Tags coming with Ano_ZFTwig
---------------------------

Here are the syntaxes for the twig tags coming with Ano_ZFTwig

* Invoke any view helper :

        {% hlp 'myHelper' with ['arg1', ['key1': 'val1', 'key2': 'val2'], 'arg3'] %}
	
    (i.e. {% hlp 'url' with [['controller': 'index', 'action': 'my-action'], 'my_route'] %} )
	
* Adding a javascript file to the stack :

        {% javascript 'js/blog.js' %}
	
* Rendering javascript html tags (i.e. in the head section) :

        {% javascripts %}
	
* Adding a stylesheet link to the stack :

        {% stylesheet 'css/blog.css' with ['media': 'screen'] %}

* Rendering stylesheet links (i.e. in the head section) :

        {% stylesheets %}
	
* Adding a meta http-equiv to the stack :

        {% metaHttpEquiv 'Content-Type' with 'text/html; charset=utf-8' %}
	
* Adding a regular meta to the stack :
	
        {% metaName 'description' with 'My super website SEO description' %}
	
* Rendering meta tags (i.e. in the head section) :

        {% metas %}
	
* Generate an url from a route :

        {% route 'my_route' with ['id': post.id] %}

* Include layout section :

        {% layout 'content' %} (= <?php echo $this->layout()->content; ?>

* Setting a placeholder :

        {% holder 'titleh1' with 'My super title' %}

* Displaying a placeholder :
    
        {% holder 'titleh1' %}

* Translate a message

        {% trans 'message' %}
        {% metaName 'description' with 'My message'|trans %}


Usage example
-------------

### Without Zend_Layout and by using templates inheritance.

* layout.twig

        <head>
            <title>{% block title 'Default title' %}</title>
            {% block metas %}
                {% metaHttpEquiv 'Content-Type' with 'text/html; charset=utf-8' %}
            {% endblock %}
            {% block javascripts %}
                {% javascript 'js/jquery.js' %}
            {% endblock %}
            {% block stylesheets %}
                {% stylesheet 'css/layout.css' %}
            {% endblock %}

            {% metas %}
            {% javascripts %}
            {% stylesheets %}
        </head>
        <body>
            <h1>{% block 'title1' 'Default Title' %}</h1>
            {% block content %}{% endblock %}
        </body>

* twig-help.twig

        {% extends 'layouts/layout.twig' %}

        {% block title 'Anonymation - Twig for Zend Framework' %}

        {% block metas %}
            {% parent %}
            {% metaName 'description' with 'My super twig description for SEO' %}
        {% endblock %}
        {% block javascripts %}
            {% parent %}
            {% javascript 'js/twig.js' %}
        {% endblock %}
        {% block stylesheets %}
            {% parent %}
            {% stylesheet 'css/twig.css' %}
        {% endblock %}

        {% block 'title1' 'Some help about Twig' %}

        {% block content %}
            <div id="more-information">
                <p>
                    Helpful Links: <br />
                    <a href="http://www.twig-project.org/documentation">Twig documentation</a> |
                    <a href="http://github.com/benjamindulau/Ano_ZFTwig">Ano_ZFTwig source code</a>
                </p>
            </div>
            <a href="{% route 'default' with ['controller': 'index', 'action': 'index'] %}">
                &lt; Back to homepage
            </a>
        {% endblock %}


### With Zend_Layout

* layout.twig :

        <head>
            {% title %}
            {% metaHttpEquiv 'Content-Type' with 'text/html; charset=utf-8' %}
            {% javascript 'js/jquery.js' with ['mode': 'prepend'] %}
            {% stylesheet 'css/layout.css' with ['mode': 'prepend'] %}
            <base href="{% hlp 'serverUrl' %}/{% hlp 'baseUrl' %}" />
            {% metas %}
            {% javascripts %}
            {% stylesheets %}
        </head>
        <body>
            <h1>{% holder 'title1' %}</h1>
            {% layout 'content' %}
        </body>

* twig-help.twig :

        {# layout override #}

        {% headTitle 'Anonymation - Twig for Zend Framework' %}
        {% metaName 'description' with 'My super twig description for SEO' %}
        {% javascript 'js/twig.js' %}
        {% holder 'title1' with 'Some help about Twig' %}

        {# content #}
           <div id="more-information">
                <p>
                    Helpful Links: <br />
                    <a href="http://www.twig-project.org/documentation">Twig documentation</a> |
                    <a href="http://github.com/benjamindulau/Ano_ZFTwig">Ano_ZFTwig source code</a>
                </p>
            </div>
            <a href="{% route 'default' with ['controller': 'index', 'action': 'index'] %}">
                &lt; Back to homepage
            </a>