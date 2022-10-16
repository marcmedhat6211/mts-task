<?php

namespace App\Core;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    /**
     * @throws \Exception
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function resolve(string $id)
    {
        // Inspect the class that we are trying to get from the container
        $reflectionClass = new \ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception("Class '{$id}' is not instantiable");
        }

        // Inspect the constructor of the class
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $id;
        }

        // Inspect the constructor parameters (dependencies)
        $parameters = $constructor->getParameters();
        if (!$parameters) {
            return new $id;
        }

        // If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = array_map(function (\ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new \Exception("Failed to resolve class {'$id'} because param '{$name}' is missing a type hint");
            }

            if ($type instanceof \ReflectionUnionType) {
                throw new \Exception("Failed to resolve class {'$id'} because of union type for param '{$name}'");
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new \Exception("Failed to resolve class {'$id'} because of an invalid param '{$name}'");
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}