<?php
require_once 'lib/Metaname.php';

# On the assumption that WHMCS doesn't "catch", any exceptions raised in this
# module should be caught, logged and an "error" array returned:
#
#   return array( 'error' => $error_message );
#
# ..bearing in mind that $error_message will be presented to the end user.
#

function metaname_getConfigArray()
{
  return array(
    'AccountRef' => array(
      'FriendlyName' => 'Account Reference',
      'Description'  => 'Enter your Metaname Account Reference here.',
      'Size'         => '4',
      'Type'         => 'text'
    ),
    'APIKey' => array(
      'FriendlyName' => 'API Key',
      'Description'  => 'Enter your Metaname API Key here.',
      'Size'         => '40',
      'Type'         => 'text'
    ),
    'TestSite' => array(
      'FriendlyName' => 'Test Site',
      'Description'  => 'Tick to use the Metaname Test Site.',
      'Type'         => 'yesno'
    )
  );
}

function metaname_RegisterDomain( $params )
{
  #metaname_RegisterDomain: array (
  #  'domainid' => '14',
  #  'sld' => 'example',
  #  'tld' => 'co.nz',
  #  'regperiod' => '1',
  #  'registrar' => 'metaname',
  #  'ns1' => 'ns1.metaname.net',
  #  'ns2' => 'ns2.metaname.net',
  #  'ns3' => 'ns3.metaname.net',
  #  'ns4' => '',
  #  'ns5' => '',
  #  'transfersecret' => '',
  #  'userid' => '2',
  #  'id' => '2',
  #  'firstname' => 'NE',
  #  'lastname' => 'Test',
  #  'companyname' => 'nearext',
  #  'email' => 'REDACTED',
  #  'address1' => '1 High St',
  #  'address2' => '',
  #  'city' => 'Chch',
  #  'state' => 'Canterbury',
  #  'postcode' => '3456',
  #  'countrycode' => 'NZ',
  #  'country' => 'NZ',
  #  'countryname' => 'New Zealand',
  #  'phonecc' => '64',
  #  'phonenumber' => '34234324234',
  #  'notes' => '',
  #  'password' => 'REDACTED',
  #  'twofaenabled' => '',
  #  'currency' => '1',
  #  'defaultgateway' => '',
  #  'cctype' => '',
  #  'cclastfour' => '',
  #  'securityqid' => '0',
  #  'securityqans' => '',
  #  'groupid' => '0',
  #  'status' => 'Active',
  #  'credit' => '0.00',
  #  'taxexempt' => '',
  #  'latefeeoveride' => '',
  #  'overideduenotices' => '',
  #  'separateinvoices' => '',
  #  'disableautocc' => '',
  #  'emailoptout' => '0',
  #  'overrideautoclose' => '0',
  #  'language' => '',
  #  'lastlogin' => 'REDACTED',
  #  'billingcid' => '0',
  #  'fullstate' => 'Canterbury',
  #  'dnsmanagement' => '1',
  #  'emailforwarding' => '1',
  #  'idprotection' => '1',
  #  'adminfirstname' => 'NE',
  #  'adminlastname' => 'Test',
  #  'admincompanyname' => 'nearext',
  #  'adminemail' => 'REDACTED',
  #  'adminaddress1' => '1 High St',
  #  'adminaddress2' => '',
  #  'admincity' => 'Chch',
  #  'adminfullstate' => 'Canterbury',
  #  'adminstate' => 'Canterbury',
  #  'adminpostcode' => '3456',
  #  'admincountry' => 'NZ',
  #  'adminphonenumber' => '34234324234',
  #  'fullphonenumber' => '+64.34234324234',
  #  'adminfullphonenumber' => '+64.34234324234',
  #  'original' => 
  #    'domainid' => '14',
  #    'sld' => 'netest',
  #    'tld' => 'co.nz',
  #    'regperiod' => '1',
  #    'registrar' => 'metaname',
  #    'ns1' => 'ns1.metaname.net',
  #    'ns2' => 'ns2.metaname.net',
  #    'ns3' => 'ns3.metaname.net',
  #    'ns4' => '',
  #    'ns5' => '',
  #    'transfersecret' => NULL,
  #    'userid' => '2',
  #    'id' => '2',
  #    'firstname' => 'NE',
  #    'lastname' => 'Test',
  #    'companyname' => 'nearext',
  #    'email' => 'REDACTED',
  #    'address1' => '1 High St',
  #    'address2' => '',
  #    'city' => 'Chch',
  #    'state' => 'Canterbury',
  #    'postcode' => '3456',
  #    'countrycode' => 'NZ',
  #    'country' => 'NZ',
  #    'countryname' => 'New Zealand',
  #    'phonecc' => 64,
  #    'phonenumber' => '34234324234',
  #    'notes' => '',
  #    'password' => 'REDACTED',
  #    'twofaenabled' => false,
  #    'currency' => '1',
  #    'defaultgateway' => '',
  #    'cctype' => '',
  #    'cclastfour' => '',
  #    'securityqid' => '0',
  #    'securityqans' => '',
  #    'groupid' => '0',
  #    'status' => 'Active',
  #    'credit' => '0.00',
  #    'taxexempt' => '',
  #    'latefeeoveride' => '',
  #    'overideduenotices' => '',
  #    'separateinvoices' => '',
  #    'disableautocc' => '',
  #    'emailoptout' => '0',
  #    'overrideautoclose' => '0',
  #    'language' => '',
  #    'lastlogin' => 'REDACTED',
  #    'billingcid' => '0',
  #    'fullstate' => 'Canterbury',
  #    'dnsmanagement' => true,
  #    'emailforwarding' => true,
  #    'idprotection' => true,
  #    'adminfirstname' => 'NE',
  #    'adminlastname' => 'Test',
  #    'admincompanyname' => 'nearext',
  #    'adminemail' => 'REDACTED',
  #    'adminaddress1' => '1 High St',
  #    'adminaddress2' => '',
  #    'admincity' => 'Chch',
  #    'adminfullstate' => 'Canterbury',
  #    'adminstate' => 'Canterbury',
  #    'adminpostcode' => '3456',
  #    'admincountry' => 'NZ',
  #    'adminphonenumber' => '34234324234',
  #    'fullphonenumber' => '+64.34234324234',
  #    'adminfullphonenumber' => '+64.34234324234',
  #  ),
  #
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_RegisterDomain', $params );
  $domain_name = $metaname->specified_domain_name();
  $term_in_months = $metaname->specified_term_in_months();
  $contacts = $metaname->specified_contacts();
  #$metaname->inspect( 'contacts', $contacts );
  $name_servers = $metaname->specified_name_servers();
  #$metaname->inspect( 'name_servers', $name_servers );
  try {
    $udai = $metaname->register_domain_name( $domain_name, $term_in_months, $contacts, $name_servers );
    $message = "$domain_name has been registered for $term_in_months months";
    if ( $udai != null )
      $message .= ".  Its UDAI is $udai";
    return array( 'info' => $message );
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-4,-6,-7,-8,-9,-11,-14) );
  }
}

function metaname_TransferDomain( $params )
{
  #metaname_TransferDomain: array (
  #  'domainid' => '15',
  #  'sld' => 'example',
  #  'tld' => 'com',
  #  'registrar' => 'metaname',
  #  'userid' => '2',
  #  'id' => '2',
  #  'firstname' => 'NE',
  #  'lastname' => 'Test',
  #  'companyname' => 'nearext',
  #  'email' => 'REDACTED',
  #  'address1' => '1 High St',
  #  'address2' => '',
  #  'city' => 'Chch',
  #  'state' => 'Canterbury',
  #  'postcode' => '3456',
  #  'countrycode' => 'NZ',
  #  'country' => 'NZ',
  #  'countryname' => 'New Zealand',
  #  'phonecc' => '64',
  #  'phonenumber' => '34234324234',
  #  'notes' => '',
  #  'password' => 'REDACTED',
  #  'twofaenabled' => '',
  #  'currency' => '1',
  #  'defaultgateway' => '',
  #  'cctype' => '',
  #  'cclastfour' => '',
  #  'securityqid' => '0',
  #  'securityqans' => '',
  #  'groupid' => '0',
  #  'status' => 'Active',
  #  'credit' => '0.00',
  #  'taxexempt' => '',
  #  'latefeeoveride' => '',
  #  'overideduenotices' => '',
  #  'separateinvoices' => '',
  #  'disableautocc' => '',
  #  'emailoptout' => '0',
  #  'overrideautoclose' => '0',
  #  'language' => '',
  #  'lastlogin' => 'REDACTED',
  #  'billingcid' => '0',
  #  'fullstate' => 'Canterbury',
  #  'regperiod' => '1',
  #  'dnsmanagement' => '',
  #  'emailforwarding' => '',
  #  'idprotection' => '',
  #  'adminfirstname' => 'NE',
  #  'adminlastname' => 'Test',
  #  'admincompanyname' => 'nearext',
  #  'adminemail' => 'REDACTED',
  #  'adminaddress1' => '1 High St',
  #  'adminaddress2' => '',
  #  'admincity' => 'Chch',
  #  'adminfullstate' => 'Canterbury',
  #  'adminstate' => 'Canterbury',
  #  'adminpostcode' => '3456',
  #  'admincountry' => 'NZ',
  #  'adminphonenumber' => '34234324234',
  #  'fullphonenumber' => '+64.34234324234',
  #  'adminfullphonenumber' => '+64.34234324234',
  #  'ns1' => 'ns1.kiwiping.com',
  #  'ns2' => 'ns2.kiwiping.com',
  #  'ns3' => '',
  #  'ns4' => '',
  #  'ns5' => '',
  #  'original' => 
  #  array (
  #    'domainid' => '15',
  #    'sld' => 'example',
  #    'tld' => 'com',
  #    'registrar' => 'metaname',
  #    'userid' => '2',
  #    'id' => '2',
  #    'firstname' => 'NE',
  #    'lastname' => 'Test',
  #    'companyname' => 'nearext',
  #    'email' => 'REDACTED',
  #    'address1' => '1 High St',
  #    'address2' => '',
  #    'city' => 'Chch',
  #    'state' => 'Canterbury',
  #    'postcode' => '3456',
  #    'countrycode' => 'NZ',
  #    'country' => 'NZ',
  #    'countryname' => 'New Zealand',
  #    'phonecc' => 64,
  #    'phonenumber' => '34234324234',
  #    'notes' => '',
  #    'password' => 'REDACTED',
  #    'twofaenabled' => false,
  #    'currency' => '1',
  #    'defaultgateway' => '',
  #    'cctype' => '',
  #    'cclastfour' => '',
  #    'securityqid' => '0',
  #    'securityqans' => '',
  #    'groupid' => '0',
  #    'status' => 'Active',
  #    'credit' => '0.00',
  #    'taxexempt' => '',
  #    'latefeeoveride' => '',
  #    'overideduenotices' => '',
  #    'separateinvoices' => '',
  #    'disableautocc' => '',
  #    'emailoptout' => '0',
  #    'disableautocc' => '',
  #    'emailoptout' => '0',
  #    'overrideautoclose' => '0',
  #    'language' => '',
  #    'lastlogin' => 'REDACTED',
  #    'billingcid' => '0',
  #    'fullstate' => 'Canterbury',
  #    'regperiod' => '1',
  #    'dnsmanagement' => false,
  #    'emailforwarding' => false,
  #    'idprotection' => false,
  #    'adminfirstname' => 'NE',
  #    'adminlastname' => 'Test',
  #    'admincompanyname' => 'nearext',
  #    'adminemail' => 'REDACTED',
  #    'adminaddress1' => '1 High St',
  #    'adminaddress2' => '',
  #    'admincity' => 'Chch',
  #    'adminfullstate' => 'Canterbury',
  #    'adminstate' => 'Canterbury',
  #    'adminpostcode' => '3456',
  #    'admincountry' => 'NZ',
  #    'adminphonenumber' => '34234324234',
  #    'fullphonenumber' => '+64.34234324234',
  #    'adminfullphonenumber' => '+64.34234324234',
  #    'ns1' => 'ns1.kiwiping.com',
  #    'ns2' => 'ns2.kiwiping.com',
  #    'ns3' => '',
  #    'ns4' => '',
  #    'ns5' => '',
  #  ),
  #  'eppcode' => 'test',
  #  'transfersecret' => 'test',
  #
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_TransferDomain', $params );
  $domain_name = $metaname->specified_domain_name();
  try {
    if ( $metaname->nz_name_specified() )
    {
      $udai = $params['eppcode'];
      $new_udai = $metaname->import_nz_domain_name( $domain_name, $udai );
      return array( 'info' => "$domain_name has been transferred to your account.  Its new UDAI is $new_udai" );
    }
    else {
      $metaname->import_other_domain_name( $domain_name, $metaname->specified_contacts() );
    }
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-4,-6,-8,-15) );
  }
}

function metaname_RenewDomain( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_RenewDomain', $params );
  $domain_name = $metaname->specified_domain_name();
  $term_in_months = $metaname->specified_term_in_months();
  try {
    $metaname->renew_domain_name( $domain_name, $term_in_months );
    return array( 'info' => "$domain_name has been renewed for $term_in_months months" );
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-4,-5,-11) );
  }
}

function metaname_GetNameservers( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_GetNameservers', $params );
  $domains = $metaname->domain_names();
  #$metaname->inspect( 'dms', $domains );
  $domain = $metaname->domain_named( $metaname->specified_domain_name(), $domains );
  if ( $domain != NULL )
  {
    $values = array();
    for ( $i=0, $n=1;  $n <= count($domain->name_servers);  $i+=1, $n+=1 )
    {
      $values['ns'.$n] = $domain->name_servers[$i]->name;
    }
    return $values;
  }
  else
    return array( 'error' => 'This domain does not appear to be in your portfolio' );
}

function metaname_SaveNameservers( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_SaveNameservers', $params );
  try {
    $metaname->update_name_servers( $metaname->specified_domain_name(), $metaname->specified_name_servers() );
    return NULL;
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-4,-5,-9,-13) );
  }
}

function metaname_GetContactDetails( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_GetContactDetails', $params );
  $domains = $metaname->domain_names();
  $domain = $metaname->domain_named( $metaname->specified_domain_name(), $domains );
  if ( $domain != NULL )
  {
    $values = array();
    $metaname->copy_contact_details( $domain->contacts->registrant, $values, 'Registrant' );
    $metaname->copy_contact_details( $domain->contacts->admin,      $values, 'Admin' );
    $metaname->copy_contact_details( $domain->contacts->technical,  $values, 'Tech' );
    #$metaname->inspect( 'vl', $values );
    return $values;
  }
  else
    return array( 'error' => 'This domain does not appear to be in your portfolio' );
}

function metaname_SaveContactDetails( $params )
{
  #metaname_SaveContactDetails: array (
  #  'domainid' => '14',
  #  'sld' => 'example',
  #  'tld' => 'co.nz',
  #  'regperiod' => '1',
  #  'registrar' => 'metaname',
  #  'regtype' => 'Register',
  #  'contactdetails' => 
  #  array (
  #    'Registrant' => 
  #    array (
  #      'First Name' => 'NE2',
  #      'Last Name' => 'Test',
  #    ),
  #    'Admin' => 
  #    array (
  #      'First Name' => 'NE2',
  #      'Last Name' => 'Test',
  #    ),
  #    'Tech' => 
  #    array (
  #      'First Name' => 'NE2',
  #      'Last Name' => 'Test',
  #    ),
  #  ),
  #)
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_SaveContactDetails', $params );
  $contacts = array(
    'registrant' => $metaname->encoded_contact('Registrant'),
    'admin'      => $metaname->encoded_contact('Admin'),
    'technical'  => $metaname->encoded_contact('Tech')
  );
  try {
    $metaname->update_contacts( $metaname->specified_domain_name(), $contacts );
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-4,-5,-6,-8) );
  }
}

function metaname_GetRegistrarLock( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_GetRegistrarLock', $params );
  try {
    return $metaname->domain_name_is_locked( $metaname->specified_domain_name() ) ? 'locked' : 'unlocked';
  }
  catch ( JsonRpcFault $e )
  {
    # Errors -4, -5 and -18 are also documented for domain_name_is_locked
    # although any of these would be a WHMCS system error since this method
    # should be invoked only for domain names in the reseller's portfolio
    return $metaname->handle_fault( $e, array(-19) );
  }
}

function metaname_SaveRegistrarLock( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_SaveRegistrarLock', $params );
  try {
    $domain_name = $metaname->specified_domain_name();
    if ( $params['lockenabled'] )
      return $metaname->lock_domain_name( $domain_name );
    else
      return $metaname->unlock_domain_name( $domain_name );
  }
  catch ( JsonRpcFault $e )
  {
    # Errors -4 is also documented for lock_domain_name and unlock_domain_name
    # although any of these would be a WHMCS system error since this method
    # should be invoked only for domain names in the reseller's portfolio.
    # Error -18 is a system error anyway and is translated to the generic
    # system error
    return $metaname->handle_fault( $e, array(-5,-19) );
  }
}

function metaname_GetDNS( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_GetDNS', $params );
  try {
    $records = $metaname->dns_zone( $metaname->specified_domain_name() );
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-5,-12) );
  }
}

function metaname_SaveDNS( $params )
{
  $metaname = new Metaname( $params );
  $metaname->inspect( 'metaname_SaveDNS', $params );
  try {
    return array( 'error' => 'Not implemented' );
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-5,-12,-16,-17) );
  }
}

function metaname_GetEPPCode( $params )
{
  $metaname = new Metaname( $params );
  #$metaname->inspect( 'metaname_GetEPPCode', $params );
  try {
    $udai = $metaname->reset_domain_name_secret( $metaname->specified_domain_name() );
    if ( $udai )
      return array( 'eppcode' => $udai );
    # Otherwise, no value is returned and WHMCS assumes that the EPP code has
    # been e-mailed to the Admin contact
  }
  catch ( JsonRpcFault $e )
  {
    return $metaname->handle_fault( $e, array(-5) );
  }
}

