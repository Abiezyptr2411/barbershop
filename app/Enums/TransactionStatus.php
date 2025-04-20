<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case SETTLEMENT = 'settlement';
    case CANCEL = 'cancel';
    case DENY = 'deny';
    case EXPIRE = 'expire';
    case FAILURE = 'failure';
    case SUCCESS = 'sukses'; 
}
