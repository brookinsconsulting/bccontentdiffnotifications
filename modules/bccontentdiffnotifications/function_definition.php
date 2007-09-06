<?php
//
// Created on: <04-Sep-2007 12:48:30 gb>
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

/*! \file function_definition.php
*/

$FunctionList = array();
$FunctionList['last_archived_version'] = array( 'name' => 'last_archived_version',
						'operation_types' => array( 'read' ),
						'call_method' => array( 'include_file' => 'extension/bccontentdiffnotifications/modules/bccontentdiffnotifications/bccontentdiffnotificationsfunctioncollection.php',
									'class' => 'BcContentDiffNotificationFunctionCollection',
									'method' => 'fetchLastObjectVersionFetch' ),
						'parameter_type' => 'standard',
						'parameters' => array( array( 'name' => 'id',
									      'type' => 'integer',
									      'required' => true ) ) );

$FunctionList['diff_versions'] = array( 'name' => 'diff_versions',
					'operation_types' => array( 'read' ),
					'call_method' => array( 'include_file' => 'extension/bccontentdiffnotifications/modules/bccontentdiffnotifications/bccontentdiffnotificationsfunctioncollection.php',
								'class' => 'BcContentDiffNotificationFunctionCollection',
								'method' => 'fetchDiffVersionsFetch' ),
					'parameter_type' => 'standard',
					'parameters' => array( array( 'name' => 'object',
								      'type' => 'object',
								      'required' => true ),
							       array( 'name' => 'lastversion',
								      'type' => 'object',
								      'required' => true )
							       ) );

?>