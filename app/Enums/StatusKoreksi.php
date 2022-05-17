<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusKoreksi extends Enum
{
    const failed_to_start = 'FAILED TO START';
    const on_progress = "ON PROGRESS";
    const cancelled = "CANCELLED";
    const finished = "FINISHED";
    const error_occured = "ERROR OCCURED";
}
