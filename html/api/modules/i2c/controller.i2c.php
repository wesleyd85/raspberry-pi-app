<?php

namespace Controllers;

/**
 * Controller to acomplish i2c sensor related tasks
 *
 * @category  Controller
 * @uses      \Rhonda\RequestBody
 * @version   0.0.1
 * @since     2016-02-07
 * @author    Wesley Dekkers <wesley@wd-media.nl>
*/
class I2cCtl{

  /**
  * Read a temperature value
  * <pre class="GET"> GET [url]/i2c/temperature/:id/</pre>
  *
  * @param String - id
  *
  * @example
  * No POST Body
  *
  * @return JSON - **Object** result info
  *
  * @since   2016-02-07
  * @author  Wesley Dekkers <wesley@wd-media.nl>
  * @todo    check if id exists
  **/
  public function get_temperature($id){
    try{
      $config = \Rhonda\Config::get('config');

      $sensor = new \Models\I2c();
      $sensor->id = $id;
      $sensor->path = $config->BUS_PATH;

      echo json_encode($sensor->get_temperature());
    }catch(\Exception $e){
      echo \Rhonda\Error:: handle($e);
    }
  }

  /**
  * Read average temperature value of multiple sensors
  * <pre class="POST"> POST [url]/i2c/temperature/</pre>
  *
  * @example
  * [
  *     "sensor_id"
  *   , "sensor_id"
  * ]
  *
  * @return JSON - **Object** result info
  *
  * @since   2016-02-07
  * @author  Wesley Dekkers <wesley@wd-media.nl>
  * @todo    check if id exists
  **/
  public function temperature_multiple(){
    try{
      $config = \Rhonda\Config::get('config');

      // Load post body
      $body = \Rhonda\RequestBody:: get();

      $celsius = 0;
      $fahrenheit = 0;
      $count = count($body);

      foreach($body as $sensor_id){
        $sensor = new \Models\I2c();
        $sensor->id = $sensor_id;
        $sensor->path = $config->BUS_PATH;

        $temp = $sensor->get_temperature();
        $celsius = $celsius + $temp->celsius;
        $fahrenheit = $fahrenheit + $temp->fahrenheit;
      }

      $average = new \stdClass();
      $average->celsius = $celsius / $count;
      $average->fahrenheit = $fahrenheit / $count;


      echo json_encode($average);
    }catch(\Exception $e){
      echo \Rhonda\Error:: handle($e);
    }
  }

}
