<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
  protected $fillable = [
    'from_bank',
    'to_bank',
    'account_number',
    'amount',
    'currency',
    'status',
    'scheduled_at'
  ];
}
