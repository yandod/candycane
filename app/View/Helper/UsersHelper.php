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
class UsersHelper extends AppHelper {
	public $helpers = array(
		'Html',
		'Js'// => array('Prototype')
	);

  /**
   * change_status_link
   *
   */
	public function change_status_link($user) {
		if (isset($user['User'])) {
			$user = $user['User'];
		}
		if ($user['status'] == 3) {
			// user status locked
			return $this->Js->link(
				__('Unlock'),
				'/users/edit/' . $user['id'],
				array(
					'class' => 'icon icon-unlock',
					//'method' => 'get',
					//'async' => false,
					'data' => array(
						'data[User][status]' => 1,
						'data[User][id]' => $user['id']						
					),
					'update' => 'wrapper'
				)
			);
		} else if ($user['status'] == 2) {
			// user registered
			return $this->Js->link(
				__('Activate'),
				'/users/edit/' . $user['id'],
				array(
					'class' => 'icon icon-unlock',
					//'method' => 'post',
					//'async' => false,
					'data' => array(
						'data[User][status]' => 1,
						'data[User][id]' => $user['id']
					),
					'update' => 'wrapper'
				)
			);
		} else {
			return $this->Js->link(
				__('Lock'),
				'/users/edit/' . $user['id'],
				array(
					'class' => 'icon icon-lock',
					'method' => 'post',
					//'async' => true,
					'data' => array(
						'data[User][status]' => 3,
						'data[User][id]' => $user['id']
					),
					'update' => 'wrapper',
					//'buffer' => false
				)
			);
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
