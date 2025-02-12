<?php

namespace Drupal\calculadoraco2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

class CalculadoraCO2Controller extends ControllerBase {
  public function description() {
    $current_user = \Drupal::currentUser();
    $uid = $current_user->id();

    if ($uid != 0) {
      $path = 'modules/custom/calculadoraco2/templates/description.html.twig';
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
}