<?php
/*
# redMine - project management software
# Copyright (C) 2006-2007  Jean-Philippe Lang
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

module WatchersHelper
end

*/
class WatchersHelper extends AppHelper
{
  var $helpers = array('Html', 'Js', 'Candy');

  function watcher_tag($object, $user) {
    return $this->Html->tag("span", $this->watcher_link($object, $user), array('id'=>'watcher', false));
  }

  function watcher_link($object, $user) {
    $watched_by = $this->requestAction(array('controller'=>'issues', 'action'=>'watched_by'));
    if(!($user && $user['logged'])) {
      return '';
    }
    $watched = !empty($watched_by);
    $type = key($object);
    $url = array(
            'controller' => 'watchers',
            'action' => ($watched ? 'unwatch' : 'watch'),
            'object_type' => $type,
            'object_id' => $object[$type]['id']);
    $link = $this->Js->link(($watched ? __('Unwatch') : __('Watch')), $url, array(
        'class'=> ($watched ? 'icon icon-fav' : 'icon icon-fav-off'),
        'update'=>'watcher_link'
    ));
    return $this->Html->tag('span', $link, array('id'=>'watcher_link'));
  }

  # Returns a comma separated list of users watching the given object
  function watchers_list($object) {
    $out = '';
    $first = true;
    foreach($object as $u) {
      if($first) {
        $first = false;
      } else {
        $out .= ",\n";
      }
      $out .= $this->Html->tag('span', $this->Candy->link_to_user($u['User'], array('class'=> 'user')));
    }
    return $out;
  }
 
}
