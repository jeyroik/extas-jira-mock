<?php

return [
    '/rest/api/2/issue' => __DIR__ . '/../../resources/issues/create.json',
    '/rest/api/2/project/10114/statuses' => __DIR__ . '/../../resources/projects/statuses.json',
    '/rest/api/2/project/OCTI/statuses' => __DIR__ . '/../../resources/projects/statuses.json',
    '/rest/api/2/project' => __DIR__ . '/../../resources/projects/all.json',
    '/rest/api/2/issue/createmeta' => __DIR__ . '/../../resources/metas/create.json',
    '/rest/api/2/search' => __DIR__ . '/../../resources/issues/search.json',
    '/rest/agile/1.0/board' => __DIR__ . '/../../resources/boards/all.json',
    '/rest/agile/1.0/board/' => __DIR__ . '/../../resources/boards/all.json',
    '/rest/agile/1.0/sprint' => __DIR__ . '/../../resources/sprints/create.json',
    '/rest/agile/1.0/sprint/37' => __DIR__ . '/../../resources/sprints/update.json',
    '/rest/api/2/version' => __DIR__ . '/../../resources/versions/create.json',
    '/rest/api/2/version/37' => __DIR__ . '/../../resources/versions/update.json',
    '/rest/api/2/issue/OCTI-206' => function(){
        $json = file_get_contents('php://input');
        file_put_contents(
            __DIR__ . '/../../logs/update.log',
            '[' . date('Y-m-d H:i:s') . ']' . $json . PHP_EOL,
            FILE_APPEND
        );

        return '';
    },
    '/rest/api/2/issue/65125' => function(){
        $json = file_get_contents('php://input');
        file_put_contents(
            __DIR__ . '/../../logs/update.log',
            '[' . date('Y-m-d H:i:s') . ']' . $json . PHP_EOL,
            FILE_APPEND
        );

        return '';
    }
];
