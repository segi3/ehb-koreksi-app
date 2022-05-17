<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResponseStatus extends Enum
{
    const SUCCESS = "SUCCESS";
    const INTERNAL_SERVER_ERROR = "INTERNAL SERVER ERROR";
    const DENIED = "DENIED";
}
