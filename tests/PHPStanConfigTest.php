<?php

declare(strict_types=1);

namespace Bellangelo\TypeCoverageAutoUpdate\Tests;

use Bellangelo\TypeCoverageAutoUpdate\Exceptions\CouldNotOpenConfigurationFileException;
use Bellangelo\TypeCoverageAutoUpdate\PHPStanConfig;
use PHPUnit\Framework\TestCase;

class PHPStanConfigTest extends TestCase
{
    public function testConfigurationFileDoesNotExist(): void
    {
        $this->expectException(CouldNotOpenConfigurationFileException::class);

        new PHPStanConfig('a-file-that-does-not-exist.neon');
    }

    public function testCanReadTypeCoverageFromFile(): void
    {
        $config = new PHPStanConfig(__DIR__ . '/data/phpstan.neon');

        $this->assertTrue($config->hasParameterTypeCoverage());
        $this->assertTrue($config->hasReturnTypeCoverage());
        $this->assertTrue($config->hasPropertyTypeCoverage());
        $this->assertTrue($config->hasDeclareStrictTypesCoverage());
        $this->assertSame(7, $config->getParameterTypeCoverage());
        $this->assertSame(5, $config->getPropertyTypeCoverage());
        $this->assertSame(3, $config->getReturnTypeCoverage());
        $this->assertSame(1, $config->getDeclareStrictTypesCoverage());
    }

    public function testMissingValuesFromFile(): void
    {
        $config = new PHPStanConfig(__DIR__ . '/data/config-with-missing-values.neon');

        $this->assertFalse($config->hasReturnTypeCoverage());
        $this->assertTrue($config->hasParameterTypeCoverage());
        $this->assertTrue($config->hasPropertyTypeCoverage());
        $this->assertTrue($config->hasDeclareStrictTypesCoverage());
        $this->assertSame(null, $config->getReturnTypeCoverage());
        $this->assertSame(0, $config->getParameterTypeCoverage());
        $this->assertSame(5, $config->getPropertyTypeCoverage());
        $this->assertSame(1, $config->getDeclareStrictTypesCoverage());
    }
}