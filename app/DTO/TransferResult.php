<?php

namespace App\DTO;

use DateTime;

class TransferResult
{
  public function __construct(
    public bool $success,
    public string $message,
    public ?DateTime $scheduledAt = null
  ) {}
}
