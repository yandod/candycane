<?php
	echo $this->Html->meta(
		'atom',
		array(
			'project_id' => $this->request->params['project_id'],
			'?' => array_merge(
				$this->request->query,
				array(
					'key' => $rss_token,
					'format' => 'atom',
					'from' => null,
					'url' => null
				)
			)
		),
		array(
			'title' => $rss_title,
			'rel' => 'alternate'
		),
		false
	);
