<?php

namespace App\Banks;

use App\Banks\Contracts\BankInterface;
use App\DTO\TransferRequest;

class AlphaBank implements BankInterface
{
  public function sendMoney(TransferRequest $request): bool
  {

    return true;
  }
}
