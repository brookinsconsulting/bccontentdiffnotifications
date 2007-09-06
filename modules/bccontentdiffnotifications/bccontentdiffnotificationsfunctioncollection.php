<?php
//
// Definition of BcContentDiffNotificationFunctionCollection class
//
// Created on: <04-Sep-2007 16:42:00 gb>
//
// COPYRIGHT NOTICE: Copyright (C) 2007 Brookins Consulting
// SOFTWARE LICENSE: GNU General Public License v2.0 (or later)
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

/*! \file bccontentdiffnotificationsfunctioncollection.php
*/

/*!
  \class BcContentDiffNotificationFunctionCollection bccontentdiffnotificationsfunctioncollection.php
  \brief The class BcContentDiffNotificationFunctionCollection does

*/

class BcContentDiffNotificationFunctionCollection
{
    /*!
     Constructor
    */
    function BcContentDiffNotificationFunctionCollection()
    {
    }

    /*!
     Fetch the last content object version with a status of 'archived'
    */
    function &fetchLastObjectVersionFetch( $objectID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
	$ret = false;

	// First fetch content object
	$object = eZContentObject::fetch( $objectID );

	// Test result for objectivity
	if ( is_object( $object ) )
	{
	  // Fetch the last version
	  $oldObject = $object->version( $oldVersion );

	  // Fetch the current version
	  $version =& $object->currentVersion(); 
	  $lastVersion = $version->attribute( 'version' ) -1;
	  $lastObject = $object->version( $lastVersion );  

	  $ret = $lastObject;
	}

        return array( 'result' => $ret );
    }

    /*!
     Fetch the content diff of the current and last version of a content object
    */
    function &fetchDiffVersionsFetch( $object, $lastVersion )
    {
	$ret = false;

	if ( is_object( $object ) )
	{
 	    $newVersion = $object->attribute( 'version' );
	    $oldVersion = $lastVersion->attribute( 'version' );

	    $newObject = $object;
	    $oldObject = $lastVersion;

	    if ( is_object( $oldObject ) && is_object( $newObject ) )
	    {
	      // Fetch datamaps
	      $oldAttributes = $oldObject->dataMap();
	      $newAttributes = $newObject->dataMap();

	      // Fetch attribute diff output
	      foreach ( $oldAttributes as $attribute )
	      {
		$newAttr = $newAttributes[$attribute->attribute( 'contentclass_attribute_identifier' )];
		$contentClassAttr = $newAttr->attribute( 'contentclass_attribute' );
		$diff[$contentClassAttr->attribute( 'id' )] = $contentClassAttr->diff( $attribute, $newAttr, $extraOptions );
	      }

	      // Prepare template display of attribute diff output
	      include_once( 'kernel/common/template.php' );
	      $tpl = templateInit();

	      $tpl->setVariable( 'oldVersion', $oldVersion );
	      $tpl->setVariable( 'oldVersionObject', $oldObject );

	      $tpl->setVariable( 'newVersion', $newVersion );
	      $tpl->setVariable( 'newVersionObject', $newObject );

	      $tpl->setVariable( 'object', $object );
	      $tpl->setVariable( 'diff', $diff );

	      $diff = $tpl->fetch( 'design:diff_versions.tpl' );
	      $ret = $diff;
	    }
	}

        return array( 'result' => $ret );
    }
}

?>