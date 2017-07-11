<?php

/* @twig_path/asset1.html.twig */
class __TwigTemplate_7028932a4d474dde6608a3cecdbbf425b9a9c1b897038aad0c104d1666ef7710 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_8b3f169ecdb37b202c686137d9fef53d993110696842b125fb04addd2b4a8dea = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_8b3f169ecdb37b202c686137d9fef53d993110696842b125fb04addd2b4a8dea->enter($__internal_8b3f169ecdb37b202c686137d9fef53d993110696842b125fb04addd2b4a8dea_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@twig_path/asset1.html.twig"));

        // line 1
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        
        $__internal_8b3f169ecdb37b202c686137d9fef53d993110696842b125fb04addd2b4a8dea->leave($__internal_8b3f169ecdb37b202c686137d9fef53d993110696842b125fb04addd2b4a8dea_prof);

    }

    public function getTemplateName()
    {
        return "@twig_path/asset1.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  22 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ title }}", "@twig_path/asset1.html.twig", "/var/www/html/Tests/templates/asset1.html.twig");
    }
}
