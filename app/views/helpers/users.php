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
  var $helpers = array('Html','Ajax');

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
      return $this->Ajax->link(
          __('Unlock', true),
          '/users/edit/' . $user['id'],
          array(
              'class' => 'icon icon-unlock',
              'with' => "{'data[User][status]':1,'data[User][id]':{$user['id']}}",
              'update' => 'wrapper'
              ));
    // user registered
    } else if ($user['status'] == 2) {
      return $this->Ajax->link(
          __('Activate', true),
          '/users/edit/' . $user['id'],
          array(
              'class' => 'icon icon-unlock',
              'with' => "{'data[User][status]':1,'data[User][id]':{$user['id']}}",
              'update' => 'wrapper'
              ));
    } else {
      return $this->Ajax->link(
          __('Lock', true),
          '/users/edit/' . $user['id'],
          array(
              'class' => 'icon icon-lock',
              'with' => "{'data[User][status]':3,'data[User][id]':{$user['id']}}",
              'update' => 'wrapper'
              ));
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
