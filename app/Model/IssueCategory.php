<?php
class IssueCategory extends AppModel
{
  var $name = 'IssueCategory';
  var $belongsTo = array(
    'Project',
    'AssignedTo' => array(
      'className'  => 'User',
      'foreignKey' => 'assigned_to_id',
    ),
  );
  var $validate = array(
    'name' => array(
      'rule' => array('maxLength',30),
      'required' => true,
      'allowEmpty' => false
    )
  );
#  belongs_to :project
#  belongs_to :assigned_to, :class_name => 'User', :foreign_key => 'assigned_to_id'
#  has_many :issues, :foreign_key => 'category_id', :dependent => :nullify
#  
#  validates_presence_of :name
#  validates_uniqueness_of :name, :scope => [:project_id]
#  validates_length_of :name, :maximum => 30
#  
#  alias :destroy_without_reassign :destroy
#  
#  # Destroy the category
#  # If a category is specified, issues are reassigned to this category
  function del_with_reassgin($id,$reassgin_to = null)
  {
    $this->bindModel(array(
       'hasMany' => array(
         'Issue' => array(
           'foreignKey' => 'category_id'
         )
       )
    ));
    $this->Issue->updateAll(
		array(
			'category_id' => $reassgin_to
		),
		array(
			'category_id' => $id
		)
	);
    $this->delete($id);
  }
#  
#  def <=>(category)
#    name <=> category.name
#  end
#  
#  def to_s; name end
#end
}
