<?php

function isActive(Page $p)
{
  return wire('page')->id === $p->id;
}

function isChildActive(Page $p)
{
  return (boolean) $p->find("id=" . wire('page'))->count();
}

function render_menu(Page $root, $deep = 2, WireData $settings)
{
  $page = wire('page');
  if ($page->children->count()) {
    $active = $root->id === $page->id;
  } else {
    $active = $root->id === $page->parent->id;
  }
  
  
  $html = "<ul class='m-l ". ($active ? "m-l--a' style='left:0%;'" : "' style='left:100%;'") ." data-page-id='$root'>";

  // Add a current root item
  if ($root->id !== 1) {
  $p = $root->parent;
  $html .= "<li class='m-i m-i--prev ". (isActive($root) ? 'm-i--a' : '') ."' data-page-id='$p'>";
  $html .= "<a class='m-ia m-ia--prev' href='$p->url'><i class='icon-left m-ii--prev'></i> <span class='m-ia--prev-txt'>". $root->get($settings->text_field) ."</span></a>";
  $html .= "</li>";
  }

  foreach ($root->children('sort=sort, ' . $settings->include_selector) as $p) {
    $html .= "<li class='m-i ". (isActive($p) ? 'm-i--a' : '') ."' data-page-id='$p'>";
    $html .= "<a class='m-ia ". ($p->children($settings->include_selector)->count() ? "m-ia--left'" : "'") . (isActive($p) ? "" : "href='$p->url'") .">". $p->get($settings->text_field) ."</a>";
    if ($p->children($settings->include_selector)->count()) $html .= "<a class='m-ia m-ia--next ". (isChildActive($p) ? 'm-ia--a' : '') ."' href='$p->url'><i class='icon-right'></i></a>";
    $html .= "</li>";
  }
  $html .= "</ul>";

  foreach ($root->children('sort=sort') as $p) {
    if ($p->children()->count() && $deep > 0) {
      $html .= render_menu($p, $deep - 1, $settings);
    }
  }

  return $html;
}

echo "<div class='m-l--w' id='m_l__w'>". render_menu($renderPages->first(), $settings->menu_level, $settings) ."</div><!-- m-l--w -->";

