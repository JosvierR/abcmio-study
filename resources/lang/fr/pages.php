<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'credits' => [
        'title' => 'Journées publicitaires',
        'title_singular' => 'Journées publicitaires',
        'option_label_header' => 'Veuillez sélectionner l\'une des options d\'achat.',
        'total_title' => 'Total US$',
        'option_method' => 'Procédé de paiement',
        'option_method_others' => 'Autres modes de paiement',
        'send' => [
            'send_title' => 'Vendre des jours à un utilisateur enregistré.',
            'email_to_send' => 'e-mail de votre ami',
            'total_credits' => 'nombre total de jours',
            'placeholders' => [
                'email' => '',
                'credits' => 'jours à attribuer, maximum : total .'
            ],
            'submit_button' => 'Transfert',
            'message' => [
                'success' => 'jours envoyés correctement',
                'error' => [
                    'auto_assign' => 'Vous ne pouvez pas vous attribuer des jours',
                    'max_limit' => ' Vous ne pouvez pas allouer un montant supérieur à ce que votre compte possède.',
                    'email_not_found' => 'Cet e-mail n\'est pas enregistré.'
                ]
            ]
        ],
    ],
    'profile' => [
        'header' => [
            'title' => 'Mon profil',
            'email' => 'Courrier électronique',
        ],
        'form' => [
            'name' => [
                'title' => 'nom',
                'placeholder' => 'Votre nom complet'
            ],
            'country' => [
                'title' => 'Pays',
            ],
            'birth_date' => [
                'title' => 'Date de naissance ',
                'placeholder' => 'Date de naissance'
            ],
            'gender' => [
                'title' => 'Genre
                ',
                'placeholder' => 'Son sexe',
                'options' => [
                    'male' => 'Homme',
                    'female' => 'Femme'
                ]
            ],
            'password' => [
                'header' => [
                    'title' => 'mettre à jour votre mot de passe',
                ],
                'current_password' => 'mot de passe actuel.',
                'new_password' => 'Nouveau mot de passe (min. 6 caractères). ',
                'new_password_confirmation' => 'Confirmation mot de passe.',
            ]

        ]
    ],
    'my_ads' => [
        'header' => [
            'title' => 'mes annonces',
            'title_singular' => 'Mon annonce',
        ],
        'form' => [
            'inputs' => [
                'search' => [
                    'title' => 'Chercher',
                    'placeholder' => 'Chercher',
                ],
            ],
            'checkbox' => [
                'public_only' => 'publié uniquement',
            ],
            'buttons' => [
                'search' => 'Chercher'
            ]
        ],
        'buttons' => [
            'create-add' => 'créer une publicité'
        ],
        'labels' => [
            'search_result' => 'Résultats de recherche'
        ],
        'info_table' => [
            'title' => 'Publié',
            'start_date' => 'Commencer',
            'end_date' => 'prend fin',
            'remaining' => 'expire',
            'buttons' => [
                'extend' => 'Étendre',
                'publish' => 'publier',
                'private' => 'Priver',
                'delete' => 'Supprimer'
            ]
        ]
    ],
    'forms' => [
        'ads' => [
            'global' => [
                'title' => 'Données publicitaires',
                'feature_image' => [
                    'title' => 'image principale'
                ],
                'inputs' => [
                    'title' => [
                        'label' => 'Qualification',
                        'placeholder' => 'ajouter un titre'
                    ],
                    'business_name' => [
                        'label' => 'Nom de l\'entreprise',
                        'placeholder' => 'Message bref'
                    ],
                    'country' => [
                        'label' => 'Choisissez un pays',
                        'placeholder' => 'Choisissez le pays'
                    ],
                    'state' => [
                        'label' => 'Seleccione una Ciudad',
                        'placeholder' => 'Écrivez le nom de la ville *'
                    ],
                    'category' => [
                        'label' => 'Choisir une catégorie',
                        'placeholder' => 'Choisir une catégorie'
                    ],
                    'sub-category' => [
                        'label' => 'Sélectionnez une sous-catégorie',
                        'placeholder' => 'Sélectionnez la sous-catégorie'
                    ],
                    'status' => [
                        'label' => 'Estatus',
                        'option' => 'Activo'
                    ],
                    'messages' => [
                        'label' => 'Mensaje',
                        'option' => 'Recibir'
                    ],
                    'description' => [
                        'label' => 'Description'
                    ],
                    'contact_info' => [
                        'header' => [
                            'title' => 'Information de contact'
                        ],
                        'phone' => [
                            'label' => 'Téléphone',
                            'placeholder' => '18095554444'
                        ],
                        'whatsapp_number' => [
                            'label' => 'Numéro Whatsapp',
                            'placeholder' => '18095554444'
                        ],
                        'email' => [
                            'label' => 'Email',
                            'placeholder' => 'moncourrier@ejemplo.com'
                        ],
                        'address' => [
                            'label' => 'adresse',
                            'placeholder' => 'Dirección del anuncio'
                        ],
                        'google_map' => [
                            'title' => 'Emplacement URL Google map',
                            'placeholder' => 'https://goo.gl/maps/rh1hE4vDZ1ejwfZc7'
                        ]
                    ],
                    'socials_media' => [
                        'website' => [
                            'label' => 'Lieu Web',
                            'placeholder' => 'https://abcmio.com'
                        ],
                        'social_network' => [
                            'label' => 'Réseau social',
                            'placeholder' => 'https://facebook.com/abcmio'
                        ]
                    ]
                ],
            ],
            'create' => [
//                'title' => 'Crear Anuncio',
                'title' => 'sauvegarder',
                'header' => 'Ad data',
                'buttons' => [
                    'create_ad' => 'Create Ad'
                ]
            ],
            'edit' => [
                'title' => 'modifier l\'modifier l\'annonce',
                'header' => 'Données publicitaires',
                'buttons' => [
                    'create_ad' => 'Créer une publicité'
                ]
            ],
        ]
    ]

];
