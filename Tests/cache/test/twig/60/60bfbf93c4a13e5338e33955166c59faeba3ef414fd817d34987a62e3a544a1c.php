<?php

/* @twig_path/asset2.html.twig */
class __TwigTemplate_cc2d9451f4efc3220a65cc33a20c0c7fae8888d9cf1710719ef4a0362ead9c9e extends Twig_Template
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
        $__internal_54292c8db228960941c434a50620074d4bfc71398fd02991c67f81f69bab3538 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_54292c8db228960941c434a50620074d4bfc71398fd02991c67f81f69bab3538->enter($__internal_54292c8db228960941c434a50620074d4bfc71398fd02991c67f81f69bab3538_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@twig_path/asset2.html.twig"));

        // line 1
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        
        $__internal_54292c8db228960941c434a50620074d4bfc71398fd02991c67f81f69bab3538->leave($__internal_54292c8db228960941c434a50620074d4bfc71398fd02991c67f81f69bab3538_prof);

    }

    public function getTemplateName()
    {
        return "@twig_path/asset2.html.twig";
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
        return new Twig_Source("{{ title }}", "@twig_path/asset2.html.twig", "/var/www/html/Tests/templates/asset2.html.twig");
    }
}
