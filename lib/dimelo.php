<?php

/**
 * handler DIMELO login/logout
 * @author ombou
 */
class dimelo
{
  Const API_VERSION = '1.0';

  protected $iss = null;

  protected $hostname;

  protected $claim;

  protected $redirectUrl;

  protected $serviceUrl;

  protected $jwt;

  public function getIssuer()
  {
    return $this->iss;
  }

  public function setHostname($hostname)
  {
    $this->hostname = $hostname;
  }

  /**
   * @param JWTHandler $jwt 
   * @param string $iss issuer identifier
   * @param string $redirectUrl
   * @param string $serviceUrl
   *  
   */
  public function __construct(JWTHandler $jwt, $iss, $access_token, $redirectUrl, $serviceUrl)
  {
    $iat = time();

    $this->iss = $iss;
    $this->access_token = $access_token;

    $this->claim = array(
      'iss' => $iss,
      'iat' => $iat,
      'jti' => md5( uniqid(mt_rand(), true) ),
    );

    $this->redirectUrl = $redirectUrl;
    $this->serviceUrl = $serviceUrl;
    $this->jwt = $jwt;

  }

  public function addField($name, $value)
  {
    $this->claim[$name] = $value;
  }

  /**
   * get user service url callback url
   * 
   * @param string $serviceUrl
   * 
   * @return string
   */
  public function getCallBackUrl()
  {
    $callBackUrl = $this->redirectUrl . urlencode($this->serviceUrl) . '&jwt=' . $this->jwt->generateToken($this->claim);

    return $callBackUrl;
  }

  /**
   * logout client from DIMELO
   * 
   * @param string $iss dimelo issuer
   * 
   * @return boolean
   */
  public function logout()
  {
    if ($this->getIssuer() == null) {
      return false;
    }

    //get the user information by the iss
    $urlInfos = $this->hostname . '/' . self::API_VERSION . '/users?access_token='. $this->access_token . '&username=' . $this->getIssuer(); 
    /*
     * @ombou\todo : making a curl call to $urlInfos (using Guzzle)
     */
    // $return = $this->curlCall($urlInfos);
    $data = (array) json_decode($return, true);

    //POST logout request 
    if (key($data) === 0 && isset($data[0]['id'])) {
      $logoutUrl = $this->hostname . '/' . self::API_VERSION . '/users/'. $data[0]['id'] . '/logout';
      $post_data = array(
        'access_token' => $this->access_token,
      );

      /*
       * @ombou\todo : making a curl call to $logoutUrl with $posted_data (using Guzzle)
       */
      // $return = $this->curlCall($logoutUrl, $post_data);
      $data = (array) json_decode($return, true);

      if (sfConfig::get('sf_logging_enabled')) {
        $message = isset($data['id']) ? '{dimelo} success logout : ' . $this->getIssuer() . '/' . $data['id'] : '{dimelo} error logout : ' . $this->getIssuer();
        /*
         * @ombou\todo : log $message
         */
      }

      return isset($data['id']);
    }
  }
}

