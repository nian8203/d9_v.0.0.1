<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomForm extends FormBase{
  /**
   * {@inheritdoc }
   */
  public function getFormId(){
    return 'custom_form_form';
  }
  /**
   * {@inheritdoc }
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['elemento_imagen'] = array(
      '#markup' => '<img class="zoom" src="https://i1.wp.com/arnimartinez.com/wp-content/uploads/2017/12/datos-personales.gif?resize=525%2C238">',
    );

    $form['#attached']['library'][] = 'custom_form/custom_form_libraries';
    
    //creacion de contenedor para insertar los campos descritos abajo
    $form['datos_personales'] = array( 
      '#type' => 'fieldset',
      '#title' => $this->t('Datos personales'),
      '#attributes' => array(
        'class' => array('mi_clase'),
      ),
    );

    //en los campos se debe add el nombre del contenedor antes
    //del nombre del campo ej = $form['datos_personales']['nombre'] 
    $form['datos_personales']['nombre'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Digite su nombre'),
     //'#default_value' => $node->title,
      '#size' => 60,
      '#maxlength' => 30,
      //'#pattern' => 'some-prefix-[a-z]+',
      '#required' => true,
    );
    
    $form['datos_personales']['apellido'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Digite su apellido'),
     //'#default_value' => $node->title,
      '#size' => 60,
      '#maxlength' => 30,
      //'#pattern' => 'some-prefix-[a-z]+',
      '#required' => TRUE,
    );

    $form['datos_personales']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Digite su correo'),
      //'#pattern' => '*@example.com',
      '#required' => true,
    ];


    //nuevo container datos institucionales
    $form['datos_institucionales'] = array(
      '#type' => 'details',
      '#title' => $this->t('Datos Institucionales'),
      '#open' => TRUE,
    );
    
    $form['datos_institucionales'] ['telefono'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Digite un telefono'),
      //'#pattern' => '[^\\d]*',
      '#required' => true,
    );
    
    $form['datos_institucionales']['expiration'] = [
      '#type' => 'date',
      '#title' => $this->t('Fecha de ingreso'),
      '#default_value' => '2020-02-05',
    ];
    
    // $form['name']=[
    //   '#type' => 'textfield',
    //   '#title' => $this->t('name'),
    // ];
    // $form['lastname']=[
    //   '#type' => 'textfield',
    //   '#title' => $this->t('lastname'),
    // ];


//=========================BUTTONS================================
    $form['actions']['#type'] = 'actions';
    
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send'),
      '#buton_type' => 'primary',
      // '#attributes' => array(  //modifica los estilos llamandolos desde el css
      //   'class' => array('.btn-send'),
      // ),
    );

    $form['actions']['cancelar'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => array('custom_form_cancelar'), //llamar a la funcion en .module
      '#limit_validation_errors' => array(), //redireccionar sin que se ejecuten las validaciones de los campos
      // '#attributes' => array(  //sirve para modificar el estilo de los botones desde css
      //   'class' => array('.btn-send'),
      // ),
    );

    return $form;

 //=========================END BUTTONS=============================

  }
  



  // /**
  //  * {@inheritdoc}
  //  */
  // public function validatePhoneForm(array &$form, FormStateInterface $form_state){
  //   if(strlen($form_state->getValue('telefono'))<8){
  //     $form_state->setErrorByName('telefono', this->t('El numero ingresado no es valido'));
  //   }
  //   return;
  // }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
    if (strlen($form_state->getValue('telefono'))<8) {
      $form_state->setErrorByName('telefono', $this->t('Numero no valido'));
    }
     
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('Good mornig @fullname', ['@fullname' => $form_state->getValue('nombre')." ".
    $form_state->getValue('apellido')])); 
    $this->messenger()->addStatus($this->t('Your telephone number is @numero',array('@numero' => $form_state->getValue('telefono'))));

    global $base_url;
    //dpm($base_url);
    $response = new RedirectResponse($base_url); //base_url = directorio raiz
    $response->send();
    return;
  }


  

}