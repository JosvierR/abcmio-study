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
        'title' => 'Days of Publicity',
        'title_singular' => 'Days of Publicity',
        'option_label_header' => 'Please choose one of  purchase options',
        'total_title' => 'Total US$',
        'option_method' => 'Payment Method',
        'option_method_others' => 'Other payment methods',
        'send' => [
            'send_title' => 'Sell credits to a register user.',
            'email_to_send' => 'Your friend\'s email',
            'total_credits' => 'Total Credits',
            'placeholders' => [
                'email' => '',
                'credits' => 'Credits to assign, maximum :total '
            ],
            'submit_button' => 'Transfer',
            'message' => [
                'success' => 'Credits Transfer successfully',
                'error' => [
                    'auto_assign' => 'You cannot earn yourself credits. Usted no puede auto asiganrse créditos',
                    'max_limit' => 'You cannot allocate more than your account has, No puede asignar una cantidad superior a la que posee su cuenta.',
                    'email_not_found' => 'This email is not registered, Este correo no está registrado.'
                ]
            ]

        ],

    ],
    'profile' => [
        'header' => [
            'title' => 'My profile',
            'email' => 'Email',
        ],
        'form' => [
            'name' => [
                'title' => 'Your name',
                'placeholder' => 'Your fullname here'
            ],
            'country' => [
                'title' => 'Country',
            ],
            'birth_date' => [
                'title' => 'Your birth date',
                'placeholder' => 'Your birth date here'
            ],
            'gender' => [
                'title' => 'Your gender',
                'placeholder' => 'Your gender here',
                'options' => [
                    'male' => 'Male',
                    'female' => 'Female'
                ]
            ],
            'password' => [
                'header' => [
                    'title' => 'Update your password',
                ],
                'current_password' => 'Your current password',
                'new_password' => 'Insert your password here (min. 6 characters)',
                'new_password_confirmation' => 'Insert your password confirmation here',
            ]

        ]
    ],
    'my_ads' => [
        'header' => [
            'title' => 'My ads',
            'title_singular' => 'My Ad',
        ],
        'form' => [
            'inputs' => [
                'search' => [
                    'title' => 'Search',
                    'placeholder' => 'Search',
                ],
            ],
            'checkbox' => [
                'public_only' => 'Publish only',
            ],
            'buttons' => [
                'search' => 'Search'
            ]
        ],
        'buttons' => [
            'create-add' => 'Create Ad'
        ],
        'labels' => [
            'search_result' => 'Search results'
        ],
        'info_table' => [
            'title' => 'Published',
            'start_date' => 'Starts',
            'end_date' => 'Ends',
            'remaining' => 'Remaining',
            'buttons' => [
                'extend' => 'Extend',
                'publish' => 'Publish',
                'private' => 'Unpublish',
                'delete' => 'Delete'
            ]
        ]
    ],
    'forms' => [
        'ads' => [
            'global' => [
                'title' => 'Ad information',
                'feature_image' => [
                    'title' => 'Feature image'
                ],
                'inputs' => [
                    'title' => [
                        'label' => 'Title',
                        'placeholder' => 'Ad title *'
                    ],
                    'business_name' => [
                        'label' => 'Business name',
                        'placeholder' => 'Short Message'
                    ],
                    'country' => [
                        'label' => 'Select Country',
                        'placeholder' => 'Select a country *'
                    ],
                    'state' => [
                        'label' => 'City',
                        'placeholder' => 'Write the name of the city *'
                    ],
                    'category' => [
                        'label' => 'Select Category',
                        'placeholder' => 'Select a category'
                    ],
                    'sub-category' => [
                        'label' => 'Select sub Category',
                        'placeholder' => 'Select a sub category'
                    ],
                    'status' => [
                        'label' => 'Status',
                        'option' => 'Active'
                    ],
                    'messages' => [
                        'label' => 'Messages',
                        'option' => 'Allow'
                    ],
                    'description' => [
                        'label' => 'Description'
                    ],
                    'contact_info' => [
                        'header' => [
                            'title' => 'Contact info'
                        ],
                        'phone' => [
                            'label' => 'Phone',
                            'placeholder' => '18095554444'
                        ],
                        'whatsapp_number' => [
                            'label' => 'Whatsapp Number',
                            'placeholder' => '18095554444'
                        ],
                        'email' => [
                            'label' => 'Email',
                            'placeholder' => 'youremail@example.com'
                        ],
                        'address' => [
                            'label' => 'Address',
                            'placeholder' => 'Address\'s Ad'
                        ],
                        'google_map' => [
                            'title' => 'Google Map URL Location',
                            'placeholder' => 'https://goo.gl/maps/rh1hE4vDZ1ejwfZc7'
                        ]
                    ],
                    'socials_media' => [
                        'website' => [
                            'label' => 'Website',
                            'placeholder' => 'https://abcmio.com'
                        ],
                        'social_network' => [
                            'label' => 'Social Network',
                            'placeholder' => 'https://facebook.com/abcmio'
                        ]
                    ]
                ],
            ],
            'create' => [
//                'title' => 'Create Ad',
                'title' => 'Save',
                'header' => 'Ad data',
                'buttons' => [
                    'create_ad' => 'Create Ad'
                ]
            ],
            'edit' => [
                'title' => 'Edit Ad',
                'header' => 'Ad data',
                'buttons' => [
                    'create_ad' => 'Create Ad'
                ]
            ],
        ]
    ]
];
