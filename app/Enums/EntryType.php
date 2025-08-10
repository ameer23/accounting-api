<?php

namespace App\Enums;

enum EntryType: string
{
    case Debit = 'debit';
    case Credit = 'credit';
}