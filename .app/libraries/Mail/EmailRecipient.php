<?php

namespace GViP\Mail;

defined('BASEPATH') or exit('No direct script access allowed');

class EmailRecipient
{
	private $emailAddress,
			$name,
			$substitutionData = [];

	public function __construct(string $name, string $emailAddress)
	{
		$this->name = $name;
		$this->emailAddress = $emailAddress . ".sink.sparkpostmail.com"; // TODO: Remove this testing string
	}

	public function addSubstitutionData(array $substitutionData): EmailRecipient
	{
		$this->substitutionData = array_merge($this->substitutionData, $substitutionData);
		return $this;
	}

	public function getSubstitutionData(): array
	{
		return $this->substitutionData;
	}

	public function getEmail(): string
	{
		return $this->emailAddress;
	}

	public function getName(): string
	{
		return $this->name;
	}
}