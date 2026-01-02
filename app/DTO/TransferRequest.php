<?php

namespace App\DTO;

use App\Enums\BankCode;
use App\Enums\Currency;

class TransferRequest
{
  public function __construct(
    public BankCode $fromBank,
    public BankCode $toBank,
    public string $toAccountNumber,
    public float $amount,
    public Currency $currency,
    public \DateTime $transferTime,
  ) {}
}
