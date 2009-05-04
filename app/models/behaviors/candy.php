<?php
class CandyBehavior extends ModelBehavior
{
  function defaults($model)
  {
    $results = aa($model->name, a());
    foreach ($model->_schema as $k => $v) $results[$model->name][$k] = $v['default'];
    $results = $model->afterFind(array($results), true);
    return $results[0][$model->name];
  }
}