<?php

namespace  App\GQL\Types;
use GraphQL\Type\Definition\ObjectType;

/**
 * Class TypeRegistry
 * http://webonyx.github.io/graphql-php/type-system/#type-registry
 *
 * @method MKType MK()
 */
class AbstractType extends ObjectType
{
    public static function getInstance()
    {
        static $typeInstance = null;
        if (is_null($typeInstance)) {
            $typeInstance = new static();
        }

        return $typeInstance;
    }
}