<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Nelmio\Alice\DataLoaderInterface;
use Nelmio\Alice\FileLoaderInterface;
use Nelmio\Alice\FilesLoaderInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\ObjectSet;
use Nelmio\Alice\Throwable\LoadingThrowable;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait FixturesTrait
{
    public static DataLoaderInterface|FilesLoaderInterface|null|FileLoaderInterface $loader = null;

    public static ObjectSet|null $objectSet = null;

    public function getFixtureLoader(): FileLoaderInterface|FilesLoaderInterface|DataLoaderInterface
    {
        if (null === self::$loader) {
            self::$loader = new NativeLoader();
        }

        return self::$loader;
    }

    /**
     * @throws LoadingThrowable
     */
    public function loadFixtures(): ObjectSet
    {
        $loader = $this->getFixtureLoader();
        $files = [];
        $endString = '.yaml';
        foreach (scandir(self::FIXTURES_PATH) as $filename) {
            if ((str_ends_with($filename, $endString))) {
                $files[] = self::FIXTURES_PATH.'/'.$filename;
            }
        }
        /* @var ObjectSet $objects */
        return $loader->loadFiles($files);
    }

    /**
     * @throws LoadingThrowable
     */
    public function getFixtureValue(string $name): ?object
    {
        if (null === self::$objectSet) {
            self::$objectSet = $this->loadFixtures();
        }

        return self::$objectSet->getObjects()[$name] ?? null;
    }

    /**
     * @throws LoadingThrowable
     */
    public function getFixtureValueField(string $name, string $path): mixed
    {
        $object = $this->getFixtureValue($name);
        self::assertNotNull($object, sprintf('There is no %s fixture, so cant access %s path', $name, $path));
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($object, $path);
    }
}
