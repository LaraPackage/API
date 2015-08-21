<?php

return [

    /*
     * The default number of records to return for a page or cursor.
     */

    'version_designator' => 'v',
    'versions'           => [
        1 => [
            'vendor'         => 'vnd.your_api.',
            'media'          => [
                'types'   => ['json'],
                'default' => 'json',
            ],
            'collection'     => [
                'page_size'        => 10,
                'current_position' => 'current',
            ],
            // This is typically used for testing for getting ids for routes that are non-standard.
            'resourceIdsMap' => [
                '/your/{random_id}/route' => function () {
                    return (new \LaraPackage\RandomId\TableFetcher)->getRandomColumnEntries('table', ['column_id']);
                },
            ],
            'factory'        => \LaraPackage\Api\Factory\VersionFactory::class,
        ],
    ],
];
