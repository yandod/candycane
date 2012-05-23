<?php
class News extends AppModel
{
	var $belongsTo = array(
		'Project' => array(),
		'Author' => array(
			'className' => 'User',
			'foreginKey' => 'author_id',
		),
	);
    var $hasMany = array(
        'Comments' => array(
            'className' => 'Comment',    
            'foreignKey' => 'commented_id',
            'conditions' => array('Comments.commented_type' => 'News'),
            'dependent' => true
        )
    ); 

  var $validate = array(
    'title'  =>  array(
      'Required' =>  array( 'rule' => array('notEmpty'), 'required' => true ),
      'maxLength' =>  array( 'rule' => array('maxLength', 60) ),
    ),
    'summary'  =>  array(
      'maxLength' =>  array( 'rule' => array('maxLength', 255) ),
    ),
    'description'  =>  array(
      'Required' =>  array( 'rule' => array('notEmpty'), 'required' => true ),
    ),
  ) ;
//  belongs_to :author, :class_name => 'User', :foreign_key => 'author_id'
//  has_many :comments, :as => :commented, :dependent => :delete_all, :order => "created_on"
//  
//  validates_presence_of :title, :description
//  validates_length_of :title, :maximum => 60
//  validates_length_of :summary, :maximum => 255
//
//  acts_as_searchable :columns => ['title', "#{table_name}.description"], :include => :project
//  acts_as_event :url => Proc.new {|o| {:controller => 'news', :action => 'show', :id => o.id}}
//  acts_as_activity_provider :find_options => {:include => [:project, :author]},
//                            :author_key => :author_id
  var $actsAs = array(
    'ActivityProvider'=> array(
      'find_options'=> array('include'=>array('Project', 'Author')),
      'author_key'=>'author_id'),
    'Event' => array('url' => array('Proc' => '_event_url')),
    'Searchable' => array()
  );
  var $filterArgs = array(
    array('name' => 'description', 'type' => 'like'),
    array('name' => 'title', 'type' => 'like'),
  );
  function _event_url($data) {
    return  array('controller'=>'news', 'action'=>'show', 'id'=>$data['News']['id'], 'project_id' => $data['Project']['id']);
  }

//  # returns latest news for projects visible by user
	function latest($user,$count = 5)
	{
	    $param = array(
	      'order' => 'News.created_on DESC',
	      'conditions' => $this->Project->allowed_to_condition($user,'view_news'),
	      'limit' => $count
	    );
		return $this->find('all',$param);
	}
}
