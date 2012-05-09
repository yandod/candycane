<fieldset id="filters"><legend><?php echo __('Date range') ?></legend>
<p>
<?php 
  $period_select = $this->Form->input('period', array(
      'type' => 'select',
      'options' => $this->Timelog->options_for_period_select(),
      'onchange' => 'this.form.onsubmit();',
      'onfocus' => '$("TimeEntryPeriodType1").checked = true;',
      'label'=>false,
      'div'=>false,
      'value'=>$this->request->query['period'],
  ));
  echo $this->Form->input('period_type', array(
      'type'=>'radio', 
      'options'=>array('1'=>' '.$period_select), 
      'div'=>false, 
      'label'=>false,
      'value'=>$this->request->query['period_type'],
  )); 
?>
</p>
<p>
<?php
  $date_select = '<span onclick="$(\'TimeEntryPeriodType2\').checked = true;">';
  $date_select .= __('From').': ';
  $date_select .= $this->Form->input('from', array(
      'type' => 'text',
      'size' => 10,
      'id'   => 'from',
      'div'=>false, 
      'label'=>false,
      'value'=>$this->request->query['from'],
  ));
  $date_select .= $this->Candy->calendar_for('from');
  $date_select .= ' '.__('To').' ';
  $date_select .= $this->Form->input('to', array(
      'type' => 'text',
      'size' => 10,
      'id'   => 'to',
      'div'=>false, 
      'label'=>false,
      'value'=>$this->request->query['to'],
  ));
  $date_select .= $this->Candy->calendar_for('to');
  $date_select .= '</span>';
  echo $this->Form->input('period_type', array(
      'type'=>'radio', 
      'options'=>array('2'=>' '.$date_select), 
      'div'=>false, 
      'label'=>false,
      'value'=>$this->request->query['period_type'],
  ));
  echo ' '.$this->Form->submit(__('Apply'), array('name' => null, 'div'=>false));
?>
</p>
</fieldset>

<div class="tabs">
<?php
  $url_params = ($this->request->query['period_type']==2) 
    ? array('from' => $this->request->query['from'], 'to' => $this->request->query['to'] ) 
    : array('period' => $this->request->query['period'] ); 
?>
<ul>
  <li><?php echo $this->Html->link(__('Details'), 
          array_merge($this->Timelog->link_to_timelog_detail_url($main_project), array('?'=>$url_params)),
          array('class' => ($this->request->action == 'details' ? 'selected' : ''))); ?></li>
  <li><?php echo $this->Html->link(__('Reports'), 
          array_merge($this->Timelog->link_to_timelog_report_url($main_project), array('?'=>$url_params)),
          array('class' => ($this->request->action == 'report' ? 'selected' : ''))); ?></li>
</ul>
</div>
