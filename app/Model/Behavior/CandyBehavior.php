<?php
/**
 * Common Behabior
 */
class CandyBehavior extends ModelBehavior
{

	public function defaults($model) {
		$results = aa($model->name, array());
		foreach ($model->_schema as $k => $v) {
			$results[$model->name][$k] = $v['default'];
		}
		$results = $model->afterFind(array($results), true);
		return $results[0][$model->name];
	}

}