<?php

namespace Domain\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;

/**
 * Class Repository
 * @package Domain\Repository
 */
class Repository
{
    private EntityManager $entity_manager;
    private string $class_name;
    private string $class_alias;
    private int $hydration_mode;

    public function __construct(EntityManager $entity_manager)
    {
        $this->entity_manager = $entity_manager;
        $this->hydration_mode = AbstractQuery::HYDRATE_ARRAY;
        $this->setClassName(self::class);
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entity_manager;
    }

    public function getClassName(): string
    {
        return $this->class_name;
    }

    protected function setClassName(string $class_name): Repository
    {
        $this->class_name = $class_name;
        $this->setClassAlias();
        return $this;
    }

    public function getClassAlias(): string
    {
        return $this->class_alias;
    }

    private function setClassAlias(): Repository
    {
        $class_name_array = explode('\\', $this->class_name);
        $this->class_alias = strtolower(end($class_name_array)[0]);
        return $this;
    }

    public function getHydrationMode(): ?int
    {
        return $this->hydration_mode;
    }
}