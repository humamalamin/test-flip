<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursment extends Model
{
    use HasFactory;

    protected $fillable = [
        "amount",
        "status",
        "timestamp",
        "bank_code",
        "account_number",
        "beneficiary_name",
        "remark",
        "receipt",
        "time_served",
        "fee",
        "transaction_id"
    ];
}
