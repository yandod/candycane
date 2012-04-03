<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class ProjectHelper extends AppHelper
{

  function identifier_frozen($project)
  {
    if (is_null($project)) { return true; }

    $validate = false;

    if (empty($project['identifier'])) {
      $validate = true;
    } else {
      $validate = !(preg_match('/^\d*$/', $project['identifier']));
    }

    return ($validate && !(empty($project['id']) || empty($project['identifier'])));
// if !identifier.blank? && identifier.match(/^\d*$/)
  }
#  def identifier_frozen?
#    errors[:identifier].nil? && !(new_record? || identifier.blank?)
#  end
}
