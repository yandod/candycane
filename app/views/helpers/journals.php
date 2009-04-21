<?php
# redMine - project management software
# Copyright (C) 2006-2008  Jean-Philippe Lang
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

class JournalsHelper extends AppHelper
{
  var $helpers = array(
    'Candy',
    'Html',
    'Ajax'
  );
  function render_notes($journal, $current_user, $options=array()) {
    $content = '';
    $editable = $this->is_editable_by($journal, $current_user);
    $links = array();
    if($journal['Journal']['notes'] != '') {
      if(!empty($options['reply_links'])) {
        $links[] = $this->Ajax->link(
          $this->Html->image('comment.png'),
          array('controller'=>'issues', 'action'=>'reply', 'id'=>$journal['Journal']['journalized_id'], 'journal_id'=>$journal['Journal']['id']),
          array('title'=>__('Quote',true))
          ,null,false
        );
      }
      if($editable) {
        $links[] = $this->Ajax->link(
          $this->Html->image('edit.png'), 
          array('controller'=>'journals', 'action'=>'edit', 'id'=>$journal['Journal']['id']),
          array('title'=>__('Edit',true), 'update'=>"journal-".$journal['Journal']['id']."-notes")
          ,null,false
        );
      }
    }
    if(!empty($links)) $content .= $this->Html->tag('div', join(' ', $links), array('class'=>'contextual'));
    $content .= $this->Candy->textilizable($journal['Journal']['notes']);
    return $this->Html->tag('div', $content, array('id'=>"journal-".$journal['Journal']['id']."-notes", 'class'=>($editable ? 'wiki editable' : 'wiki')));
  }

  function is_editable_by($journal, $usr) {
    return !empty($usr) && $usr['logged'] && ($this->Candy->authorize_for(':edit_issue_notes') || ($journal['User']['id'] == $usr['id'] && $this->Candy->authorize_for(':edit_own_issue_notes')) );
  }
}
