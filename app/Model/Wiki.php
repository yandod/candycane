<?php
/**
 * Wiki Model
 *
 * @package candycane
 * @subpackage candycane.models
 */
class Wiki extends AppModel {

/**
 * Model name
 *
 * @var string
 */
 	public $name = 'Wiki';

/**
 * "Belongs To" Associations
 *
 * @var array
 */
 	public $belongsTo = array('Project');


/**
 * "Has Many" Associations
 *
 * @var array
 */
 	public $hasMany = array(
		'WikiPage' => array(
			'className' => 'WikiPage',
			'dependent' => true,
			// :dependent => :destroy
			'order' => 'title',
		),
		'WikiRedirect' => array(
			'className' => 'WikiRedirect',
			'dependent' => true,
			// :dependent => :delete_all
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'start_page' => array(
			'validates_presence_of' => array('rule' => 'notEmpty'),
			'validates_format_of' => array('rule' => array('custom', '/^[^,\.\/\?\;\|\:]*$/'))
		),
	);

/**
 * Find Project by id
 *
 * @param string $project_id Project Id
 * @return array Wiki Data
 */
	public function findByProjectId($project_id) {
		return $this->find('first', array(
			'conditions' => array(
				'Wiki.project_id' => $project_id
			),
		));
	}

/**
 * Find the page with the given title.
 * If page doesn't exist, return a data array for saving new page.
 *
 * @param string $title Page Title
 * @return array Page data
 * @todo Add support for WikiContentVersion
 */
	public function find_or_new_page($title) {
		if ($title === null || $title === '') {
			$title = $this->field('start_page');
		}
		$page = $this->find_page($title);
		if (!$page) {
			$page = array(
				'WikiPage' => array(
					'wiki_id' => $this->id,
					'title' => Wiki::titleize($title)
				),
				'WikiContent' => array(
					'version' => 1, // temporary (until supporting WikiContentVersion)
				), 
			);
		}
		return $page;
	}

/**
 * Find the page with the given title
 *
 * @param string $title Title
 * @param array $options Options
 * @return array Page data
 */
	public function find_page($title, $options = array()) {
		$param = array();
		if ($title === "") {
			$title = $this->field('start_page');
		}
		$title = Wiki::titleize($title);
		$page = $this->WikiPage->find('first', array(
			'conditions' => array(
				'WikiPage.wiki_id' => $this->id,
				'WikiPage.title' => $title
			),
		));
		if (isset($page['WikiPage']['id'])) {
			$this->WikiPage->id = $page['WikiPage']['id'];
		}
		//    if !page && !(options[:with_redirect] == false)
		//      # search for a redirect
		//      redirect = redirects.find_by_title(title)
		//      page = find_page(redirect.redirects_to, :with_redirect => false) if redirect
		//    end
		return $page;
	}

/**
 * Title-ize a string
 *
 * @param string $title Title
 * @return string Title-ized string
 */
	public function titleize($title) {
		// replace spaces with _ and remove unwanted caracter
		$title = preg_replace('/\s+/', '_', $title);
		// upcase the first letter
		return preg_replace('/^([a-z])/e', 'strtoupper("\\1")', $title);
	}
}