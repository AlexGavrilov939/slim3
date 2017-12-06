<?php

namespace App\GQL;

use App\GQL\Queries\MK\MKListQuery;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GQLServer {
    static function processRequest(ServerRequestInterface $request, ResponseInterface $response) {
        return self::getInstance()->processPsrRequest($request, $response, $response->getBody());
    }

    /**
     * @return StandardServer
     */
    static private function getInstance() {
        static $instance;
        if (!$instance) {
            $instance = new StandardServer([
                'schema' => new Schema([
                    'query' => new ObjectType([
                        'name' => 'RootQueryType',
                        'fields' => [
                            'hello' => [
                                'type' => Type::string(),
                                'resolve' => function () {
                                    return 'world';
                                }
                            ],
                            'mkList' => MKListQuery::get(),
//                            'mkList' => MKType::getInstance()
                        ]
                    ]),
                    'mutation' => new ObjectType([
                        'name' => 'Mutation',
                        'fields' => [
                            'sum' => [
                                'type' => Type::int(),
                                'args' => [
                                    'x' => ['type' => Type::int()],
                                    'y' => ['type' => Type::int()]
                                ],
                                'resolve' => function($root, $args) {
                                    return $args['x'] + $args['y'];
                                }
                            ]
                        ]
                    ])
                ])
            ]);
        }

        return $instance;
    }

}