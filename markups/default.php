<?php

include("../WidgetSlideMenuRenderer.php");

$renderer = new WidgetSlideMenuRenderer(wire('page'), $settings);

echo "<div class='m-l--w' id='m_l__w'>". $renderer->render_menu($renderPages->first(), $settings->menu_level) ."</div><!-- m-l--w -->";

