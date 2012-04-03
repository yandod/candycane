<?php
 /**
  * Display validation errors division.
  * If you do not use $form when output tag of input, set argument $formHelper at renderElement.  
  * ex1: echo $this->renderElement('error_explanation');
  * ex2: echo $this->renderElement('error_explanation', array('formHelper'=>$ajax->Form));
  */
?>
<?php
if(!empty($this->validationErrors)):
  if(!isset($forHelper)) {
    if(!isset($form)) {
      return;
    }
    $formHelper = $form;
  }
?>
<div id="errorExplanation" class="errorExplanation">
<span>
  <?php
  $count = 0; 
  foreach($this->validationErrors as $errors) {
    $count += count($errors);
  }
  echo sprintf(__('%d errors'), $count); 
  ?>:
</span>
<ul>
<?php
  foreach($this->validationErrors as $errors):
    foreach($errors as $field => $message):
?>
  <li><?php echo sprintf(__('[%s] : %s'), $this->Candy->label_text($field), $message); ?></li>
<?php
    endforeach;
  endforeach;
  $formHelper->validationErrors = array();
?>
</ul>
</div>
<?php endif; ?>
