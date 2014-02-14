<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
    <div class="list-group">
        {% for routeName, title in [
            '/': 'LBL_MAIN_ROUTE'
        ] %}
            {% set class = 'list-group-item' %}

            {% if routeName == tag.getCurrentRouteName() %}
                {% set class = class ~ ' active' %}
            {% endif %}

            {{ linkTo([['for': routeName], t._(title), 'class': class ]) }}
        {% endfor %}
    </div>
</div>
