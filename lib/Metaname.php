<?php

include('JsonRpcClient.php');


function str_rpartition( $string, $delimiter )
{
  $i = strrpos( $string, $delimiter );
  if ( false === $i )
    return array( $string, NULL );
  else
    return array( substr($string,0,$i), substr($string,$i+1) );
}


function str_endswith( $string, $suffix )
{
  $i = strrpos( $string, $suffix );
  if ( false === $i )
    return false;
  else
    return $i + strlen($suffix) == strlen($string);
}


# Helper methods have been added here to avoid polluting the root namespace
class Metaname
{
  private  $whmcs_params;
  private  $remote;
  private  $identity;
  private  $api_key;


  #public function __construct($api_endpoint,$identity, $api_key)
  public function __construct($whmcs_params)
  {
    $this->whmcs_params = $whmcs_params;
    $api_endpoint = 'no' === $whmcs_params['TestSite'] ?
                    'https://metaname.net/api/1.1' :
                    'https://test.metaname.net/api/1.1';
    $this->remote = new JsonRpcClient($api_endpoint);
    $this->identity = $whmcs_params['AccountRef'];
    $this->api_key = $whmcs_params['APIKey'];
  }


  function __call($name, $arguments)
  {
    $args = array_merge( array($this->identity, $this->api_key), $arguments);
    return call_user_func_array(array($this->remote,$name), $args);
  }


  function postal_address_to_s($postal_address)
  {
    $parts = array($postal_address->line1,
                   $postal_address->line2,
                   $postal_address->city,
                   $postal_address->region,
                   $postal_address->postal_code,
                   $postal_address->country_code);
    return join(',', array_filter($parts));
  }


  function phone_number_to_s($phone_number)
  {
    $parts = array($phone_number->country_code,
                   $phone_number->area_code,
                   $phone_number->local_number);
    return '+'. join(' ', $parts);
  }


  function contact_to_s($contact)
  {
    $postal_address = $this->postal_address_to_s($contact->postal_address);
    $phone_number = $this->phone_number_to_s($contact->phone_number);
    $fax_number = $this->phone_number_to_s($contact->fax_number);
    return <<<EOF
Name:    $contact->name
Email:   $contact->email_address
Address: $postal_address
Phone:   $phone_number
Fax:     $fax_number
EOF;
  }

  function specified_domain_name()
  {
    return $this->whmcs_params['sld']. '.'. $this->whmcs_params['tld'];
  }

  function nz_name_specified()
  {
    return str_endswith( strtolower($this->whmcs_params['tld']), 'nz' );
  }

  # Given a list of Domain objects, provides the Domain object with the given
  # name or NULL if no Domain could be found with the given name.
  #
  function domain_named( $name, $domains )
  {
    #$this->inspect( 'domain', $name );
    foreach ( $domains as $domain )
    {
      #$this->inspect( 'd', $domain );
      #$this->inspect( 'n', $domain->name );
      if ( $domain->name == $name )
        return $domain;
    }
    return NULL;
  }

  function specified_term_in_months()
  {
    return 12 * $this->whmcs_params['regperiod'];
  }

  function specified_contacts()
  {
    return array(
      'registrant' => $this->_specified_contact(''),
      'admin'      => $this->_specified_contact('admin'),
      'technical'  => $this->_specified_contact('admin')
    );
  }

  function _specified_contact( $prefix )
  {
    $params = $this->whmcs_params;
    # The structure of fullphonenumber is "+64.123456", where "123456" is
    # edited in WHMCS as a single text field
    # FIXME: This is cheesy-as but probably the best that we can do at present
    # Try to detect the area code
    $fullphonenumber = trim( $params[$prefix.'fullphonenumber'] );
    $matches = array();
    if ( preg_match('/\+(\d+)\.(.+)', $fullphonenumber, $matches) )
    {
      $country_code =    $matches[1];
      $national_number = $matches[2];
      # national_number should contain *only* digits
      preg_replace( '/[^\d]/', '', $national_number );

      # If the phone number that the customer input starts with a zero then
      # it's probably the case that they have included an area code
      if ( '0' == $national_number[0] )
      {
        # This is NZ-specific and overly simplistic to assume that land lines
        # have a 2 digit code and mobile phones 3 ( Scott Base, Compass
        # Communications, Teletraders MVNO, M2 MVNO, Airnet NZ Ltd)
        if ( $national_number[1] != '2' )
        {
          # national_number is like "03 123 456"
          $area_code = $national_number[1];
          $local_number = substr( $national_number, 2 );
        }
        else {
          # $national_number is like "021 123 456"
          $area_code = substr( $national_number, 1, 2 );
          $local_number = substr( $national_number, 3 );
        }
      }
      else {
        # The customer probably input a local number, although area_code is
        # *required*, so use the first digit of the local number
        $area_code = substr( $national_number, 0, 1 );
        $local_number = substr( $national_number, 1 );
      }
    }
    else {
      # The phone number was not in the expected format.  This should never happen
      $this->log( 'Unexpected phone number format: '.$fullphonenumber );
      $country_code = '64';
      $area_code =    '9';
      $local_number = $fullphonenumber;
      # local_number should contain *only* digits
      preg_replace( '/[^\d]/', '', $local_number );
    }
    return array(
      'name' =>               $params[$prefix.'firstname']. ' '. $params[$prefix.'lastname'],
      'email_address' =>      $params[$prefix.'email'],
      'organisation_name' =>  $params[$prefix.'companyname'],
      'postal_address' =>     array(
                                'line1' =>        $params[$prefix.'address1'],
                                'line2' =>        $params[$prefix.'address2'],
                                'city' =>         $params[$prefix.'city'],
                                'region' =>       $params[$prefix.'state'],
                                'postal_code' =>  $params[$prefix.'postcode'],
                                'country_code' => $params[$prefix.'country'],
      ),
      'phone_number' =>       array(
                                'country_code' => $country_code,
                                'area_code' =>    $area_code,
                                'local_number' => $local_number,
      ),
      'fax_number' =>         NULL,
    );
  }

  function specified_name_servers()
  {
    $name_servers = array();
    for ( $n = 1; $n <= 5; $n++ ) {
      $ns = $this->whmcs_params['ns' . $n];
      if ( $ns != '' ) {
        array_push( $name_servers, array(
          'name'        => $ns,
          'ip4_address' => NULL,
          'ip6_address' => NULL
        ) );
      }
    }
    return $name_servers;
  }

  function handle_fault( $error, $convey_error_codes )
  {
    if ( in_array($error->getCode(),$convey_error_codes) )
      $error_message = $error->getMessage();
    else {
      $this->log( $error );
      $error_message = 'System error.  Please contact the Support team.';
    }
    return array( 'error' => $error_message );
  }

  function copy_contact_details( $contact, &$values, $contact_type )
  {
    $name_parts = str_rpartition( $contact->name, ' ' );
    $values[$contact_type]['First Name'] = $name_parts[0];
    $values[$contact_type]['Last Name']  = $name_parts[1];
  }

  function encoded_contact( $contact_type )
  {
    $cd = $this->whmcs_params['contactdetails'][$contact_type];
    return array( 'name' => $cd['First Name']." ".$cd['Last Name'] );
  }

  function inspect( $label, $value )
  {
    $this->log( $label. ': '. var_export($value,TRUE) );
  }

  public function log( $stuff )
  {
    $f = fopen('/tmp/metaname-module.log', 'a');

    if ( is_subclass_of($stuff,'Exception') )
    {
      $e = $stuff;
      $code = $e->getCode(); $message = $e->getMessage(); $trace = $e->getTraceAsString();
      $stuff = "ERROR $code ( $message) at:\n$trace";
    }
    fwrite( $f, "  ".date('c')."  ".$stuff."\n" );
    fclose( $f );
  }
}

?>
