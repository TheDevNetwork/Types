<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Math\Library;

use Tdn\PhpTypes\Math\MathInterface;

/**
 * Interface MathLibraryInterface.
 */
interface MathLibraryInterface extends MathInterface
{
    /**
     * Sees if library is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsOperationType(string $type): bool;
}
