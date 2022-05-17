<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StateKoreksi extends Enum
{
    const SUDAH_DIKOREKSI = 'SUDAH DIKOREKSI';
    const BELUM_DIKOREKSI = 'BELUM DIKOREKSI';
    const CAMPUR = 'CAMPUR';
}
