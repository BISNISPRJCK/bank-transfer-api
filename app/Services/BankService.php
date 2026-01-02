<?php

namespace App\Services;

use App\Banks\AlphaBank;
use App\Banks\BetaBank;
use App\Banks\CharlieBank;
use App\DTO\TransferRequest;
use App\DTO\TransferResult;
use App\Enums\Currency;
use DateTime;


class BankService
{

  protected array $banks;

  public function __construct()
  {
    $this->banks = [
      'A01' => new AlphaBank(),
      'B02' => new BetaBank(),
      'C03' => new CharlieBank(),

    ];
  }

  public function sendMoney(TransferRequest $request): bool
  {
    $bank = $this->banks[$request->fromBank->value] ?? null;

    if (!$bank) {
      return false;
    }

    return $bank->sendMoney($request);
  }

  public function handleOnline(TransferRequest $request): TransferResult
  {
    $time = (int) $request->transferTime->format('Hi');

    if ($time < 400) {
      return $this->delay($request, '04:00');
    }

    if (
      $request->currency === Currency::USD &&
      $time >= 1700 && $time <= 2159
    ) {
      return $this->delay($request, '22:00');
    }

    $priorityBanks = $this->getPriorityBanks($time);

    foreach ($priorityBanks as $bankCode) {
      $bank = $this->banks[$bankCode] ?? null;

      if (!$bank) {
        continue;
      }

      if ($bank->sendMoney($request)) {
        return new TransferResult(
          success: true,
          message: "Transfer berhasil via bank {$bankCode}"
        );
      }
    }

    return new TransferResult(
      success: false,
      message: 'Semua Bank Prioritas gagal'
    );
  }


  private function delay(TransferRequest $request, string $time): TransferResult
  {
    return new TransferResult(
      success: false,
      message: 'Transfer ditunda',
      scheduledAt: new DateTime($time)
    );
  }

  private function getPriorityBanks(int $time): array
  {
    return match (true) {
      $time >= 400 && $time <= 959 => ['A01', 'B02', 'C03'],
      $time >= 1000 && $time <= 1659 => ['B02', 'C03', 'A01'],
      $time >= 1700 && $time <= 2159 => ['C03', 'A01', 'B02'],
      $time >= 2200 => ['B02'],
      default => []
    };
  }
}
