<?php

use GViP\Analytics;

class RecommendationFeedback_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function store(int $recipientUserId, string $recommendationType, int $targetId, string $uporDown)
	{
		if ($recommendationType != 'expert') throw new \Exception('The system does not currently store feedback on the type of recommendation you requested.');

		$this->load->model('expertise_model');
		$targetExpert = $this->expertise_model->find($targetId);
		$targetName = $targetExpert['firstname'] . " " . $targetExpert['lastname'];

		$analytics = new Analytics;
		$analytics->track([
			'userId' => $recipientUserId,
			'event' => 'Recommendation Feedback Given',
			'properties' => [
				'Category' => 'Expert',
				'Target Id' => $targetId,
				'Target Name' => $targetName,
				'Location' => 'Email',
				'Feedback' => $uporDown
			]
		]);
	}

}