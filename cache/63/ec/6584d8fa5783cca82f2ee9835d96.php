<?php

/* login.html.twig */
class __TwigTemplate_63ec6584d8fa5783cca82f2ee9835d96 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("default.html.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "default.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "  
\t";
        // line 5
        if ($this->env->getExtension('security')->isGranted("ROLE_ADMIN")) {
            // line 6
            echo "\t\t<div class=\"alert alert-success\">
\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
\t\t\t<p>Welcome <strong>";
            // line 8
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "security"), "token"), "user"), "username"), "html", null, true);
            echo "!</strong></p>
\t\t</div>
\t";
        } else {
            // line 11
            echo "\t\t\t<form action=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("login_check"), "html", null, true);
            echo "\" method=\"post\">
\t\t\t\t\t<p>
\t\t\t\t\t\t<label for=\"username\">Username: </label>
\t\t\t\t\t\t<input id=\"username\" type=\"text\" name=\"_username\" value=\"";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["last_username"]) ? $context["last_username"] : $this->getContext($context, "last_username")), "html", null, true);
            echo "\">
\t\t\t\t\t</p>
\t\t\t\t\t<p>
\t\t\t\t\t\t<label for=\"password\">Password: </label>
\t\t\t\t\t\t<input id=\"password\" type=\"password\" name=\"_password\">
\t\t\t\t\t</p>
\t\t\t\t\t<p><input class=\"btn btn-small btn-primary\" type=\"submit\" value=\"Log in\"></p>
\t\t\t\t\t";
            // line 21
            if ((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error"))) {
                // line 22
                echo "\t\t\t\t\t\t<div class=\"alert alert-error\">
\t\t\t\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
\t\t\t\t\t\t\t<strong>Error!</strong> ";
                // line 24
                echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
                echo ".
\t\t\t\t\t\t</div>
\t\t\t\t\t";
            }
            // line 27
            echo "\t\t\t</form>
\t";
        }
        // line 29
        echo "
";
    }

    public function getTemplateName()
    {
        return "login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 29,  75 => 27,  69 => 24,  65 => 22,  63 => 21,  53 => 14,  46 => 11,  40 => 8,  36 => 6,  34 => 5,  31 => 4,  28 => 3,);
    }
}
