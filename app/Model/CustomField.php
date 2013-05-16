<?php

class CustomField extends AppModel
{
#  has_many :custom_values, :dependent => :delete_all
    var $name = 'CustomField';
    var $actsAs = array('List');
    var $validate = array(
        'name' => array(
            'validates_presence_of'   => array('rule' => array('notEmpty')),
            'validates_uniqueness_of' => array('rule' => array('isUnique')),
            'validates_length_of'     => array('rule' => array('maxLength', 30)),
            'validates_format_of'     => array('rule' => array('custom', '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}\s\.\'\-]*$/iu')),
        ),
        'field_format' => array(
            'validates_invalid_of' => array('rule' => array('validate_field_format')),
        ),
        'possible_values' => array(
            'validates_not_empty' => array('rule' => array('validate_possible_values')),

        ),
        'default_value' => array(
            'validates_invalid_of' => array('rule' => array('validate_default_value')),
        ),
    );

    public function validate_field_format($data)
    {
        return in_array($data['field_format'], array_keys($this->FIELD_FORMATS));
    }

    public function validate_possible_values($data)
    {
        if ($this->data[$this->name]['field_format'] === 'list') {
            if (empty($data['possible_values']) || !is_array($data['possible_values'])) {
                return false;
            }
        }
        return true;
    }

    public function validate_default_value($data)
    {
        # validate default value
        if (empty($this->data[$this->name]['default_value'])) {
            return true;
        }
        $CustomValue = ClassRegistry::init('CustomValue');
        $CustomValue->set(array('CustomValue' => array('value' => $this->data[$this->name]['default_value']), $this->name => $this->data[$this->name]));

        if ($CustomValue->validates()) {
            return true;
        }
        return false;
    }


    var $FIELD_FORMATS = array(
        "string" => array('name' => 'Text',      'order' => 1),
        "text"   => array('name' => 'Long text', 'order' => 2),
        "int"    => array('name' => 'Integer',   'order' => 3),
        "float"  => array('name' => 'Float',     'order' => 4),
        "list"   => array('name' => 'List',      'order' => 5),
        "date"   => array('name' => 'Date',      'order' => 6),
        "bool"   => array('name' => 'Boolean',   'order' => 7),
    );

    public function group_by($fields, $name)
    {
        $results = array();
        foreach ($fields as $field) {
            $results[$field[$this->name][$name]][] = $field;
        }
        return $results;
    }

    public function count_project(&$list)
    {
        if (!empty($list['IssueCustomField'])) {
            $this->bindModel(array('hasMany' => array('CustomFieldsProject')), false);
            foreach ($list['IssueCustomField'] as $i => $field) {
                $conditions = array('custom_field_id' => $field[$this->name]['id']);
                $list['IssueCustomField'][$i]['Project']['count_all'] = $this->CustomFieldsProject->find('count', array('conditions' => $conditions));
            }
        }
    }

    var $__add_trackers = array();
    var $__del_trackers = array();

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->name]['type']) && $this->data[$this->name]['type'] == 'IssueCustomField' && !empty($this->data['CustomField']['id'])) {
            $this->bindModel(array('hasMany' => array('CustomFieldsTracker')), false);
            $assoc_trackers       = Set::extract('{n}.CustomFieldsTracker.tracker_id', $this->CustomFieldsTracker->find('all', array('conditions' => array('custom_field_id' => $this->data['CustomField']['id']))));
            $tracker_ids          = empty($this->data[$this->name]['tracker_id']) ? array() : $this->data[$this->name]['tracker_id'];
            $this->__add_trackers = array_diff($tracker_ids, $assoc_trackers ? $assoc_trackers : array());
            $this->__del_trackers = array_diff($assoc_trackers ? $assoc_trackers : array(), $tracker_ids);
        }
        unset($this->data[$this->name]['tracker_id']);

        App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
        if (!empty($this->data[$this->name]['possible_values']) && $this->data[$this->name]['field_format'] == 'list') {
            if (empty($this->data[$this->name]['possible_values'][count($this->data[$this->name]['possible_values']) - 1])) {
                unset($this->data[$this->name]['possible_values'][count($this->data[$this->name]['possible_values']) - 1]);
            }
            $this->data[$this->name]['possible_values'] = Spyc::YAMLDump($this->data[$this->name]['possible_values'], true);
        } else {
            $this->data[$this->name]['possible_values'] = '--- []';
        }

        if (empty($this->data[$this->name]['min_length'])) {
            $this->data[$this->name]['min_length'] = 0;
        }
        if (empty($this->data[$this->name]['max_length'])) {
            $this->data[$this->name]['max_length'] = 0;
        }

        return true;
    }

    public function afterSave($created)
    {
        $id = $this->id;
        if ($created) {
            $id = $this->getLastInsertID();
        }
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        foreach ($this->__del_trackers as $del) {
            $this->CustomFieldsTracker->deleteAll(array('custom_field_id' => $id, 'tracker_id' => $del), false);
        }
        foreach ($this->__add_trackers as $add) {
            $db->create($this->CustomFieldsTracker, array('custom_field_id', 'tracker_id'), array($id, $add));
        }
    }

    public function beforeValidate($options = array())
    {
        # remove empty values
        if (!empty($this->data[$this->name]['possible_values'])) {
            $possible_values = array();
            if (is_array($this->data[$this->name]['possible_values'])) {
                foreach ($this->data[$this->name]['possible_values'] as $v) {
                    if (!empty($v)) {
                        $possible_values[] = $v;
                    }
                }
            }
            $this->data[$this->name]['possible_values'] = $possible_values;
        }
        # make sure these fields are not searchable
        if (in_array($this->data[$this->name]['field_format'], array('int', 'float', 'date', 'bool'))) {
            $this->data[$this->name]['searchable'] = false;
        }
        return true;
    }
}
