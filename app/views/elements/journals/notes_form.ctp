<?php 
$journal_id = $journal['Journal']['id'];
$url = array('controller'=>'journals', 'action'=>'edit', 'id'=>$journal_id);
echo $form->create('Journal', array(
        'id'=>"journal-$journal_id-form", 'url'=>$url,
        'onsubmit'=>$ajax->remoteFunction(array('url'=>$url, 'form'=>true, 'after'=>'return false'))
      )
    );
echo $form->input('notes', array(
    'type'=>'textarea', 'class'=>'wiki-edit', 
    'rows'=>(($journal['Journal']['notes'] == '') ? 10 : min(max(10, strlen($journal['Journal']['notes']) / 50), 100)),
    'div'=>false, 'label'=>false));
// TODO call_hook for Plugin
// call_hook(:view_journals_notes_form_after_notes, { :journal => @journal}) %>
?>
<p>
<?php
echo $form->submit(__('Save',true), array('div'=>false));
echo $html->link(__('Cancel',true), '#', array('onclick'=>
    "Element.remove('journal-$journal_id-form');Element.show('journal-$journal_id-notes'); return false;"));
?>
</p>
<?php echo $ajax->Form->end(); ?>
