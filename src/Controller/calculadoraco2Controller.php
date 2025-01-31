<?php

namespace Drupal\calculadoraco2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

class CalculadoraCO2Controller extends ControllerBase {
  public function description() {
    $current_user = \Drupal::currentUser();
    $uid = $current_user->id();

    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {calculadoraco2_table} WHERE user_id = :uid", [':uid' => $uid]);
    $results = $query->fetchAll();

    if ($uid != 0) {
      $path = 'modules/custom/calculadoraco2/templates/description.html.twig';
      if ($current_user->hasRole('administrator')) {
        $path = 'modules/custom/calculadoraco2/templates/descriptionadmin.html.twig';
      }        
    } else {
      $path = 'modules/custom/calculadoraco2/templates/descriptionnolog.html.twig';
    }
    $content = file_get_contents($path);
    $body = [
      'description' => [
        '#type' => 'inline_template',
        '#template' => $content,
        '#context' => [
          'module' => 'calculadoraco2',
        ],
        '#cache' => [
          'max-age' => 0,
        ],
      ],
    ];
    return $body;
  }

  public function user() {
    $current_user = \Drupal::currentUser();
    $uid = $current_user->id();

    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {calculadoraco2_table} WHERE user_id = :uid", [':uid' => $uid]);
    $results = $query->fetchAll();

    $output = '';
    foreach ($results as $record) {
      $term = Term::load($record->grupo_tipo_tid);
      $tipo_grupo = $term ? $term->getName() : $this->t('Tipo de grupo no encontrado');
      $integrantes = (int) $record->grupo_integrantes_num;
      $total_CO2 = (float) $record->CO2;

      $output .= $this->t('Tipo de grupo: @tipo_grupo, Número de miembros: @integrantes', [
        '@tipo_grupo' => $tipo_grupo,
        '@integrantes' => $integrantes,
      ]) . '<br>' . $this->t('El resultado de producción de CO2 es: @total KG de CO2', [
        '@total' => number_format($total_CO2, 2),
      ]) . '<br><br>';
    }

    $response = [
      '#type' => 'markup',
      '#markup' => $output,
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    return $response;
  }

  public function admin() {
    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {calculadoraco2_table}");
    $results = $query->fetchAll();

    $output = '';
    foreach ($results as $record) {
      $user = User::load($record->user_id);
      $username = $user ? $user->getDisplayName() : $this->t('Usuario no encontrado');
      $term = Term::load($record->grupo_tipo_tid);
      $tipo_grupo = $term ? $term->getName() : $this->t('Tipo de grupo no encontrado');
      $integrantes = (int) $record->grupo_integrantes_num;
      $total_CO2 = (float) $record->CO2;

      $output .= $this->t('Usuario: @username, Tipo de grupo: @tipo_grupo, Número de miembros: @integrantes', [
        '@username' => $username,
        '@tipo_grupo' => $tipo_grupo,
        '@integrantes' => $integrantes,
      ]) . '<br>' . $this->t('El resultado de producción de CO2 es: @total KG de CO2', [
        '@total' => number_format($total_CO2, 2),
      ]) . '<br><br>';
    }

    $response = [
      '#type' => 'markup',
      '#markup' => $output,
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    return $response;
  }
}