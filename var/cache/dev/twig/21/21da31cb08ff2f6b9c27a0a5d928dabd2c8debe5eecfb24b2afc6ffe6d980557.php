<?php

/* default/index.html.twig */
class __TwigTemplate_e07e524cb0d19c8ec59dab60ea1519ede4ddc106a84438bcdb6f0672ed0ae613 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.html.twig", "default/index.html.twig", 1);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "default/index.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "default/index.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 4
        echo "    <div class=\"position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light\">
        <div class=\"col-md-5 p-lg-5 mx-auto my-5\">
            <h1 class=\"display-4 font-weight-normal\">Punny headline</h1>
            <p class=\"lead font-weight-normal\">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple's marketing pages.</p>
            <a class=\"btn btn-outline-secondary\" href=\"#\">Coming soon</a>
        </div>
        <div class=\"product-device shadow-sm d-none d-md-block\"></div>
        <div class=\"product-device product-device-2 shadow-sm d-none d-md-block\"></div>
    </div>

    <div class=\"d-md-flex flex-md-equal w-100 my-md-3 pl-md-3\">
        <div class=\"bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden\">
            <div class=\"my-3 py-3\">
                <h2 class=\"display-5\">Another headline</h2>
                <p class=\"lead\">And an even wittier subheading.</p>
            </div>
            <div class=\"bg-light shadow-sm mx-auto\" style=\"width: 80%; height: 300px; border-radius: 21px 21px 0 0;\"></div>
        </div>
        <div class=\"bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden\">
            <div class=\"my-3 p-3\">
                <h2 class=\"display-5\">Another headline</h2>
                <p class=\"lead\">And an even wittier subheading.</p>
            </div>
            <div class=\"bg-dark shadow-sm mx-auto\" style=\"width: 80%; height: 300px; border-radius: 21px 21px 0 0;\"></div>
        </div>
    </div>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "default/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 4,  44 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'base.html.twig' %}

{% block body %}
    <div class=\"position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light\">
        <div class=\"col-md-5 p-lg-5 mx-auto my-5\">
            <h1 class=\"display-4 font-weight-normal\">Punny headline</h1>
            <p class=\"lead font-weight-normal\">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple's marketing pages.</p>
            <a class=\"btn btn-outline-secondary\" href=\"#\">Coming soon</a>
        </div>
        <div class=\"product-device shadow-sm d-none d-md-block\"></div>
        <div class=\"product-device product-device-2 shadow-sm d-none d-md-block\"></div>
    </div>

    <div class=\"d-md-flex flex-md-equal w-100 my-md-3 pl-md-3\">
        <div class=\"bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden\">
            <div class=\"my-3 py-3\">
                <h2 class=\"display-5\">Another headline</h2>
                <p class=\"lead\">And an even wittier subheading.</p>
            </div>
            <div class=\"bg-light shadow-sm mx-auto\" style=\"width: 80%; height: 300px; border-radius: 21px 21px 0 0;\"></div>
        </div>
        <div class=\"bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden\">
            <div class=\"my-3 p-3\">
                <h2 class=\"display-5\">Another headline</h2>
                <p class=\"lead\">And an even wittier subheading.</p>
            </div>
            <div class=\"bg-dark shadow-sm mx-auto\" style=\"width: 80%; height: 300px; border-radius: 21px 21px 0 0;\"></div>
        </div>
    </div>
{% endblock %}
", "default/index.html.twig", "/Users/simo/Desktop/PermutationESPRIT/app/Resources/views/default/index.html.twig");
    }
}
