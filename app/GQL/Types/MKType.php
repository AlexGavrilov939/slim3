<?php

namespace App\GQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MKType extends AbstractType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'total' => [
                    'type' => Type::int()
                ],
                'items' => [
                    'type' => Type::listOf(
                        self::getSingleMKType()
                    )
                ]
            ]
        ]);
    }

    public static function getSingleMKType()
    {
        return new ObjectType([
            'name' => 'MKSingle',
            'description' => 'Маркетинговая кампания',
            'fields' => [
                'type' =>  [
                    'type' => Type::string(),
                    'description' => 'Тип маркетинговой кампании'
                ],
                'id' => Type::string(), // 'Id'
                'responsible' => [
                    'type' => Type::string(),
                    'description' => 'Ответственный'
                ],
                'dueDate' => [
                    'type' => Type::string(),
                    'description' => 'Срок исполнения'
                ],
                'createDate' => [
                    'type' => Type::string(),
                    'description' => 'Дата создания'
                ],
                'status' => [
                    'type' => Type::string(),
                    'description' => 'Статус'
                ]
            ]
        ]);
    }

    public static function getMKListType()
    {
        return new ObjectType([
            'name' => 'MKList',
            'description' => 'Список маркетинговый кампаний',
            'fields' => [
                'total' => [
                    'description' => 'Общее количество записей',
                    'type' => Type::int()
                ],
                'items' => [
                    'type' => Type::listOf(self::getSingleMKType())
                ]
            ]
        ]);
    }
}