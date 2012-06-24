<?php

class ThemeContainer extends Object {
  protected $themes = array();

  public function __construct() {
    $this->initTheme();
  }

  protected function initTheme() {
    $themed_dir = APP . DS . 'webroot/themed';

    $theme_dirs = glob($themed_dir . DS . '*', GLOB_ONLYDIR);
    $theme_list = array_map('basename', $theme_dirs);
    $this->themes = array_merge(
      $this->themes, array_combine($theme_list, $theme_list)
    );
  }

  /*
  protected function initPluginThemes() { }
   */

  public function getThemeLists() {
    return $this->themes;
  }
}
