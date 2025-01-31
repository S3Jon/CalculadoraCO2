<?php
use Drupal\taxonomy\Entity\Term;

/**
 * Implements hook_views_data().
 */
function calculadoraco2_views_data() {
  $data['calculadoraco2_table'] = [
    'table' => [
      'group' => t('Calculadora CO2'),
      'base' => [
        'title' => t('Calculadora CO2'),
        'help' => t('Tabla de Calculadora de CO2'),
        'field' => 'id',
        'database' => 'default',
      ],
    ],
  ];

  $data['calculadoraco2_table']['user_id'] = [
    'title' => t('User ID'),
    'help' => t('ID del usuario que envió el registro.'),
    'field' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'base' => 'users_field_data',
      'base field' => 'uid',
      'id' => 'standard',
      'label' => t('User'),
    ],
    'filter' => [
      'id' => 'numeric',
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

  $data['calculadoraco2_table']['nombre'] = [
    'title' => t('Nombre de usuario'),
    'help' => t('Nombre del usuario que envió el registro.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['calculadoraco2_table']['grupo_tipo_tid'] = [
    'title' => t('Tipo de grupo'),
    'help' => t('Tipo de grupo al que pertenece el registro.'),
    'field' => [
      'id' => 'standard',
    ],
    'relationship' => [
      'base' => 'taxonomy_term_field_data',
      'base field' => 'tid',
      'id' => 'standard',
      'label' => t('Tipo de grupo'),
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['calculadoraco2_table']['grupo_integrantes_num'] = [
    'title' => t('Número de integrantes'),
    'help' => t('Número de integrantes del grupo.'),
    'field' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['calculadoraco2_table']['created'] = [
    'title' => t('Fecha de creación'),
    'help' => t('Fecha de creación del registro.'),
    'field' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['calculadoraco2_table']['CO2'] = [
    'title' => t('CO2 calculado'),
    'help' => t('Cantidad de CO2 calculada.'),
    'field' => [
      'id' => 'numeric',
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