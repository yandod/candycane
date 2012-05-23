<?php
class Version extends AppModel {
  var $name = 'Version';

  var $belongsTo = array(
    'Project',
  );
  var $hasMany = array(
    'FixedIssue' => array(
      'className'=>'Issue',
      'foreignKey'=>'fixed_version_id',
    ),
  );
  var $validate = array(
    'name' => array(
      'rule' => array('maxLength', 60),
    ),
  );

  function afterFind($results, $primary = false)
  {
    if (isset($results['id'])) {
      $results = $this->afterFindOne($results);
    } else {
      foreach($results as $key=>$result) {
        if (isset($result[$this->alias][0])) {
          foreach($result[$this->alias] as $key2=>$version) {
            $results[$key][$this->alias][$key2] = $this->afterFindOne($version);
          }
        } else {
          $results[$key][$this->alias] = $this->afterFindOne($results[$key][$this->alias]);
        }
      }
    }

    return $results;
  }

	function afterFindOne($result) {
		if (empty($result) || empty($result['id'])) {
			return $result;
		}

		if (empty($result['effective_date'])) {
			$result['effective_date'] = null;
		}
		
		

    $result['start_date'] = $result['effective_date'];
    $result['due_date'] = $result['effective_date'];

    $time_entry = ClassRegistry::init('TimeEntry');
    $time_entry->bindModel(array('belongsTo' => array('Issue')));
    $time_entries = $time_entry->find('first', array(
      'fields' => array('sum(hours) as sum' ),
      'conditions'=>array(
          'Issue.fixed_version_id' => $result['id']
        ),
    ));
    $result['spent_hours'] = $time_entries[0]['sum'];
    $result['open_issues_count'] = $this->FixedIssue->find('count', array(
      'conditions'=>array(
        'fixed_version_id'=>$result['id'],
        'Status.is_closed'=>false,
      ),
    ));
    $result['closed_issues_count'] = $this->FixedIssue->find('count', array(
      'conditions'=>array(
        'fixed_version_id'=>$result['id'],
        'Status.is_closed'=>true,
      ),
    ));

   if (($result['open_issues_count'] + $result['closed_issues_count']) == 0) {
     $result['completed_pourcent'] = 0;
   } else if ($result['open_issues_count'] == 0) {
     $result['completed_pourcent'] = 100;
   } else {
     $sum = $this->FixedIssue->find('first', array(
      'fields' => array(
       'sum(FixedIssue.done_ratio) as sum'
     ),
      'conditions'=>array(
        'fixed_version_id'=>$result['id'],
        'Status.is_closed'=>false,
      ),
      'recursive' => 0
     ));
     $result['completed_pourcent'] = ($result['closed_issues_count'] * 100 + $sum[0]['sum']) / ($result['open_issues_count'] + $result['closed_issues_count']);
   }
#  def completed_pourcent
#    if fixed_issues.count == 0
#      0
#    elsif open_issues_count == 0
#      100
#    else
#      (closed_issues_count * 100 + Issue.sum('done_ratio', :include => 'status', :conditions => ["fixed_version_id = ? AND is_closed = ?", id, false]).to_f) / fixed_issues.count
#    end
#  end

   if (($result['open_issues_count'] + $result['closed_issues_count']) == 0) {
     $result['closed_pourcent'] = 0;
   } else {
     $result['closed_pourcent'] = $result['closed_issues_count'] * 100 / ($result['open_issues_count'] + $result['closed_issues_count']);
   }
   
#  def closed_pourcent
#    if fixed_issues.count == 0
#      0
#    else
#      closed_issues_count * 100.0 / fixed_issues.count
#    end
#  end
    
    //effective_date && (effective_date <= Date.today) && (open_issues_count == 0)
    if (empty($result['effective_date'])) {
      $result['completed'] = false;
    } else if (strtotime($result['effective_date']) >= time()) {
      $result['completed'] = false;
    } else {
      $result['completed'] = ($result['open_issues_count'] == 0);
    }
#  def estimated_hours
#    @estimated_hours ||= fixed_issues.sum(:estimated_hours).to_f
#  end
    $issues = $this->FixedIssue->find('first', array(
        'fields'=>'sum(estimated_hours) as sum',
        'conditions' => array(
            'FixedIssue.fixed_version_id' => $result['id']
        )
        ));
    $result['estimated_hours'] = $issues[0]['sum'];

#  def spent_hours
#    @spent_hours ||= TimeEntry.sum(:hours, :include => :issue, :conditions => ["#{Issue.table_name}.fixed_version_id = ?", id]).to_f
#  end

#  def completed?
#    effective_date && (effective_date <= Date.today) && (open_issues_count == 0)
#  end
#  def open_issues_count
#    @open_issues_count ||= Issue.count(:all, :conditions => ["fixed_version_id = ? AND is_closed = ?", self.id, false], :include => :status)
#  end
    return $result;
  }
 
  function sort($a,$b){
      
      return (strtotime($a['effective_date']) > strtotime($b['effective_date'])) ? -1:1;
  }
}

