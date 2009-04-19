<?php
/* vim: fenc=utf8 ff=unix
 */
/*
module ReportsHelper

  def aggregate(data, criteria)
    a = 0
    data.each { |row|
      match = 1
      criteria.each { |k, v|
        match = 0 unless (row[k].to_s == v.to_s) || (k == 'closed' && row[k] == (v == 0 ? "f" : "t"))
      } unless criteria.nil?
      a = a + row["total"].to_i if match == 1
    } unless data.nil?
    a
  end

  def aggregate_link(data, criteria, *args)
    a = aggregate data, criteria
    a > 0 ? link_to(a, *args) : '-'
  end
end
*/
class ReportsHelper extends AppHelper
{
  var $helpers = array('Html');

  /**
   * aggregate
   *
   * @param  array $data
   * @param  array $criteria
   * @return integer
   */
  function aggregate($data, $criteria)
  {
    if (empty($criteria)) {
      return 0;
    }

    $a = 0;
    foreach ($data as $row) {
      $match = 0;
      foreach ($criteria as $k => $v) {
        if ($row[$k] == $v) {
          $match++;
        }
      }
      $a = $a + (count($criteria) == $match ? $row['total'] : 0);
    }
    return $a;
  }   

  /**
   * aggregate link
   *
   * @param  array $data
   * @param  array $criteria
   * @param  array $args
   * @return string
   */
  function aggregate_link($data, $criteria, $args)
  {
    $a = $this->aggregate($data, $criteria);
    if ($a > 0) {
      return $this->Html->link($a, $args);
    } else {
      return '-';
    }
  }
}
