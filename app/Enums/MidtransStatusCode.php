<?php

namespace App\Enums;

enum MidtransStatusCode: string
{
    case SUCCESS = '200';
    case PENDING = '201';
    case DENIED = '202';
    case EXPIRED = '407';
    case FAILED = '500';
}
