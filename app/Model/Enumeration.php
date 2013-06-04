<?php

class Enumeration extends AppModel
{
    var $name = 'Enumeration';
    var $actsAs = array('List' => array(
        'scope' => 'Enumeration.opt'
    ));

    var $OPTIONS = array(
        "IPRI" => array('label' => 'Issue priorities', 'model' => 'Issue', 'foreign_key' => 'priority_id'),
        "DCAT" => array('label' => 'Document categories', 'model' => 'Document', 'foreign_key' => 'category_id'),
        "ACTI" => array('label' => 'Activities (time tracking)', 'model' => 'TimeEntry', 'foreign_key' => 'activity_id')
    );

    function get_values($option, $order = 'ASC')
    {
        return $this->find('all', array('conditions' => array('opt' => $option), 'order' => "position $order"));
    }

    function default_value($option)
    {
        return $this->find('first', array('conditions' => array('opt' => $option, 'is_default' => true), 'order' => 'position'));
    }

    function objects_count($row)
    {
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

    function in_use($row)
    {
        return ($this->objects_count($row) != 0);
    }

    function destroy($row, $reassign_to = null)
    {
        $model = ClassRegistry::init($this->OPTIONS[$row['Enumeration']['opt']]['model']);
        $model->updateAll(
            array(
                $this->OPTIONS[$row['Enumeration']['opt']]['foreign_key'] => $reassign_to
            ),
            array(
                $this->OPTIONS[$row['Enumeration']['opt']]['foreign_key'] => $row['Enumeration']['id']
            )
        );
        $this->delete($row['Enumeration']['id']);
    }
}