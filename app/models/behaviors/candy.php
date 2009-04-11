<?php
class CandyBehavior extends ModelBehavior
{
  function defaults($model)
  {
    $results = aa($model->name, a());
    foreach ($model->_schema as $k => $v) $results[$model->name][$k] = $v['default'];
    return $results;
  }
}