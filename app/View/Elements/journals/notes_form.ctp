<?php 
$journal_id = $journal['Journal']['id'];
$url = array(
	'controller' => 'journals',
	'action' => 'edit',
	$journal_id
);
echo $this->Form->create(
    'Journal',
    array(
        'id' => "journal-$journal_id-form",
		'url' => $url,
        'onsubmit' => $this->Js->request(
			$url,
		    array(
//			    'url' => $url,
				'form' => true,
				'after' => 'return false'
			)
		)
    )
);
echo $this->Form->input(
    'notes',
    array(
        'type' => 'textarea',
		'class' => 'wiki-edit', 
        'rows' => (($journal['Journal']['notes'] == '') ? 10 : min(max(10, strlen($journal['Journal']['notes']) / 50), 100)),
        'div' => false,
		'label' => false
	)
);
// TODO call_hook for Plugin
// call_hook(:view_journals_notes_form_after_notes, { :journal => @journal}) %>
?>
<p>
<?php
echo $this->Form->submit(
    __('Save'),
	array(
	    'div' => false
	)
);
echo $this->Html->link(
    __('Cancel'),
	'#',
	array(
	    'onclick' => "Element.remove('journal-$journal_id-form');Element.show('journal-$journal_id-notes'); return false;"
	)
);?>
</p>
<?php echo $this->Form->end(); ?>
