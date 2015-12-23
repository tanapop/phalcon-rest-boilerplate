<?php

namespace App\Bootstrap;

use App\Constants\Resources;
use App\Model\Item;
use App\Model\Product;
use App\Model\User;
use App\Transformers\UserTransformer;
use Phalcon\Acl;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconRest\Api;
use PhalconRest\Api\Resource;
use App\Constants\AclRoles;
use PhalconRest\Api\Endpoint;

class ResourceBootstrap extends \App\Bootstrap
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api
            ->resource(Resource::crud('/users', Resources::USER)
                ->deny(AclRoles::UNAUTHORIZED, AclRoles::AUTHORIZED, AclRoles::USER)
                ->model(User::class)
                ->transformer(UserTransformer::class)
                ->singleKey('user')
                ->multipleKey('users')
                ->endpoint(Endpoint::get('/me', 'me')
                    ->allow(AclRoles::USER)
                )
                ->endpoint(Endpoint::post('/authenticate', 'authenticate')
                    ->deny(AclRoles::AUTHORIZED)
                )
            )

            ->resource(Resource::factory('/items', Resources::ITEM)
                ->model(Item::class)
                ->singleKey('item')
                ->multipleKey('items')
                ->endpoint(Endpoint::all())
                ->endpoint(Endpoint::find())
            )

            ->resource(Resource::crud('/products', Resources::PRODUCT)
                ->model(Product::class)
                ->singleKey('product')
                ->multipleKey('products')
            );
    }
}