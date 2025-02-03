namespace Drupal\calculadoraco2\Plugin\views;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for the calculadoraco2_table.
 */
class CalculadoraCO2ViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = [];

    // Define la tabla base de la vista.
    $data['calculadoraco2_table']['table'] = [
      'group' => t('Calculadora CO2'),
      'base' => [
        'field' => 'id',
        'title' => t('Calculadora CO2'),
        'help' => t('Tabla de registro para calculadora CO2.'),
      ],
    ];

    // Define los campos de la tabla.
    $data['calculadoraco2_table']['id'] = [
      'title' => t('ID'),
      'help' => t('Identificador único del registro.'),
      'field' => [
        'id' => 'numeric',
      ],
    ];

    $data['calculadoraco2_table']['user_id'] = [
      'title' => t('ID de Usuario'),
      'help' => t('El ID del usuario que creó el registro.'),
      'field' => [
        'id' => 'numeric',
      ],
      'filter' => [
        'id' => 'numeric',
      ],
    ];

    $data['calculadoraco2_table']['grupo_tipo_tid'] = [
      'title' => t('Tipo de Grupo'),
      'help' => t('Nombre del término de taxonomía que representa el tipo de grupo.'),
      'field' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
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
    ];

    // Relación con la tabla de usuarios.
    $data['calculadoraco2_table']['user_id'] = [
      'title' => t('Usuario'),
      'help' => t('Relación con el usuario que creó el registro.'),
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'relationship field' => 'user_id',
        'id' => 'standard',
        'label' => t('Usuario relacionado'),
      ],
    ];

    return $data;
  }
}
