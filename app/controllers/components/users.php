<?php
/**
 * users.php
 *
 */

/**
 * UsersComponent
 *
 */
class UsersComponent extends Object
{

  /**
   * users_status_options_for_select
   *
   */
  function users_status_options_for_select($selected)
  {
    $user_count_by_status = $this->User
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
  }

  /**
   * user_settings_tabs
   *
   */
  function user_settings_tabs()
  {
#    tabs = [{:name => 'general', :partial => 'users/general', :label => :label_general},
#            {:name => 'memberships', :partial => 'users/memberships', :label => :label_project_plural}
#            ]
  }

}
