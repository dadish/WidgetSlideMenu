<?php

class WidgetSlideMenuRenderer {

  public $settings;

  public $page;

  public function __construct(Page $page, WireData $settings)
  {
    $this->page = $page;
    $this->settings = $settings;
  }

  public function isActive (Page $p)
  { 
    if ($p->id === $this->page->id) return true;
      
    if ($p->children("id=$this->page")->count() && !$p->children($this->settings->include_selector)->count()) return true;

    return false;
  }

  public function isClickable(Page $p)
  {
    return $this->page->id !== $p->id;
  }

  public function isChildActive (Page $p)
  {
    return (boolean) $p->find("id=" . $this->page)->count();
  }

  public function render_menu (Page $root, $deep = 2)
  {
    if ($this->page->children($this->settings->include_selector)->count()) {
      $active = $root->id === $this->page->id;

    } else if (!$this->page->parent->children($this->settings->include_selector)->count()) {
      $active = $root->id === $this->page->parent->parent->id;
    } else {
      $active = $root->id === $this->page->parent->id;
    }
    
    
    $html = "<ul class='m-l ". ($active ? "m-l--a' style='left:0%;'" : "' style='left:100%;'") ." data-page-id='$root'>";

    // Add a current root item
    if ($root->parent->id !== 1) {
    $p = $root->parent;
    $html .= "<li class='m-i m-i--prev ". ($this->isActive($root) ? 'm-i--a' : '') ."' data-page-id='$p'>";
    $html .= "<a class='m-ia m-ia--prev' href='$p->url'><i class='icon-left m-ii--prev'></i> <span class='m-ia--prev-txt'>". $root->get($this->settings->text_field) ."</span></a>";
    $html .= "</li>";
    }

    foreach ($root->children('sort=sort, ' . $this->settings->include_selector) as $p) {
      $html .= "<li class='m-i ". ($this->isActive($p) ? 'm-i--a' : '') ."' data-page-id='$p'>";
      $html .= "<a class='m-ia ". ($p->children($this->settings->include_selector)->count() ? "m-ia--left'" : "'") . ($this->isClickable($p) ? "href='$p->url'" : "") .">". $p->get($this->settings->text_field) ."</a>";
      if ($p->children($this->settings->include_selector)->count()) $html .= "<a class='m-ia m-ia--next ". ($this->isChildActive($p) ? 'm-ia--a' : '') ."' href='$p->url'><i class='icon-right'></i></a>";
      $html .= "</li>";
    }
    $html .= "</ul>";

    foreach ($root->children('sort=sort') as $p) {
      if ($p->children()->count() && $deep > 0) {
        $html .= $this->render_menu($p, $deep - 1);
      }
    }

    return $html;
  }
}