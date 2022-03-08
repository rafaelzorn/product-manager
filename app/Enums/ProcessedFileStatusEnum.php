<?php

namespace App\Enums;

enum ProcessedFileStatusEnum: string
{
    case Waiting    = 'waiting';
    case Processing = 'processing';
    case Completed  = 'completed';
    case Failed     = 'failed';
}
