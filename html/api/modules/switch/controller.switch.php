<?php

namespace Controllers;

/**
 * Controller to switch power on relay on/off
 *
 * @category  Controller
 * @uses      \Rhonda\RequestBody
 * @version   0.0.1
 * @since     2016-02-07
 * @author    Wesley Dekkers <wesley@wd-media.nl>
*/
class SwitchCtl{

  /**
  * Load GPIO pins from the raspberry pi
  *
  * @return Return
  *
  * @since   2016-02-13
  * @author  Wesley Dekkers <wesley@wd-media.nl> 
  **/
  public function get(){
    $get = new \Models\GPIO();

    echo json_encode($read_all->read_all());
  }
  
  /**
  * Switch pin ON / OFF
  * <pre class="PUT"> PUT [url]/switch/set/:pin/:value/</pre>
  *
  * @param String - pin number (wPi pin)
  * @param String - value (ON/OFF)
  *
  * @example
  * No POST Body
  *
  * @return JSON - **Object** success message
  *
  * @since   2016-02-05
  * @author  Wesley Dekkers <wesley@wd-media.nl>
  * @todo    check if values are set correctly
  * @todo    check if pin exists
  **/
  public function set($pin, $value){
    try{
      if(!is_numeric($pin)){
        throw new \Exception("Pin should be numeric");
      }

      # Load the model
      $set_gpio = new \Models\GPIO();
      $set_gpio->pin = $pin;

      # Decide what to do
      if($value == 'ON'){
        $set_gpio->mode = 'OUT';
        $set_gpio->status = 0;
      }
      elseif($value == 'OFF'){
        $set_gpio->mode = 'IN';
        $set_gpio->status = 1;
      }
      else{
        throw new \Exception("No valid pin value entered");
      }
      
      // Execute the commands
      $set_gpio->mode();
      $set_gpio->write();

      // Reload the pin
      echo json_encode($set_gpio->get());
    }catch(\Exception $e){
      echo \Rhonda\Error:: handle($e);
    }
  }

}
