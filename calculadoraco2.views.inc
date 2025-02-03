<?php

/**
 * Implements hook_views_data().
 */
function calculadoraco2_views_data() {
    $data['calculadoraco2_table'] = [];
  
    // Definición de la tabla base.
    $data['calculadoraco2_table']['table'] = [
      'group' => t('Calculadora CO2'),
      'base' => [
        'title' => t('Calculadora CO2'),
        'help' => t('Tabla de Calculadora de CO2'),
        'field' => 'id',
        'entity type' => 'node',
        'database' => 'default',
      ],
    ];

    
  
    $data['calculadoraco2_table']['table']['group'] = t('Calculadora CO2');

  
    // Campo: ID del usuario.
    $data['calculadoraco2_table']['user_id'] = [
      'title' => t('User ID'),
      'help' => t('ID del usuario que envió el registro.'),
      'field' => [
        'id' => 'numeric',
        //'click sortable' => TRUE,
      ],
      // Relación con la tabla users.
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => t('User'),
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'alter' => [
        'text' => TRUE,
        'html' => TRUE,
      ],
      'sort' => [
        'id' => 'standard',
      ],
      'argument' => [
        'id' => 'numeric',
        'title' => t('User ID'),
        'help' => t('Filtra registros según el ID del usuario autenticado.'),
        'default_argument_type' => 'current_user',
        'default_argument_skip_url' => TRUE,
      ],
    ];
  
    // Campo: Nombre del usuario (relación con users).
    $data['calculadoraco2_table']['nombre'] = [
        'title' => t('Nombre de usuario'),
        'help' => t('Nombre del usuario que envió el registro.'),
        'field' => [
          'id' => 'standard',
          //'click sortable' => TRUE,
        ],
        'filter' => [
          'id' => 'string',
        ],
        'sort' => [
          'id' => 'standard',
        ],
    ];

    // Campo: Tipo de grupo.
    $data['calculadoraco2_table']['grupo_tipo_tid'] = [
        'title' => t('Grupo tipo'),
        'help' => t('Tipo de grupo al que pertenece el registro.'),
        'field' => [
            'id' => 'standard',
            //'click sortable' => TRUE,
          ],
        'filter' => [
        'id' => 'string',
        ],
      'sort' => [
        'id' => 'standard',
       ],
    ];
  
    // Campo: Número de integrantes.
    $data['calculadoraco2_table']['grupo_integrantes_num'] = [
      'title' => t('Número de integrantes'),
      'help' => t('Número de integrantes del grupo.'),
      'field' => [
        'id' => 'numeric',
        //'click sortable' => TRUE,
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'standard',
       ],
    ];
  
    // Campo: Fecha de creación.
    $data['calculadoraco2_table']['created'] = [
      'title' => t('Fecha de creación'),
      'help' => t('Fecha de creación del registro.'),
      'field' => [
        'id' => 'date',
        //'click sortable' => TRUE,
      ],
      'filter' => [
        'id' => 'date',
      ],
      'sort' => [
        'id' => 'standard',
       ],
    ];
  
    // Campo: CO2 calculado.
    $data['calculadoraco2_table']['CO2'] = [
      'title' => t('CO2 calculado'),
      'help' => t('Cantidad de CO2 calculada.'),
      'field' => [
        'id' => 'numeric',
        //'click sortable' => TRUE,
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'standard',
       ],
    ];
  
    return $data;
  }