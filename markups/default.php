<?php

include("../WidgetSlideMenuRenderer.php");

$renderer = new WidgetSlideMenuRenderer(wire('page'), $settings);

echo "<div class='m-l--w' id='m_l__w'>". $renderer->render_menu(get_home_page(), $settings->menu_level) ."</div><!-- m-l--w -->";

