<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class PostDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }
    
    public function __invoke(array $context = []): OpenApi
    {
        /** @var OpenApi $openApi */
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();
        
        $schemas['Post']       = new \ArrayObject([
                                                      'type'       => 'object',
                                                      'properties' => [
                                                          'name'    => [
                                                              'type'    => 'string',
                                                              'example' => 'First post',
                                                          ],
                                                          'description' => [
                                                              'type'    => 'string',
                                                              'example' => 'Best',
                                                          ],
                                                      ],
                                                  ]);
        $pathItem = new Model\PathItem(
            ref:  'Post',
            get: new Model\Operation(
                      operationId: 'postCredentialsItem',
                      tags:        ['Post'],
                      responses:   [
                                       '200' => [
                                           'description' => 'Get posts',
                                           'content'     => [
                                               'application/json' => [
                                                   'schema' => [
                                                       '$ref' => '#/components/schemas/Post',
                                                   ],
                                               ],
                                           ],
                                       ],
                                   ],
                      summary:     'Get posts.',
                  ),
        );
        $openApi->getPaths()->addPath('/api/posts', $pathItem);
        
        return $openApi;
    }
}