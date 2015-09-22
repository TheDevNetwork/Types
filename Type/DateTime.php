<?php

namespace Tdn\PhpTypes\Type;

use Carbon\Carbon;

/**
 * Alias class for carbon
 *
 * Same effect as doing class_alias('Carbon\\Carbon', 'DateTime')
 *
 * Class DateTime.
 */
class DateTime extends Carbon
{
}
