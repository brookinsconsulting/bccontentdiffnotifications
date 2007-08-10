#!/usr/bin/env php
<?php

include_once( 'kernel/classes/ezscript.php' );
include_once( 'lib/ezutils/classes/ezcli.php' );

$cli = eZCLI::instance();

$scriptSettings = array();
$scriptSettings['description'] = 'Send a content diff e-mail';
$scriptSettings['use-session'] = true;
$scriptSettings['use-modules'] = false;
$scriptSettings['use-extensions'] = false;

$script = eZScript::instance( $scriptSettings );
$script->startup();

$config = '[file-transport]';
$argumentConfig = '[receiver][object-id][old-version][new-version]';
$optionHelp = array( 'file-transport' => "Use the file mail transport,\noverrides the configured mail transport\nfor debugging purposes." );
$arguments = false;

$useStandardOptions = true;

$options = $script->getOptions( $config, $argumentConfig, $optionHelp, $arguments, $useStandardOptions );
$script->initialize();


if ( count( $options['arguments'] ) < 4 )
{
    $script->shutdown( 1, 'wrong argument count' );
}

$email      = $options['arguments'][0];
$objectID   = $options['arguments'][1];
$oldVersion = $options['arguments'][2];
$newVersion = $options['arguments'][3];

include_once( 'kernel/classes/ezcontentobject.php' );
$object = eZContentObject::fetch( $objectID );
$oldObject = $object->version( $oldVersion );
$newObject = $object->version( $newVersion );

$oldAttributes = $oldObject->dataMap();
$newAttributes = $newObject->dataMap();

foreach ( $oldAttributes as $attribute )
{
    $newAttr = $newAttributes[$attribute->attribute( 'contentclass_attribute_identifier' )];
    $contentClassAttr = $newAttr->attribute( 'contentclass_attribute' );
    $diff[$contentClassAttr->attribute( 'id' )] = $contentClassAttr->diff( $attribute, $newAttr, $extraOptions );
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'oldVersion', $oldVersion );
$tpl->setVariable( 'oldVersionObject', $oldObject );

$tpl->setVariable( 'newVersion', $newVersion );
$tpl->setVariable( 'newVersionObject', $newObject );

$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'diff', $diff );

$body = $tpl->fetch( 'design:test_html_mail.tpl' );

include_once( 'lib/ezutils/classes/ezmail.php' );
$mail = new eZMail();

// Note: Change email receiver to setting
$mail->setReceiver( 'info@example.com' );

// Note: Change email sender to setting
$mail->setSender( 'info@example.com' );

$mail->setBody( $body );
$mail->setContentType( 'text/html', false, '7bit' );
$mail->setSubject( 'Test mail diff' );

if ( $options['file-transport'] )
{
    include_once( 'lib/ezutils/classes/ezfiletransport.php' );
    $f = new eZFileTransport();
    $success = $f->sendMail( $mail );
}
else
{
    include_once( 'lib/ezutils/classes/ezmailtransport.php' );
    $n = new eZMailTransport();
    $success = $n->send( $mail );
}

$debug = eZDebug::instance();
$debug->writeDebug( $success, 'success?' );

$script->shutdown( 0 );

?>