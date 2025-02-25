<?php

namespace Drupal\calculadoraco2\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\taxonomy\Entity\Term;

class CalculadoraCO2Form extends FormBase {
  protected $step = 1;
  protected $database;
  protected $currentUser;

  public function __construct(Connection $database, AccountProxyInterface $current_user) {
    $this->database = $database;
    $this->currentUser = $current_user;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('current_user')
    );
  }

  public function getFormId() {
    return 'calculadoraco2_multistep_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    if (!$this->currentUser->isAuthenticated()) {
      header('Location: /user/login');
    }

    $this->step = $form_state->get('step') ? $form_state->get('step') : 1;

    switch ($this->step) {
      case 1:
        $form = $this->stepOne($form, $form_state);
        break;
      case 2:
        $form = $this->stepTwo($form, $form_state);
        break;
      case 3:
        $form = $this->stepThree($form, $form_state);
        break;
    }
    return $form;
  }

  private function stepOne(array $form, FormStateInterface $form_state) {
    $stored_values = $form_state->get('stored_values') ? $form_state->get('stored_values') : [];

    $tiposGrupo = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree('calculadora_co2');

    $options = [];
    foreach ($tiposGrupo as $tipoGrupo) {
      $options[$tipoGrupo->name] = $tipoGrupo->name;
    }

    $form['grupo_tipo_tid'] = [
      '#type' => 'select',
      '#title' => $this->t('Tipo de grupo'),
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => isset($stored_values['grupo_tipo_tid']) ? $stored_values['grupo_tipo_tid'] : '',
    ];
    $this->addNavigationButtons($form, 1);
    return $form;
  }

  private function stepTwo(array $form, FormStateInterface $form_state) {
    $stored_values = $form_state->get('stored_values') ? $form_state->get('stored_values') : [];

    $form['grupo_integrantes_num'] = [
      '#type' => 'number',
      '#min' => 1,
      '#max' => 1_500_000_000, //El país con más habitantes tiene 1.400.000.000, no creo que una empresa pudiera tener más
      '#title' => $this->t('Número de integrantes del grupo'),
      '#required' => TRUE,
      '#default_value' => isset($stored_values['grupo_integrantes_num']) ? $stored_values['grupo_integrantes_num'] : '',
    ];
    $this->addNavigationButtons($form, 2);
    return $form;
  }

  private function stepThree(array $form, FormStateInterface $form_state) {
    $stored_values = $form_state->get('stored_values') ? $form_state->get('stored_values') : [];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calcular'),
    ];
    $this->addNavigationButtons($form, 3);
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($this->step == 1) {
      if (empty($form_state->getValue('grupo_tipo_tid'))) {
        $form_state->setErrorByName('grupo_tipo_tid', $this->t('Por favor selecciona un tipo de grupo'));
      }
    }
    if ($this->step == 2) {
      if (empty($form_state->getValue('grupo_integrantes_num'))) {
        $form_state->setErrorByName('grupo_integrantes_num', $this->t('Por favor ingresa el número de integrantes del grupo'));
      }
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->step == 3) {
      $stored_values = $form_state->get('stored_values') ? $form_state->get('stored_values') : [];
      $tipo_grupo = $stored_values['grupo_tipo_tid'];
      $integrantes = (int) $stored_values['grupo_integrantes_num'];
      $timestamp = time();
      $uid = $this->currentUser->id();

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
        
        $valor1 = $termino->get('field_valor1')->value ?? 0;
        $valor2 = $termino->get('field_valor2')->value ?? 0;
      }

      (float)$total_CO2 = (float)$integrantes * $valor1 * $valor2; // Forzar float porque se capeaba en 2,147,480,000 

      // Obtener el nombre del usuario.
      $user = \Drupal\user\Entity\User::load($uid);
      $nombre = $user ? $user->getDisplayName() : 'Usuario no encontrado';

      $this->database->insert('calculadoraco2_table')
        ->fields([
          'user_id' => $uid,
          'grupo_tipo_tid' => $termino->id(),
          'grupo_integrantes_num' => $integrantes,
          'created' => $timestamp,
          'CO2' => $total_CO2,
          'nombre' => $nombre,
        ])
        ->execute();

    } else {
      $stored_values = $form_state->get('stored_values') ? $form_state->get('stored_values') : [];
      $stored_values = array_merge($stored_values, $form_state->getValues());
      $form_state->set('stored_values', $stored_values);
      $form_state->set('step', $this->step + 1);
      $form_state->setRebuild();
    }
  }

  private function addNavigationButtons(array &$form, $step) {
    if ($step > 1) {
      $form['actions']['back'] = [
        '#type' => 'submit',
        '#value' => $this->t('Back'),
        '#submit' => ['::back'],
        '#limit_validation_errors' => [],
      ];
    }

    if ($step < 3) {
      $form['actions']['next'] = [
        '#type' => 'submit',
        '#value' => $this->t('Next'),
      ];
    }
  }

  public function back(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step');
    $form_state->set('step', $step - 1);
    $form_state->setRebuild();
  }
}
