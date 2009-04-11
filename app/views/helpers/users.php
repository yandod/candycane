<?php
/**
 * users.php
 *
 * status = 1 // active
 * status = 2 // registered
 * status = 3 // locked
 */

/**
 * UsersHelper
 *
 */
class UsersHelper extends AppHelper
{
  var $helpers = array('Html');

#  def users_status_options_for_select(selected)
#    user_count_by_status = User.count(:group => 'status').to_hash
#    options_for_select([[l(:label_all), ''], 
#                        ["#{l(:status_active)} (#{user_count_by_status[1].to_i})", 1],
#                        ["#{l(:status_registered)} (#{user_count_by_status[2].to_i})", 2],
#                        ["#{l(:status_locked)} (#{user_count_by_status[3].to_i})", 3]], selected)
#  end
#  
#  # Options for the new membership projects combo-box
#  def projects_options_for_select(projects)
#    options = content_tag('option', "--- #{l(:actionview_instancetag_blank_option)} ---")
#    projects_by_root = projects.group_by(&:root)
#    projects_by_root.keys.sort.each do |root|
#      options << content_tag('option', h(root.name), :value => root.id, :disabled => (!projects.include?(root)))
#      projects_by_root[root].sort.each do |project|
#        next if project == root
#        options << content_tag('option', '&#187; ' + h(project.name), :value => project.id)
#      end
#    end
#    options
#  end
#  
#  def user_settings_tabs
#    tabs = [{:name => 'general', :partial => 'users/general', :label => :label_general},
#            {:name => 'memberships', :partial => 'users/memberships', :label => :label_project_plural}
#            ]
#  end

  /**
   * change_status_link
   *
   */
  function change_status_link($user)
  {
    if (isset($user['User'])) {
      $user = $user['User'];
    }

    # url = {:action => 'edit', :id => user, :page => params[:page], :status => params[:status]}
    $url = array(
      'action' => 'edit',
      'id' => $user,
      //'page' => $this->params['url']['page'],
      //'status' => $this->params['url']['status'],
    );

    // user status locked
    if ($user['status'] == 3) {
      return $this->Html->link(__('button_unlock', true), '/users/edit/' . $user['id'], array('class' => 'icon icon-unlock'));
      // return $this->Html->link(__('button_unlock', true), '/users/edit/' . $user['id'], array('class' => 'icon icon-unlock'));
    // user registered
    } else if ($user['status'] == 2) {
      return $this->Html->link(__('button_activate', true), '/users/edit/' . $user['id'], array('class' => 'icon icon-unlock'));
    } else {
      return $this->Html->link(__('button_lock', true), '/users/edit/' . $user['id'], array('class' => 'icon icon-lock'));
    }

    # if user.locked?
    #   link_to l(:button_unlock), url.merge(
    #     :user => {:status => User::STATUS_ACTIVE}),
    #     :method => :post,
    #     :class => 'icon icon-unlock'
    # elsif user.registered?
    #   link_to l(:button_activate), url.merge(
    #     :user => {:status => User::STATUS_ACTIVE}),
    #     :method => :post,
    #     :class => 'icon icon-unlock'
    # elsif user != User.current
    #   link_to l(:button_lock), url.merge(
    #   :user => {:status => User::STATUS_LOCKED}),
    #   :method => :post,
    #   :class => 'icon icon-lock'
    # end
  }
 
}
