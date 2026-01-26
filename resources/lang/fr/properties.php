<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Properties Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
    'properties' => [
        'total_found' => 'Annonces'
    ],
    'report_ad' => 'Signaler l\'annonce',
    'publish' => [
        'title' => 'publier',
        'property_title' => 'Qualification',
        'start_date_title' => 'date de début',
        'select_days_title' => 'sélectionner des jours',
        'days_title' => 'Jour',
    ],
    'actions' => [
        'created' => [
            'success' => 'Votre annonce a été créée avec succès',
            'error' => 'Votre annonce n\'a pas pu être créée.',
        ],
        'updated' => [
            'success' => 'Votre annonce a été mise à jour avec succès',
            'error' => 'Votre annonce n\'a pas pu être mise à jour',
        ],
        'deleted' => [
            'success' => 'Votre annonce a été supprimée avec succès',
            'error' => 'Votre annonce n\'a pas pu être supprimée',
        ],
        'published' => [
            'success' => 'Votre annonce a été publiée avec succès',
            'error' => 'Votre annonce n\'a pas pu être publiée',
        ],
        'extended' => [
            'success' => 'Votre annonce s\'est propagée avec succès',
            'error' => 'Votre annonce n\'a pas pu être étendue',
        ],
        'private' => [
            'success' => 'Votre annonce a été rendue privée avec succès',
            'error' => 'Impossible de rendre votre annonce privée.',
        ]
    ]
];
