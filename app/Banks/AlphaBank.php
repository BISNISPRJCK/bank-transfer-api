<?php

namespace App\Banks;

use App\Banks\Contracts\BankInterface;
use App\DTO\TransferRequest;

class AlphaBank implements BankInterface
{
  public function sendMoney(TransferRequest $request): bool
  {

    // simulasi alpha gagal untuk online transfer

    if ($request->fromBank !== $request->toBank) {
      return false;
    }

    return true;
  }
}
