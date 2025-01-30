<?php

namespace Drupal\calculadoraco2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;

class calculadoraco2Controller extends ControllerBase {
    public function description() {
        $current_user = \Drupal::currentUser();
        $uid = $current_user->id();

        $database = \Drupal::database();
        $query = $database->query("SELECT * FROM {calculadoraco2_table} WHERE user_id = :uid", [':uid' => $uid]);
        $result = $query->fetchObject();

        if (!$result) {
            if ($uid != 0)
                {$path = 'modules/custom/calculadoraco2/templates/description.html.twig';}
            else
                {$path = 'modules/custom/calculadoraco2/templates/descriptionnolog.html.twig';}
            $content = file_get_contents($path);
            $body = [
                'description' => [
                    '#type' => 'inline_template',
                    '#template' => $content,
                    '#context' => [
                        'module' => 'calculadoraco2',
                    ],
                ],
            ];
            return $body;
        } else {
            $tipo_grupo = $result->grupo_tipo_tid;
            $integrantes = (int) $result->grupo_integrantes_num;
      
            $term = \Drupal::entityTypeManager()
              ->getStorage('taxonomy_term')
              ->loadByProperties([
                'vid' => 'calculadora_co2',
                'name' => $tipo_grupo,
              ]);
      
            $valor1 = 0;
            $valor2 = 0;
      
            if (!empty($term)) {
              $termino = reset($term);
              $descripcion = $termino->get('description')->value;
              $arrayValores = explode(',', $descripcion);
      
              $valor1 = (float)preg_replace('/[^0-9.]/', '', $arrayValores[0]);
              $valor2 = (float)preg_replace('/[^0-9.]/', '', $arrayValores[1]);
            }
      
            $total_CO2 = $integrantes * $valor1 * $valor2;
      
            $response = [
              '#type' => 'markup',
              '#markup' => $this->t('Tipo de grupo: @tipo_grupo, Número de miembros: @integrantes', [
                '@tipo_grupo' => $tipo_grupo,
                '@integrantes' => $integrantes,
              ]) . '<br>' . $this->t('El resultado de producción de CO2 es: @total KG de CO2', [
                '@total' => number_format($total_CO2, 2),
              ]),
            ];

            $response['#markup'] .= '<br><a href="' . Url::fromRoute('calculadoraco2.form')->toString() . '">' . $this->t('Modificar resultados') . '</a>';
      
            return $response;
        }
    }
}
