<?php

declare(strict_types=1);

namespace Kommandhub\Foundation;

use Shopware\Core\Framework\Plugin;

/**
 * Kommandhub Foundation plugin for Shopware 6.
 *
 * This plugin provides shared logic, abstract classes, and events
 * used across various Kommandhub payment integration plugins.
 */
class KommandhubFoundationSW extends Plugin
{
    public function executeComposerCommands(): bool
    {
        return true;
    }
}
