<?php

/* default.html.twig */
class __TwigTemplate_41173a828254e6349cfa18358d32738b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'description' => array($this, 'block_description'),
            'author' => array($this, 'block_author'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'content' => array($this, 'block_content'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
\t<title>Samuca Fashion</title>
\t
\t<meta charset=\"UTF-8\">
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t<meta name=\"description\" content=\"";
        // line 8
        $this->displayBlock('description', $context, $blocks);
        echo "\">
\t<meta name=\"author\" content=\"";
        // line 9
        $this->displayBlock('author', $context, $blocks);
        echo "\">
\t
\t<link rel=\"shortcut icon\" href=\"/assets/ico/favicon.ico\" />
\t<link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"/assets/ico/apple-touch-icon-114-precomposed.png\">
\t<link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"/assets/ico/apple-touch-icon-72-precomposed.png\">
\t<link rel=\"apple-touch-icon-precomposed\" href=\"/assets/ico/apple-touch-icon-57-precomposed.png\">
\t
\t<link rel=\"stylesheet\" href=\"/assets/css/bootstrap.min.css\">
\t<link rel=\"stylesheet\" href=\"/assets/css/bootstrap-responsive.min.css\">
\t<link rel=\"stylesheet\" href=\"/assets/css/theme.css\">\t
\t
\t<link rel=\"author\" href=\"/assets/humans.txt\" />
\t
\t<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
\t<!--[if lt IE 9]>
\t\t<script src=\"http://html5shim.googlecode.com/svn/trunk/html5.js\"></script>
\t<![endif]-->
\t
\t";
        // line 27
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 28
        echo "</head>
<body>
\t<header>
\t\t<div class=\"navbar\">
\t\t\t<div class=\"navbar-inner\">
\t\t\t\t<div class=\"container\">
\t\t\t\t\t<a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
\t\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t\t</a>
\t\t\t\t\t
\t\t\t\t\t<a class=\"brand\" href=\"/\">Samuca Fashion</a>
\t\t\t\t\t
\t\t\t\t\t<div class=\"nav-collapse\">
            ";
        // line 43
        if (((!array_key_exists("use_authentication", $context)) || $this->env->getExtension('security')->isGranted("ROLE_ADMIN"))) {
            // line 44
            echo "              <ul class=\"nav\">            
              ";
            // line 45
            if ((array_key_exists("menu", $context) && twig_length_filter($this->env, (isset($context["menu"]) ? $context["menu"] : $this->getContext($context, "menu"))))) {
                // line 46
                echo "                ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["menu"]) ? $context["menu"] : $this->getContext($context, "menu")));
                foreach ($context['_seq'] as $context["_key"] => $context["entity"]) {
                    // line 47
                    echo "                  <li>
                    <a href=\"";
                    // line 48
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["entity"]) ? $context["entity"] : $this->getContext($context, "entity")), "path"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute((isset($context["entity"]) ? $context["entity"] : $this->getContext($context, "entity")), "name")), "html", null, true);
                    echo "</a>
                  </li>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entity'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 51
                echo "              ";
            }
            // line 52
            echo "              </ul>
              ";
            // line 53
            if ((array_key_exists("use_authentication", $context) && (isset($context["use_authentication"]) ? $context["use_authentication"] : $this->getContext($context, "use_authentication")))) {
                // line 54
                echo "              <ul class=\"nav pull-right\">
                <li>
                  <p><a class=\"btn btn-small\" href=\"";
                // line 56
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("logout"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Log out"), "html", null, true);
                echo "</a></p>
                </li:
              </ul>
              ";
            }
            // line 60
            echo "            ";
        }
        // line 61
        echo "\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t
\t\t<div class=\"logo pull-right container-fluid\">
\t\t\t<a href=\"javascript:void(0);\"><img src=\"/assets/img/project-logo.png\"></a>
\t\t</div>
\t</header>
\t
\t<div class=\"container-fluid\">
\t\t";
        // line 72
        $this->displayBlock('content', $context, $blocks);
        // line 73
        echo "\t</div>
\t
\t<footer class=\"container-fluid pull-right\"\">
\t\t<a href=\"javascript:void(0);\"><img src=\"/assets/img/vendor-logo.png\"></a>
\t\t<p>&copy; Samuca 2013</p>
\t</footer>
\t
\t<script src=\"/assets/js/jquery.min.js\"></script>
\t<script src=\"/assets/js/bootstrap.min.js\"></script>
\t
  ";
        // line 83
        $this->displayBlock('javascripts', $context, $blocks);
        // line 84
        echo "\t
</body>
</html>";
    }

    // line 8
    public function block_description($context, array $blocks = array())
    {
    }

    // line 9
    public function block_author($context, array $blocks = array())
    {
    }

    // line 27
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 72
    public function block_content($context, array $blocks = array())
    {
    }

    // line 83
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "default.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  182 => 83,  177 => 72,  172 => 27,  167 => 9,  162 => 8,  156 => 84,  154 => 83,  142 => 73,  140 => 72,  127 => 61,  124 => 60,  115 => 56,  111 => 54,  109 => 53,  106 => 52,  103 => 51,  92 => 48,  89 => 47,  84 => 46,  82 => 45,  79 => 44,  77 => 43,  60 => 28,  58 => 27,  37 => 9,  33 => 8,  24 => 1,);
    }
}
