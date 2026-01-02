<?php

namespace App\Banks\Contracts;

use App\DTO\TransferRequest;

interface BankInterface
{
  public function sendMoney(TransferRequest $requuest): bool;
}
