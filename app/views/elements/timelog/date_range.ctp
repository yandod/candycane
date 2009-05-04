<fieldset id="filters"><legend><?php __('Date range') ?></legend>
<p>
<?php 
  $period_select = $ajax->Form->input('period', array(
      'type' => 'select',
      'options' => $timelog->options_for_period_select(),
      'onchange' => 'this.form.onsubmit();',
      'onfocus' => '$("period_type_1").checked = true;',
      'label'=>false,
      'div'=>false,
      'value'=>$this->params['url']['period'],
  ));
  echo $ajax->Form->input('period_type', array(
      'type'=>'radio', 
      'options'=>array('1'=>' '.$period_select), 
      'div'=>false, 
      'label'=>false,
      'value'=>$this->params['url']['period_type'],
  )); 
?>
</p>
<p>
<?php
  $date_select = '<span onclick="$(\'period_type_2\').checked = true;">';
  $date_select .= __('From',true).': ';
  $date_select .= $ajax->Form->input('from', array(
      'type' => 'text',
      'size' => 10,
      'id'   => 'from',
      'div'=>false, 
      'label'=>false,
      'value'=>$this->params['url']['from'],
  ));
  $date_select .= $candy->calendar_for('from');
  $date_select .= ' '.__('To',true).' ';
  $date_select .= $ajax->Form->input('to', array(
      'type' => 'text',
      'size' => 10,
      'id'   => 'to',
      'div'=>false, 
      'label'=>false,
      'value'=>$this->params['url']['to'],
  ));
  $date_select .= $candy->calendar_for('to');
  $date_select .= '</span>';
  echo $ajax->Form->input('period_type', array(
      'type'=>'radio', 
      'options'=>array('2'=>' '.$date_select), 
      'div'=>false, 
      'label'=>false,
      'value'=>$this->params['url']['period_type'],
  ));
  echo ' '.$ajax->Form->submit(__('Apply',true), array('name' => null, 'div'=>false));
?>
</p>
</fieldset>

<div class="tabs">
<?php
  $url_params = ($this->params['url']['period_type']==2) 
    ? array('from' => $this->params['url']['from'], 'to' => $this->params['url']['to'] ) 
    : array('period' => $this->params['url']['period'] ); 
?>
<ul>
  <li><?php echo $html->link(__('Details',true), 
          array_merge($timelog->link_to_timelog_detail_url($main_project), array('?'=>$url_params)),
          array('class' => ($this->action == 'details' ? 'selected' : ''))); ?></li>
  <li><?php echo $html->link(__('Reports',true), 
          array_merge($timelog->link_to_timelog_report_url($main_project), array('?'=>$url_params)),
          array('class' => ($this->action == 'reports' ? 'selected' : ''))); ?></li>
</ul>
</div>
