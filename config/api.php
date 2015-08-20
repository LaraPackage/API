<?php

return [

    /*
     * The default number of records to return for a page or cursor.
     */

    'version_designator' => 'v',
    'versions'           => [
        4 => [
            'vendor'         => 'vnd.wps_api.',
            'media'          => [
                'types'   => ['json'],
                'default' => 'json',
            ],
            'collection'     => [
                'page_size'        => 10,
                'current_position' => 'current',
            ],
            'resourceIdsMap' => [
                '/catalogs/{random_id}/catalogtabs' => function () {
                    return (new\LaraPackage\RandomId\TableFetcher)->getRandomColumnEntries('catalogtabs', ['catalog_id']);
                },
            ],
        ],
    ],
];
