<?php

namespace App\DTO;

use DateTime;

class TransferResult
{
  public function __construc(
    public bool $success,
    public string $message,
    public ?\DateTime $scheduleAt = null
  ) {}
}
