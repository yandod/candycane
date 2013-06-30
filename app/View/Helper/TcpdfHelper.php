<?php
class TcpdfHelper extends TCPDF {
  var $helpers = array();
  var $font_for_content = 'arialunicid0';
  var $font_for_footer = 'arialunicid0';
  var $footer_date = '';
  
  function __construct($options) {
    $defaults = array(
      'orientation'   => 'P',
      'unit'          => 'mm',
      'format'        => 'A4',
      'unicode'       => true,
      'encoding'      => "UTF-8",
    );
    $options =  (is_array($options)) ? array_merge($defaults, $options) : $defaults;
    extract(array_merge($defaults, $options));
    parent::__construct($orientation, $unit, $format, $unicode, $encoding);
  }
  
  function beforeRender() {
    $Settings =& ClassRegistry::getObject('Setting');
    $this->SetCreator($Settings->app_title);
    $this->SetFont($this->font_for_content);
  }

  function beforeRenderFile()
  {

  }

  function SetFontStyle($style, $size) {
    $this->SetFont($this->font_for_content, $style, $size);
  }

  // Page footer
  function Footer() {
    $this->SetFont($this->font_for_footer, 'I', 8);
    $this->SetY(-15);
    $this->SetX(15);
    $this->Cell(0, 5, $this->footer_date, 0, 0, 'L');
    $this->SetY(-15);
    $this->SetX(-30);
    $this->Cell(0, 5, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
  }  
}