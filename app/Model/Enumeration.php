<?php
class Enumeration extends AppModel
{
  var $name = 'Enumeration';
  var $actsAs = array('List' => array(
    'scope' => 'Enumeration.opt'
  ));
  
#  acts_as_list :scope => 'opt = \'#{opt}\''
#
#  before_destroy :check_integrity
#  
#  validates_presence_of :opt, :name
#  validates_uniqueness_of :name, :scope => [:opt]
#  validates_length_of :name, :maximum => 30
#
  var  $OPTIONS = array(
    "IPRI" => array('label' => 'Issue priorities', 'model' => 'Issue', 'foreign_key' => 'priority_id'),
    "DCAT" => array('label' => 'Document categories', 'model' => 'Document', 'foreign_key' => 'category_id'),
    "ACTI" => array('label' => 'Activities (time tracking)', 'model' => 'TimeEntry', 'foreign_key' => 'activity_id')
  );
#  
  function get_values($option, $order = 'ASC') {
    return $this->find('all', array('conditions'=>array('opt'=>$option), 'order'=>"position $order"));
  }
#  
  function default_value($option) {
    return $this->find('first', array('conditions'=>array('opt'=>$option, 'is_default'=>true), 'order'=>'position'));
  }
#
#  def option_name
#    OPTIONS[self.opt][:label]
#  end
#
#  def before_save
#    if is_default? && is_default_changed?
#      Enumeration.update_all("is_default = #{connection.quoted_false}", {:opt => opt})
#    end
#  end
#  
	function objects_count($row){
		$model = ClassRegistry::init($this->OPTIONS[$row['Enumeration']['opt']]['model']);
		return $model->find(
			'count',
			array(
				'conditions' => array(
					$this->OPTIONS[$row['Enumeration']['opt']]['foreign_key'] => $row['Enumeration']['id']
				)
			)
		);
  }
#
  function in_use($row){
    return ($this->objects_count($row) != 0);
  }

  #  alias :destroy_without_reassign :destroy
  
 # Destroy the enumeration
 # If a enumeration is specified, objects are reassigned
	function destroy($row,$reassign_to = null) {
#    if reassign_to && reassign_to.is_a?(Enumeration)
		$model = ClassRegistry::init($this->OPTIONS[$row['Enumeration']['opt']]['model']);
		$model->updateAll(
			array(
				$this->OPTIONS[$row['Enumeration']['opt']]['foreign_key'] => $reassign_to
			),
			array(
				$this->OPTIONS[$row['Enumeration']['opt']]['foreign_key'] => $row['Enumeration']['id']
			)
		);
#    end
		$this->delete($row['Enumeration']['id']);
	}
#  
#  def <=>(enumeration)
#    position <=> enumeration.position
#  end
#  
#  def to_s; name end
#  
#private
#  def check_integrity
#    raise "Can't delete enumeration" if self.in_use?
#  end
#end
}
