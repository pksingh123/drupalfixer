<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/drupalfixer/templates/page.html.twig */
class __TwigTemplate_1b5609103a379cdf2aabaca9312303f2159f9154da588d767775b8d7cd03a22b extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'navbar' => [$this, 'block_navbar'],
            'main' => [$this, 'block_main'],
            'header' => [$this, 'block_header'],
            'sidebar_first' => [$this, 'block_sidebar_first'],
            'highlighted' => [$this, 'block_highlighted'],
            'help' => [$this, 'block_help'],
            'content' => [$this, 'block_content'],
            'sidebar_second' => [$this, 'block_sidebar_second'],
            'featuer_first' => [$this, 'block_featuer_first'],
            'featuer_second' => [$this, 'block_featuer_second'],
            'featuer_thired' => [$this, 'block_featuer_thired'],
            'featuer_fourth' => [$this, 'block_featuer_fourth'],
            'footer' => [$this, 'block_footer'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 54, "if" => 56, "block" => 57];
        $filters = ["escape" => 183, "split" => 185, "clean_class" => 62, "t" => 74];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'block'],
                ['escape', 'split', 'clean_class', 't'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 54
        $context["container"] = (($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", []), "fluid_container", [])) ? ("container-fluid") : ("container"));
        // line 56
        if (($this->getAttribute(($context["page"] ?? null), "navigation", []) || $this->getAttribute(($context["page"] ?? null), "navigation_collapsible", []))) {
            // line 57
            echo "  ";
            $this->displayBlock('navbar', $context, $blocks);
        }
        // line 94
        echo "
";
        // line 96
        $this->displayBlock('main', $context, $blocks);
        // line 164
        echo "\t";
        // line 165
        $context["featuer_classes"] = [0 => ((((($this->getAttribute(        // line 166
($context["page"] ?? null), "featuer_first", []) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-3") : ("")), 1 => ((((($this->getAttribute(        // line 167
($context["page"] ?? null), "featuer_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-12") : ("")), 2 => (((((twig_test_empty($this->getAttribute(        // line 168
($context["page"] ?? null), "featuer_first", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-12") : ("")), 3 => (((((twig_test_empty($this->getAttribute(        // line 169
($context["page"] ?? null), "featuer_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-12") : ("")), 4 => (((((twig_test_empty($this->getAttribute(        // line 170
($context["page"] ?? null), "featuer_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-12") : ("")), 5 => ((((($this->getAttribute(        // line 171
($context["page"] ?? null), "featuer_first", []) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-6") : ("")), 6 => ((((($this->getAttribute(        // line 172
($context["page"] ?? null), "featuer_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-6") : ("")), 7 => ((((($this->getAttribute(        // line 173
($context["page"] ?? null), "featuer_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-6") : ("")), 8 => (((((twig_test_empty($this->getAttribute(        // line 174
($context["page"] ?? null), "featuer_first", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-6") : ("")), 9 => (((((twig_test_empty($this->getAttribute(        // line 175
($context["page"] ?? null), "featuer_first", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-6") : ("")), 10 => (((((twig_test_empty($this->getAttribute(        // line 176
($context["page"] ?? null), "featuer_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-6") : ("")), 11 => (((((twig_test_empty($this->getAttribute(        // line 177
($context["page"] ?? null), "featuer_first", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-4") : ("")), 12 => ((((($this->getAttribute(        // line 178
($context["page"] ?? null), "featuer_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_second", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-4") : ("")), 13 => ((((($this->getAttribute(        // line 179
($context["page"] ?? null), "featuer_first", []) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_thired", []))) && $this->getAttribute(($context["page"] ?? null), "featuer_fourth", []))) ? ("col-sm-4") : ("")), 14 => ((((($this->getAttribute(        // line 180
($context["page"] ?? null), "featuer_first", []) && $this->getAttribute(($context["page"] ?? null), "featuer_second", [])) && $this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])))) ? ("col-sm-4") : (""))];
        // line 183
        echo "\t <section class=\"featuer ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo "\">
\t ";
        // line 185
        echo "\t ";
        $context["classes"] = twig_split_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["featuer_classes"] ?? null)), " ");
        // line 186
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "featuer_first", [])) {
            // line 187
            echo "        ";
            $this->displayBlock('featuer_first', $context, $blocks);
            // line 192
            echo "      ";
        }
        // line 193
        echo "\t  ";
        // line 194
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "featuer_second", [])) {
            // line 195
            echo "        ";
            $this->displayBlock('featuer_second', $context, $blocks);
            // line 200
            echo "      ";
        }
        // line 201
        echo "\t  ";
        // line 202
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "featuer_thired", [])) {
            // line 203
            echo "        ";
            $this->displayBlock('featuer_thired', $context, $blocks);
            // line 208
            echo "      ";
        }
        // line 209
        echo "\t  ";
        // line 210
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])) {
            // line 211
            echo "        ";
            $this->displayBlock('featuer_fourth', $context, $blocks);
            // line 216
            echo "      ";
        }
        echo " 
\t </section>
";
        // line 218
        if ($this->getAttribute(($context["page"] ?? null), "footer", [])) {
            // line 219
            echo "  ";
            $this->displayBlock('footer', $context, $blocks);
        }
    }

    // line 57
    public function block_navbar($context, array $blocks = [])
    {
        // line 58
        echo "    ";
        // line 59
        $context["navbar_classes"] = [0 => "navbar", 1 => (($this->getAttribute($this->getAttribute(        // line 61
($context["theme"] ?? null), "settings", []), "navbar_inverse", [])) ? ("navbar-inverse") : ("navbar-default")), 2 => (($this->getAttribute($this->getAttribute(        // line 62
($context["theme"] ?? null), "settings", []), "navbar_position", [])) ? (("navbar-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", []), "navbar_position", []))))) : (($context["container"] ?? null)))];
        // line 65
        echo "    <header";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["navbar_attributes"] ?? null), "addClass", [0 => ($context["navbar_classes"] ?? null)], "method")), "html", null, true);
        echo " id=\"navbar\" role=\"banner\">
      ";
        // line 66
        if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", [0 => ($context["container"] ?? null)], "method")) {
            // line 67
            echo "        <div class=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
            echo "\">
      ";
        }
        // line 69
        echo "      <div class=\"navbar-header\">
        ";
        // line 70
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "navigation", [])), "html", null, true);
        echo "
        ";
        // line 72
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", [])) {
            // line 73
            echo "          <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#navbar-collapse\">
            <span class=\"sr-only\">";
            // line 74
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Toggle navigation"));
            echo "</span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
          </button>
        ";
        }
        // line 80
        echo "      </div>

      ";
        // line 83
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", [])) {
            // line 84
            echo "        <div id=\"navbar-collapse\" class=\"navbar-collapse collapse\">
          ";
            // line 85
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", [])), "html", null, true);
            echo "
        </div>
      ";
        }
        // line 88
        echo "      ";
        if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", [0 => ($context["container"] ?? null)], "method")) {
            // line 89
            echo "        </div>
      ";
        }
        // line 91
        echo "    </header>
  ";
    }

    // line 96
    public function block_main($context, array $blocks = [])
    {
        // line 97
        echo "  <div role=\"main\" class=\"main-container ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo " js-quickedit-main-content\">
    <div class=\"row\">

      ";
        // line 101
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "header", [])) {
            // line 102
            echo "        ";
            $this->displayBlock('header', $context, $blocks);
            // line 107
            echo "      ";
        }
        // line 108
        echo "
      ";
        // line 110
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])) {
            // line 111
            echo "        ";
            $this->displayBlock('sidebar_first', $context, $blocks);
            // line 116
            echo "      ";
        }
        // line 117
        echo "
      ";
        // line 119
        echo "      ";
        // line 120
        $context["content_classes"] = [0 => ((($this->getAttribute(        // line 121
($context["page"] ?? null), "sidebar_first", []) && $this->getAttribute(($context["page"] ?? null), "sidebar_second", []))) ? ("col-sm-6") : ("")), 1 => ((($this->getAttribute(        // line 122
($context["page"] ?? null), "sidebar_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-9") : ("")), 2 => ((($this->getAttribute(        // line 123
($context["page"] ?? null), "sidebar_second", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])))) ? ("col-sm-9") : ("")), 3 => (((twig_test_empty($this->getAttribute(        // line 124
($context["page"] ?? null), "sidebar_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-12") : (""))];
        // line 127
        echo "      <section";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["content_attributes"] ?? null), "addClass", [0 => ($context["content_classes"] ?? null)], "method")), "html", null, true);
        echo ">

        ";
        // line 130
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "highlighted", [])) {
            // line 131
            echo "          ";
            $this->displayBlock('highlighted', $context, $blocks);
            // line 134
            echo "        ";
        }
        // line 135
        echo "
        ";
        // line 137
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "help", [])) {
            // line 138
            echo "          ";
            $this->displayBlock('help', $context, $blocks);
            // line 141
            echo "        ";
        }
        // line 142
        echo "
        ";
        // line 144
        echo "        ";
        $this->displayBlock('content', $context, $blocks);
        // line 148
        echo "      </section>

      ";
        // line 151
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])) {
            // line 152
            echo "        ";
            $this->displayBlock('sidebar_second', $context, $blocks);
            // line 157
            echo "      ";
        }
        // line 158
        echo "\t  
\t  
    </div>
  </div>
";
    }

    // line 102
    public function block_header($context, array $blocks = [])
    {
        // line 103
        echo "          <div class=\"col-sm-12\" role=\"heading\">
            ";
        // line 104
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "header", [])), "html", null, true);
        echo "
          </div>
        ";
    }

    // line 111
    public function block_sidebar_first($context, array $blocks = [])
    {
        // line 112
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 113
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])), "html", null, true);
        echo "
          </aside>
        ";
    }

    // line 131
    public function block_highlighted($context, array $blocks = [])
    {
        // line 132
        echo "            <div class=\"highlighted\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "highlighted", [])), "html", null, true);
        echo "</div>
          ";
    }

    // line 138
    public function block_help($context, array $blocks = [])
    {
        // line 139
        echo "            ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "help", [])), "html", null, true);
        echo "
          ";
    }

    // line 144
    public function block_content($context, array $blocks = [])
    {
        // line 145
        echo "          <a id=\"main-content\"></a>
          ";
        // line 146
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "
        ";
    }

    // line 152
    public function block_sidebar_second($context, array $blocks = [])
    {
        // line 153
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 154
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])), "html", null, true);
        echo "
          </aside>
        ";
    }

    // line 187
    public function block_featuer_first($context, array $blocks = [])
    {
        // line 188
        echo "          <div class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 189
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "featuer_first", [])), "html", null, true);
        echo "
          </div>
        ";
    }

    // line 195
    public function block_featuer_second($context, array $blocks = [])
    {
        // line 196
        echo "          <div class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 197
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "featuer_second", [])), "html", null, true);
        echo "
          </div>
        ";
    }

    // line 203
    public function block_featuer_thired($context, array $blocks = [])
    {
        // line 204
        echo "          <div class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 205
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "featuer_thired", [])), "html", null, true);
        echo "
          </div>
        ";
    }

    // line 211
    public function block_featuer_fourth($context, array $blocks = [])
    {
        // line 212
        echo "          <div class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 213
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "featuer_fourth", [])), "html", null, true);
        echo "
          </div>
        ";
    }

    // line 219
    public function block_footer($context, array $blocks = [])
    {
        // line 220
        echo "    <footer class=\"footer ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo "\" role=\"contentinfo\">
      ";
        // line 221
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer", [])), "html", null, true);
        echo "
    </footer>
  ";
    }

    public function getTemplateName()
    {
        return "themes/custom/drupalfixer/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  460 => 221,  455 => 220,  452 => 219,  445 => 213,  442 => 212,  439 => 211,  432 => 205,  429 => 204,  426 => 203,  419 => 197,  416 => 196,  413 => 195,  406 => 189,  403 => 188,  400 => 187,  393 => 154,  390 => 153,  387 => 152,  381 => 146,  378 => 145,  375 => 144,  368 => 139,  365 => 138,  358 => 132,  355 => 131,  348 => 113,  345 => 112,  342 => 111,  335 => 104,  332 => 103,  329 => 102,  321 => 158,  318 => 157,  315 => 152,  312 => 151,  308 => 148,  305 => 144,  302 => 142,  299 => 141,  296 => 138,  293 => 137,  290 => 135,  287 => 134,  284 => 131,  281 => 130,  275 => 127,  273 => 124,  272 => 123,  271 => 122,  270 => 121,  269 => 120,  267 => 119,  264 => 117,  261 => 116,  258 => 111,  255 => 110,  252 => 108,  249 => 107,  246 => 102,  243 => 101,  236 => 97,  233 => 96,  228 => 91,  224 => 89,  221 => 88,  215 => 85,  212 => 84,  209 => 83,  205 => 80,  196 => 74,  193 => 73,  190 => 72,  186 => 70,  183 => 69,  177 => 67,  175 => 66,  170 => 65,  168 => 62,  167 => 61,  166 => 59,  164 => 58,  161 => 57,  155 => 219,  153 => 218,  147 => 216,  144 => 211,  141 => 210,  139 => 209,  136 => 208,  133 => 203,  130 => 202,  128 => 201,  125 => 200,  122 => 195,  119 => 194,  117 => 193,  114 => 192,  111 => 187,  108 => 186,  105 => 185,  100 => 183,  98 => 180,  97 => 179,  96 => 178,  95 => 177,  94 => 176,  93 => 175,  92 => 174,  91 => 173,  90 => 172,  89 => 171,  88 => 170,  87 => 169,  86 => 168,  85 => 167,  84 => 166,  83 => 165,  81 => 164,  79 => 96,  76 => 94,  72 => 57,  70 => 56,  68 => 54,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/drupalfixer/templates/page.html.twig", "C:\\xampp\\htdocs\\df\\web\\themes\\custom\\drupalfixer\\templates\\page.html.twig");
    }
}
