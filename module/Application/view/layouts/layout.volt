{{ getDoctype() }}
<html lang="en">
<head>{% include view.getLayoutsDir() ~ "head" %}</head>
<body>
<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project Name</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                {% for routeName, title in [
                    '/': 'LBL_HOME_PAGE',
                    'admin': 'Admin'
                ] %}
                    {% set class = '' %}
                    {% if routeName == tag.getCurrentRouteName() %}
                        {% set class = 'active' %}
                    {% endif %}

                    <li class="{{ class }}">
                        {{ linkTo([['for': routeName], t._(title), 'class': class]) }}
                    </li>
                {% endfor %}
            </ul>
            <ul class="nav navbar-nav navbar-right">
                {% if config.multilanguage.enabled %}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ t._("LBL_LANGUAGES") }} <b class="caret"></b>
                        </a>
                        <ul id="languages" class="dropdown-menu">
                            {%- for key, lang in config.multilanguage.languages %}
                                <li><a data-key="{{ key }}" href="#">{{ lang }}</a></li>
                            {%- endfor %}
                        </ul>
                    </li>
                {% endif %}
            </ul>
        </div>
        <!-- /.nav-collapse -->
    </div>
    <!-- /.container -->
</div>
<!-- /.navbar -->

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">

        {% include view.getLayoutsDir() ~ "sidebar" %}

        <div class="col-xs-12 col-sm-9">
            <div class="jumbotron">
                {{ content() }}

                <p>{{ t._("TPL_GREETING_TEXT", ['userName' : 'User From Layout']) }}</p>
            </div>
            <div class="row">
            </div>
            <!--/row-->
        </div>
        <!--/span-->
    </div>
    <!--/row-->

    <hr>

    <footer>{% include view.getLayoutsDir() ~ "footer" %}</footer>

</div>
<!--/.container-->
</body>
</html>
