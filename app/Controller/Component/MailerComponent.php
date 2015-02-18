<?php
App::uses('CakeEmail', 'Network/Email');

class MailerComponent extends Component {

	public function startup(Controller $Controller) {
		$this->Controller = $Controller;

		if (extension_loaded('mbstring')) {
			switch (Configure::read('Config.language')) {
				case 'jpn':
					$lang = "ja";
					break;
				case 'eng':
					$lang = "en";
					break;
				default:
					$lang = "uni";
			}
			mb_language($lang);
			mb_internal_encoding("UTF-8");
		}

		//tmp crash fix
		$email = "candycane@example.com";
		if (Validation::email($this->Controller->Setting->mail_from)) {
			$email = $this->Controller->Setting->mail_from;
		}

		$this->Email = new CakeEmail(array(
			'transport' => $this->Controller->Setting->mail_transport,
			'from' => $email,
			'host' => $this->Controller->Setting->mail_host,
			'port' => $this->Controller->Setting->mail_port,
			'username' => $this->Controller->Setting->mail_username,
			'password' => $this->Controller->Setting->mail_password,

		));
		$this->Email->viewVars(array(
			'footer' => $this->Controller->Setting->emails_footer
		));
		if ($this->Controller->Setting->plain_text_mail) {
			$this->Email->emailFormat('text');
		} else {
			$this->Email->emailFormat('both');
		}
	}

	protected function _toNoSelfFilter($emails) {
		if (!empty($this->Controller->current_user['UserPreference']['pref']['no_self_notified'])) {
			foreach ($emails as $key => $email) {
				if ($this->Controller->current_user['mail'] === $email) {
					unset($emails[$key]);
				}
			}
		}
		return $emails;
	}

	public function deliver_issue_add($Issue) {
		$issue_data = $Issue->findById($Issue->id);

		if ($this->Controller->Setting->bcc_recipients) {
			$this->Email->to($this->Controller->Setting->mail_from);
			$this->Email->bcc($this->_toNoSelfFilter($Issue->recipients()));
		} else {
			$this->Email->to($this->_toNoSelfFilter($Issue->recipients()));
		}

		return $this->Email->template('issue_add')
			->viewVars(array(
				'issue' => $issue_data,
				'issueurl' => Router::url(array(
					'controller' => 'issues',
					'action' => 'show',
					'issue_id' => $Issue->id
				), true),
			))
			->subject(vsprintf('%s - %s #%s %s %s', array(
				$issue_data['Project']['name'],
				$issue_data['Tracker']['name'],
				$issue_data['Issue']['id'],
				$issue_data['Status']['name'],
				$issue_data['Issue']['subject']
			)))
			->send();
	}

	public function deliver_issue_edit($Journal, $Issue) {
		$issue_data = $Issue->findById($Issue->id);
		$journal_data = $Journal->findById($Journal->getLastInsertID());

		if ($this->Controller->Setting->bcc_recipients) {
			$this->Email->to($this->Controller->Setting->mail_from);
			$this->Email->bcc($this->_toNoSelfFilter($Issue->recipients()));
		} else {
			$this->Email->to($this->_toNoSelfFilter($Issue->recipients()));
		}

		return $this->Email->template('issue_edit')
			->viewVars(array(
				'issue' => $issue_data,
				'journal' => $journal_data,
				'issueurl' => Router::url(array(
					'controller' => 'issues',
					'action' => 'show',
					'issue_id' => $Issue->id
				), true),
			))
			->subject(vsprintf('%s - %s #%s %s %s', array(
				$issue_data['Project']['name'],
				$issue_data['Tracker']['name'],
				$issue_data['Issue']['id'],
				$issue_data['Status']['name'],
				$issue_data['Issue']['subject']
			)))
			->send();
	}

	public function deliver_register($token, $user) {
		#    set_language_if_valid(token.user.language)
		return $this->Email->template('register')
			->viewVars(array(
				'token' => $token,
				'url' => Router::url(array(
					'controller' => 'account',
					'action' => 'activate',
					'?' => array('token' => $token['value'])
				), true),
			))
			->to($user['User']['mail'])
			->subject(__('Your %s account activation', $this->Controller->Setting->app_title))
			->send();
	}

	public function deliver_account_activation_request($user, $User) {
		//Send the email to all active administrators
		$recipients = $User->find('list', array(
			'fields' => array('id', 'mail'),
			'conditions' => array(
				'User.admin' => true,
				'User.status' => 1, //active
			)
		));

		if ($this->Controller->Setting->bcc_recipients) {
			$this->Email->to($this->Controller->Setting->mail_from);
			$this->Email->bcc($this->_toNoSelfFilter($recipients));
		} else {
			$this->Email->to($this->_toNoSelfFilter($recipients));
		}

		return $this->Email->template('account_activation_request')
			->viewVars(array(
				'user' => $user,
				'url' => Router::url(array(
					'controller' => 'users',
					'action' => 'index',
					'?' => array('status' => 2)
				), true),
			))
			->subject(__('%s account activation request', $this->Controller->Setting->app_title))
			->send();
	}

	public function deliver_lost_password($token, $user) {
		# set_language_if_valid(token.user.language)
		return $this->Email->template('lost_password')
			->viewVars(array(
				'token' => $token,
				'user' => $user,
				'url' => Router::url(array(
					'controller' => 'account',
					'action' => 'lost_password',
					'token' => $token['Token']['value']
				), true),
				'footer' => $this->Controller->Setting->emails_footer
			))
			->to($user['User']['mail'])
			->subject(__('Your password'))
			->send();
	}

	public function deliver_news_added($news) {
		#    redmine_headers 'Project' => news.project.identifier
		$news->read();
		$news->Project->read();

		if ($this->Controller->Setting->bcc_recipients) {
			$this->Email->to($this->Controller->Setting->mail_from);
			$this->Email->bcc($this->_toNoSelfFilter($news->Project->recipients()));
		} else {
			$this->Email->to($this->_toNoSelfFilter($news->Project->recipients()));
		}

		return $this->Email->template('news_added')
			->viewVars(array(
				'news' => $news->data,
				'news_url' => Router::url(array(
					'controller' => 'news',
					'action' => 'show',
					'id' => $news->id
				), true),
			))
			->subject(vsprintf('[%s] %s: %s', array(
				$news->Project->data['Project']['name'],
				__('News'),
				$news->data['News']['title']
			)))
			->send();
	}

	public function deliver_test($user) {
		return $this->Email->template('test')
			->viewVars(array(
				'url' => Router::url(array('controller' => 'welcome'), true),
			))
			->to($user['mail'])
			->subject('CandyCane test')
			->send();
	}

}
