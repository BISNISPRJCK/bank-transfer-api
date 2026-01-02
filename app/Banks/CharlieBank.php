<?php

namespace App\Banks;

use App\Banks\Contracts\BankInterface;
use App\DTO\TransferRequest;

class CharlieBank implements BankInterface
{
  public function sendMoney(TransferRequest $request): bool
  {
    // simulasi sukses / gagal
    return true;
  }
}
