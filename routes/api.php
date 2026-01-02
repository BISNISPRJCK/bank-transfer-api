<?php

use App\Services\TransferService;
use App\DTO\TransferRequest;
use App\DTO\TransferResult;
use App\Enums\BankCode;
use App\Enums\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/transfer', function (Request $request, TransferService $service) {
  $dto = new TransferRequest(
    fromBank: BankCode::from($request->from_bank),
    toBank: BankCode::from($request->to_bank),
    toAccountNumber: $request->to_account,
    amount: $request->amount,
    currency: Currency::from($request->currency),
    transferTime: new \DateTime($request->time)
  );

  return response()->json(
    $service->transfer($dto)
  );
});
