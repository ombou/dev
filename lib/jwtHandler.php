<?php

/**
 * JWT handler
 * 
 * @author ombou
 */
class JWTHandler
{
  CONST ALG = 'HS512';

  protected $salt;

  protected $hmacAlgMapping = array(
    'HS512' => 'sha512',
    'HS256' => 'sha256',
  );

  public function __construct($salt = null)
  {
    $this->salt = $salt;
  }

  /**
   * Check signature validation and return token data
   * 
   * @param string $token
   * 
   * @return array
   */
  public function checkToken($token)
  {
    // retrieve data from token
    list($header, $claim, $signature) = explode('.', $token);

    $headerData = json_decode(self::base64UrlDecode($header), true);

    $unsignedToken = $header . '.' . $claim;

    $return = array('is_valid' => false, 'data' => null);

    // compare signature
    if ($signature !== $this->getSignature($unsignedToken, $headerData['alg'])) {
      return false;
    }

    return json_decode(self::base64UrlDecode($claim), true);
  }

  /**
   * Generate JWT for a specific claim
   * 
   * @param array $claim
   * 
   * @return string
   */
  public function generateToken(array $claim)
  {
    $token = self::base64UrlEncode( $this->generateHeader() ) . '.' . self::base64UrlEncode( json_encode($claim) );

    return $token . '.' . $this->getSignature($token);
  }

  /**
   * Generate the signature part of the JWT
   * 
   * @param  string $token the unsigned token
   * @param  string $alg the algorythm used
   * 
   * @return string
   */
  protected function getSignature($token, $alg = self::ALG)
  {
    if (!isset($this->hmacAlgMapping[$alg])) {
      return false;
    }

    $signature = hash_hmac($this->hmacAlgMapping[$alg], $token, $this->salt, true);

    return self::base64UrlEncode($signature);
  }

  /**
   * Generate the header part of the JWT
   * 
   * @return string
   */
  protected function generateHeader()
  {
    $headerArray = array(
      'typ' => 'JWT',
      'alg' => JWTHandler::ALG,
    );

    return json_encode($headerArray);
  }
  
  public static function base64UrlEncode($string)
  {
    return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
  }

  public static function base64UrlDecode($data)
  {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
  }
}

