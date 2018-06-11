<?php

namespace AppBundle\Entity;


use Doctrine\Common\Annotations\AnnotationReader;

class EntityMerger
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {

        $this->annotationReader = $annotationReader;
    }

    /**
     * @param $entity
     * @param $changes
     */
    public function merger($entity, $changes): void
    {
        $entityClassName = get_class($entity);

        if (false === $entityClassName) {
            throw  new \InvalidArgumentException("$entity is not a class");
        }

        $changesClassName = get_class($changes);

        if (false === $changesClassName) {
            throw  new \InvalidArgumentException("$changes is not a class");
        }

        // if changes object is of the same classes as $entity
        if (!is_a($changes, $entityClassName)) {
            throw  new \InvalidArgumentException(
                "Cannot merge object of class $changesClassName with object of class $entity"
            );
        }

        $entityReflection = new \ReflectionObject($entity);
        $changesReflection = new \ReflectionObject($changes);

        foreach ($changesReflection->getProperties() as $changedProperty) {
            $changedProperty->setAccessible(true);
            $changedPropertyValue = $changedProperty->getValue($changes);

            // Ignore $changes properties with null value
            if (null === $changedProperty->getValue($changes)) {
                continue;
            }

            // Ignore $changes property if it's not present on $entity
            if (!$entityReflection->hasProperty($changedProperty->getName())) {
                continue;
            }

            $entityProperty = $entityReflection->getProperty($changedProperty->getName());
            $annotation= $this->annotationReader->getPropertyAnnotation($entityProperty, Id::class);

            // Ignore $changes property that has Doctrine $Id annotation
            if(null !== $annotation) {
                continue;
            }

            $entityProperty->setAccessible(true);
            $entityProperty->setValue($entity, $changedPropertyValue);
        }
    }
}