<?php
class JournalsHelper extends AppHelper
{
  var $helpers = array(
    'Candy',
    'Html',
    'Js'
  );
  function render_notes($journal, $current_user, $options=array()) {
    $content = '';
    $editable = $this->is_editable_by($journal, $current_user);
    $links = array();
    if($journal['Journal']['notes'] != '') {
      if(!empty($options['reply_links'])) {
        $links[] = $this->Js->link(
          	$this->Html->image(
			    'comment.png',
				array(
			       'title'=>__('Quote')
		        )
			),
          	array(
			    'controller'=>'issues',
			    'action'=>'reply',
			    $journal['Journal']['journalized_id'],
			    $journal['Journal']['id']
			),
          	array(
			    'escape' => false,
			    'buffer' => false,
			    'evalScripts' => true,
				'update'=>"journal-".$journal['Journal']['id']."-reply"
				//'success' => 'Element.show("update")'
			)
        );
      }
      if($editable) {
        $links[] = $this->Js->link(
          	$this->Html->image('edit.png', array(
			'title'=>__('Edit')
		)), 
          	array(
			  'controller'=>'journals',
			  'action'=>'edit',
			  $journal['Journal']['id']
		),
          	array(
			'escape' => false,
			'buffer' => false,
			'evalScripts' => true,
			'update'=>"journal-".$journal['Journal']['id']."-notes")
        );
      }
    }
    if(!empty($links)) $content .= $this->Html->tag('div', join(' ', $links), array('class'=>'contextual'));
    $content .= $this->Candy->textilizable($journal['Journal']['notes']);
    $return = $this->Html->tag(
        'div',
        $content,
        array(
            'id' => "journal-".$journal['Journal']['id']."-notes",
            'class' => ($editable ? 'wiki editable' : 'wiki')
        )
    ) . $this->Html->tag(
        'div',
		'',
		array(
		    'id' => "journal-".$journal['Journal']['id']."-reply",
		)
    );
        return $return;
    }

  function is_editable_by($journal, $usr) {
    return !empty($usr) && $usr['logged'] && ($this->Candy->authorize_for(':edit_issue_notes') || ($journal['User']['id'] == $usr['id'] && $this->Candy->authorize_for(':edit_own_issue_notes')) );
  }
}
