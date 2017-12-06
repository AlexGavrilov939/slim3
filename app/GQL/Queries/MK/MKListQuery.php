<?php

namespace App\GQL\Queries\MK;

use App\GQL\Queries\GQLQueryInterface;
use App\GQL\Types\MKType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class MKListQuery
{
    public static function get()
    {
        return [
            'type' => MKType::getInstance(),
            'description' => 'Список маркетинговых кампаний',
            'args' => [
                'filter' => new InputObjectType([
                   'name' => 'Filter',
                   'fields' => [
                       'date_due_from' => ['type' => Type::string()],
                       'date_due_to' => ['type' => Type::string()],
                       'date_create_from' => ['type' => Type::string()],
                       'date_create_to' => ['type' => Type::string()],
                       'type' => ['type' => Type::string()], // Inner Outer
                       'status' => ['type' => Type::string()], // all, active, archive,
                       'manager' => ['type' => Type::int()]
                   ]
                ]),
            ],
            'resolve' => function($root, $args) {
                return [
                    'total' => 450,
                    'items' => [
                        [
                            'type' => 'Тип',
                            'id' => 'Id',
                            'responsible' => 'Ответственный',
                            'dueDate' => 'Срок исполнения',
                            'createDate' => 'Дата создания',
                            'status' => 'Статус'
                        ]
                    ],
                ];
            }
        ];
    }
}