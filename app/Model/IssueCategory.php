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
            'rule'       => array('maxLength', 30),
            'required'   => true,
            'allowEmpty' => false
        )
    );

    function del_with_reassgin($id, $reassgin_to = null)
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
}
