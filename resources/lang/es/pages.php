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
        'title' => 'Días de Publicidad',
        'title_singular' => 'Días de Publicidad',
        'option_label_header' => 'Por favor, seleccione uno de las  opciones de compra.',
        'total_title' => 'Total US$',
        'option_method' => 'Método de Pago',
        'option_method_others' => 'Otros Métodos de Pago',
        'send' => [
            'send_title' => 'Vender créditos a un usuario registrado.',
            'email_to_send' => 'Correo de tu amigo',
            'total_credits' => 'Total créditos',
            'placeholders' => [
                'email' => '',
                'credits' => 'Creditos para asignar, máximo :total .'
            ],
            'submit_button' => 'Transferir',
            'message' => [
                'success' => 'Créditos enviados correctamente',
                'error' => [
                    'auto_assign' => 'Usted no puede auto asiganrse créditos',
                    'max_limit' => ' No puede asignar una cantidad superior a la que posee su cuenta.',
                    'email_not_found' => 'Este correo no está registrado.'
                ]
            ]
        ],
    ],
    'profile' => [
        'header' => [
            'title' => 'Mi perfil',
            'email' => 'Correo Electrónico',
        ],
        'form' => [
            'name' => [
                'title' => 'Nombre',
                'placeholder' => 'Su nombre completo'
            ],
            'country' => [
                'title' => 'País',
            ],
            'birth_date' => [
                'title' => 'Fecha de nacimiento ',
                'placeholder' => 'Su fecha de nacimiento'
            ],
            'gender' => [
                'title' => 'Género',
                'placeholder' => 'Su género',
                'options' => [
                    'male' => 'Hombre',
                    'female' => 'Mujer'
                ]
            ],
            'password' => [
                'header' => [
                    'title' => 'Actualice su contraseña',
                ],
                'current_password' => 'Contraseña actual.',
                'new_password' => 'Contraseña nueva (mín. 6 caracteres). ',
                'new_password_confirmation' => 'Confirmación de contraseña.',
            ]

        ]
    ],
    'my_ads' => [
        'header' => [
            'title' => 'Mis anuncios',
            'title_singular' => 'Mi anuncio',
        ],
        'form' => [
            'inputs' => [
                'search' => [
                    'title' => 'Buscar',
                    'placeholder' => 'Buscar',
                ],
            ],
            'checkbox' => [
                'public_only' => 'Publicados solamente',
            ],
            'buttons' => [
                'search' => 'Buscar'
            ]
        ],
        'buttons' => [
            'create-add' => 'Crear anuncio'
        ],
        'labels' => [
            'search_result' => 'Resultados de la búsqueda'
        ],
        'info_table' => [
            'title' => 'Publicado',
            'start_date' => 'Inicia',
            'end_date' => 'Finaliza',
            'remaining' => 'Expira',
            'buttons' => [
                'extend' => 'Extender',
                'publish' => 'Publicar',
                'private' => 'Privar',
                'delete' => 'Borrar'
            ]
        ]
    ],
    'forms' => [
        'ads' => [
            'global' => [
                'title' => 'Datos del Anuncio',
                'feature_image' => [
                    'title' => 'Imágen principal'
                ],
                'inputs' => [
                    'title' => [
                        'label' => 'Título',
                        'placeholder' => 'Título del anuncio'
                    ],
                    'business_name' => [
                        'label' => 'Nombre de la empresa',
                        'placeholder' => 'Mensaje Breve'
                    ],
                    'country' => [
                        'label' => 'Seleccione un País',
                        'placeholder' => 'Seleccione País'
                    ],
                    'state' => [
                        'label' => 'Seleccione una Ciudad',
                        'placeholder' => 'Escriba el nombre de la ciudad *'
                    ],
                    'category' => [
                        'label' => 'Seleccione una Categoría',
                        'placeholder' => 'Seleccione categoría'
                    ],
                    'sub-category' => [
                        'label' => 'Seleccione una Sub Categoría',
                        'placeholder' => 'Seleccione a Sub Categoría'
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
                        'label' => 'Descripción'
                    ],
                    'contact_info' => [
                        'header' => [
                            'title' => 'Información de Contacto'
                        ],
                        'phone' => [
                            'label' => 'Teléfono',
                            'placeholder' => '18095554444'
                        ],
                        'whatsapp_number' => [
                            'label' => 'Número Whatsapp',
                            'placeholder' => '18095554444'
                        ],
                        'email' => [
                            'label' => 'Email',
                            'placeholder' => 'micorreo@ejemplo.com'
                        ],
                        'address' => [
                            'label' => 'Dirección',
                            'placeholder' => 'Dirección del anuncio'
                        ],
                        'google_map' => [
                            'title' => 'Localizacion URL Google map',
                            'placeholder' => 'https://goo.gl/maps/rh1hE4vDZ1ejwfZc7'
                        ]
                    ],
                    'socials_media' => [
                        'website' => [
                            'label' => 'Sitio Web',
                            'placeholder' => 'https://abcmio.com'
                        ],
                        'social_network' => [
                            'label' => 'Red Social',
                            'placeholder' => 'https://facebook.com/abcmio'
                        ]
                    ]
                ],
            ],
            'create' => [
//                'title' => 'Crear Anuncio',
                'title' => 'Guardar',
                'header' => 'Ad data',
                'buttons' => [
                    'create_ad' => 'Create Ad'
                ]
            ],
            'edit' => [
                'title' => 'Editar Anuncio',
                'header' => 'Datos del Anuncio',
                'buttons' => [
                    'create_ad' => 'Crear Anuncio'
                ]
            ],
        ]
    ]

];
