<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit;

use Kommandhub\Foundation\KommandhubFoundationSW;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Plugin;

class KommandhubFoundationSWTest extends TestCase
{
    public function testPluginIsInstantiable(): void
    {
        $plugin = new KommandhubFoundationSW(true, '');
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    public function testExecuteComposerCommandsReturnsTrue(): void
    {
        $plugin = new KommandhubFoundationSW(true, '');
        $this->assertTrue($plugin->executeComposerCommands());
    }
}
