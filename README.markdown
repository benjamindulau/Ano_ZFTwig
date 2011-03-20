Last changes
============
* Makes Ano_ZFTwig compatible with last twig stable version
* Makes better use of arrays syntaxes for consistency ([] for arrays, {} for hashes)
* Uses the new twig's functions system for consistency (uses the {{ }} tag to display something instead of a {% %} tag)
* Doesn't have hardcoded twig extensions adding anymore (except for Escaper). Extensions classes are explicitly definded into the configuration. Allows the user to add its own extensions.
* Adds an "auto_escape" option into configuration
* Adds support for twig global variables. Ano_ZFTwig_GlobalVariables is loaded by default, but this can be overriden by the user through the configuration file.


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

    resources.view.engines.php.class = "Ano_View_Engine_PhpEngine"
    resources.view.engines.php.viewSuffix = "phtml"

    resources.view.engines.twig.class = "Ano_ZFTwig_View_Engine_TwigEngine"
    resources.view.engines.twig.isDefault = 1
    resources.view.engines.twig.viewSuffix = "twig"    
    resources.view.engines.twig.options.charset = "utf-8"
    resources.view.engines.twig.options.strict_variables = 0
    resources.view.engines.twig.options.cache = APPLICATION_PATH "/../var/cache/twig"
    resources.view.engines.twig.options.auto_escape = 1
    resources.view.engines.twig.options.auto_reload = 1
    resources.view.engines.twig.options.debug = 0
    resources.view.engines.twig.options.trim_blocks = 1

    resources.view.engines.twig.extensions.helper.class = "Ano_ZFTwig_Extension_HelperExtension"
    resources.view.engines.twig.extensions.trans.class = "Ano_ZFTwig_Extension_TransExtension"
    ; Just add your own extensions there

    resources.view.engines.twig.globals.class = "My_Twig_Globals" ; Optional default to Ano_ZFTwig_GlobalVariables
    resources.view.engines.twig.globals.name = "app" ;the global variable name, default to "app"

    resources.view.helperPath.My_View_Helper_ = "My/View/Helper"


If you use Zend_Layout :

    resources.layout.layout = "layout" 
    resources.layout.layoutPath = APPLICATION_PATH "/views/layouts"
	

Usage
=====

You have nothing to change into your controllers to render twig templates.
The view renderer will automatically render a twig template from files with the extension you previously configured (i.e index.twig).

For general usage, read the documentation from the official website : http://www.twig-project.org/documentation

You can use Zend_Layout with Twig but be aware that you won't be able to use twig templates inheritance with the layout, because in ZF, the view is rendered before the layout. So, some tags which depends on twig context won't work, like "block", "set".

Global variables
----------------

Ano_ZFTwig comes with default twig global variables (can be overriden through config file).
It contains only two variables for now :

* app

        {% if app.environment == 'production' %}
            {# some stuff, e.g google analytics #}
        {% endif %}

* zf : returns the current view instance, can be used inside a {{ }} tags to display any helper return value

        {{ zf.myHelper() }}


Tags coming with Ano_ZFTwig
---------------------------

Here are the syntaxes for the twig tags coming with Ano_ZFTwig

* Invoke any view helper :

        {% hlp 'myHelper' with ['arg1', {'key1': 'val1', 'key2': 'val2'}, 'arg3'] %}

* Invoke any view helper which "display/returns" something :

	    {{ zf.myHelper('something') }}

* Adding a javascript file to the stack :

        {% javascript 'js/blog.js' %}
	
* Rendering javascript html tags (i.e. in the head section) :

        {{ javascripts() }}
	
* Adding a stylesheet link to the stack :

        {% stylesheet 'css/blog.css' with {'media': 'screen'} %}

* Rendering stylesheet links (i.e. in the head section) :

        {{ stylesheets() }}
	
* Adding a meta http-equiv to the stack :

        {% metaHttpEquiv 'Content-Type' with 'text/html; charset=utf-8' %}
	
* Adding a regular meta to the stack :
	
        {% metaName 'description' with 'My super website SEO description' %}
	
* Rendering meta tags (i.e. in the head section) :

        {{ metas() }}
	
* Generate an url from a route :

        {{ url('my_route', {'id': post.id}) %}

* Include layout section :

        {{ layoutBlock('content') }} (= <?php echo $this->layout()->content; ?>

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

            {{ metas() }}
            {{ javascripts() }}
            {{ stylesheets() }}
        </head>
        <body>
            <h1>{% block 'title1' 'Default Title' %}</h1>
            {{ block('content') }}
        </body>

* twig-help.twig

        {% extends 'layouts/layout.twig' %}

        {% block title 'Anonymation - Twig for Zend Framework' %}

        {% block metas %}
            {{ parent() }}
            {% metaName 'description' with 'My super twig description for SEO' %}
        {% endblock %}
        {% block javascripts %}
            {{ parent() }}
            {% javascript 'js/twig.js' %}
        {% endblock %}
        {% block stylesheets %}
            {{ parent() }}
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
            <a href="{{ url('default', {'controller': 'index', 'action': 'index'}) }}">
                &lt; Back to homepage
            </a>
        {% endblock content %}


### With Zend_Layout

* layout.twig :

        <head>
            {{ headTitle() }}
            {% metaHttpEquiv 'Content-Type' with 'text/html; charset=utf-8' %}
            {% javascript 'js/jquery.js' with {'mode': 'prepend'} %}
            {% stylesheet 'css/layout.css' with {'mode': 'prepend'} %}
            <base href="{{ zf.serverUrl() }}/{{ zf.baseUrl() }}" />
            {{ metas() }}
            {{ javascripts() }}
            {{ stylesheets() }}
        </head>
        <body>
            <h1>{{ block('title1') }}</h1>
            {{ layoutBlock('content') }}
        </body>

* twig-help.twig :

        {# layout override #}

        {% headTitle 'Anonymation - Twig for Zend Framework' %}
        {% metaName 'description' with 'My super twig description for SEO' %}
        {% javascript 'js/twig.js' %}
        {% block title1 'Some help about Twig' %}

        {# content #}
           <div id="more-information">
                <p>
                    Helpful Links: <br />
                    <a href="http://www.twig-project.org/documentation">Twig documentation</a> |
                    <a href="http://github.com/benjamindulau/Ano_ZFTwig">Ano_ZFTwig source code</a>
                </p>
            </div>
            <a href="{{ url('default', {'controller': 'index', 'action': 'index'}) }}">
                &lt; Back to homepage
            </a>