<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Tests\DTO\PreparedObject;

/**
 * Trait OrmTrait.
 */
trait OrmTrait
{
    protected function getObject(string $class, array $criteria): mixed
    {
        $preparedCriteria = $criteria;
        foreach ($preparedCriteria as $key => $value) {
            if ($value instanceof PreparedObject) {
                $preparedCriteria[$key] = $this->getObject($value->class, $value->criteria);
            }
        }

        $object = self::$container->get('doctrine.orm.entity_manager')->getRepository($class)->findOneBy($preparedCriteria);
        self::assertNotNull($object, sprintf('There is no %s with %s', $class, http_build_query($criteria, '', ' and ')));

        return $object;
    }
}
