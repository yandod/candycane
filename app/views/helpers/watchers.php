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
  var $helpers = array('Html');

  function watcher_tag($object, $user) {
    return $this->Html->tag("span", $this->watcher_link($object, $user), array('id'=>'watcher', false));
  }

  function watcher_link($object, $user) {
    // TODO ?????
    // return '' unless user && user.logged? && object.respond_to?('watched_by?')
    // TODO Helper -> Model(SQL)
    // $watched = object.watched_by?(user)
    /*
    $url = array(
            'controller' => 'watchers',
            'action' => (watched ? 'unwatch' : 'watch'),
           :object_type => object.class.to_s.underscore,
           :object_id => object.id}           
    link_to_remote((watched ? l(:button_unwatch) : l(:button_watch)),
                   {:url => url},
                   :href => url_for(url),
                   :class => (watched ? 'icon icon-fav' : 'icon icon-fav-off'))
    */
    return '';
  }

  # Returns a comma separated list of users watching the given object
  function watchers_list($object) {
    $out = '';
    foreach($object as $u) {
      $out .= $this->Html->tag('span', $this->Html->link($u['User']['lastname'].' '.$u['User']['firstname'], array('controller'=>'account', 'action'=>'show', 'id'=>$u['User']['id'])), array('class'=> 'user'));
      $out .= ",\n";
    }
    return $out;
  }
 
}
