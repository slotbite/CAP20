<?
    function encryptData($encryption_algorithm, $encryption_mode, $random_source, 
                     $encryption_key, $raw_data, $iv=null)
{
  /* Open the cipher */
  $td = mcrypt_module_open($encryption_algorithm, '', $encryption_mode, '');

  /* Create the IV and determine the keysize length */
  $client_iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), $random_source);

  if ($iv != null)
     $client_iv = $iv;

  $ks = mcrypt_enc_get_key_size($td);

  /* Create key */
  $key = substr(md5($encryption_key), 0, $ks);

  /* Intialize encryption */
  mcrypt_generic_init($td, $key, $client_iv);

  /* Encrypt data */
  $encrypted_data = mcrypt_generic($td, $raw_data);

  /* Terminate encryption handler */
  mcrypt_generic_deinit($td);
  
  return array($client_iv, $encrypted_data);
}

/////////////////////////////////////////////////////////////////////

function decryptData($encryption_algorithm, $encryption_mode, $encryption_key, $client_iv, $encrypted_data)
{
  /* Open the cipher */
  $td = mcrypt_module_open($encryption_algorithm, '', $encryption_mode, '');

  $ks = mcrypt_enc_get_key_size($td);

  /* Create key */
  $key = substr(md5($encryption_key), 0, $ks);

  /* Initialize encryption module for decryption */
  mcrypt_generic_init($td, $key, $client_iv);

  /* Decrypt encrypted string */
  $decrypted_data = mdecrypt_generic($td, $encrypted_data);

  /* Terminate decryption handle and close module */
  mcrypt_generic_deinit($td);
  mcrypt_module_close($td);

  /* Return string */
  return trim($decrypted_data);
}

///////////////////////////////////////////////////////////////////// 

function enc_dec($str) { 
$clau1 = 'voERpqabc30deMfDJKIHW91OPAF6gjkT8lm4yztV5BNuZhnwxrsi2XCQYUGL_7S'; 
$clau2 = substr(str_repeat(strrev($clau1), 2), strlen($str)); 
return strtr($str, $clau1, $clau2); 
} 

?>