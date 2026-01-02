<?php

namespace App\Services;

use App\DTO\TransferRequest;
use App\DTO\TransferResult;
use App\Enums\Currency;
use App\Models\TransferLog;
use DateTime;

class TransferService
{

  public function __construct(
    protected BankService $bankService
  ) {}

  public function transfer(TransferRequest $request): TransferResult
  {
    // cek inhouse

    if ($request->fromBank === $request->toBank) {
      return $this->handleInhouse($request);
    }

    // online transfer
    return $this->handleOnlineTransfer($request);
  }

  private function handleInhouse(TransferRequest $request): TransferResult
  {

    $success = $this->bankService->sendMoney($request);

    $this->log($request, $success ? 'SUCCESS' : 'FAILED');

    return new TransferResult(
      success: $success,
      message: $success
        ? 'Transfer inhouse berhasil'
        : 'Transfer inhouse gagal'
    );
  }

  private function handleOnlineTransfer(TransferRequest $request): TransferResult
  {
    // logic waktu 
    return $this->bankService->handleOnline($request);
  }

  private function log(TransferRequest $request, string $status, ?DateTime $scheduledAt = null): void
  {
    TransferLog::create([
      'from_bank' => $request->fromBank->value,
      'to_bank' => $request->toBank->value,
      'account_number' => $request->toAccountNumber,
      'amount' => $request->amount,
      'currency' => $request->currency->value,
      'status' => $status,
      'scheduled_at' => $scheduledAt


    ]);
  }
}
