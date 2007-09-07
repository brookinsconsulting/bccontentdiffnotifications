<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{set-block scope=root variable=content_type}text/html{/set-block}
<html>
<head>
<style>
{literal}
<!--
  div.block { margin-bottom: 1em; }
  label { font-weight: bold; font-size: 120%; }
  del { background-color: red; }
  ins { background-color: green; }
-->
{/literal}
</style>
</head>
<body bgcolor="#ffffff" text="#000000">

{let use_url_translation=ezini('URLTranslator','Translation')|eq('enabled')
     is_update=false()}
{section loop=$object.versions}{section show=and($:item.status|eq(3),$:item.version|ne($object.current_version))}{set is_update=true()}{/section}{/section}
{section show=$is_update}
{set-block scope=root variable=subject}{$object.content_class.name|wash} {'"%name" was updated'|i18n('design/standard/notification', '', hash( '%name', $object.name|wash ))} [{ezini("SiteSettings","SiteURL")} - {$object.main_node.parent.name|wash}]{/set-block}
{set-block scope=root variable=from}{concat($object.current.creator.name|wash,' <', $sender, '>')}{/set-block}
{set-block scope=root variable=message_id}{concat('<node.',$object.main_node_id,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{/set-block}
{set-block scope=root variable=reply_to}{concat('<node.',$object.main_node_id,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{/set-block}
{set-block scope=root variable=references}{section name=Parent loop=$object.main_node.path_array}{concat('<node.',$:item,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{delimiter}{" "}{/delimiter}{/section}{/set-block}
<p>{"This email is to inform you that an updated item has been published at %sitename."|i18n('design/standard/notification','',hash('%sitename',ezini("SiteSettings","SiteURL")))}<br />
{"The item can be viewed by using the link below."|i18n('design/standard/notification')}</p>

<p><a href="http://{ezini("SiteSettings","SiteURL")}{cond( $use_url_translation, $object.main_node.url_alias|ezurl(no),
                                               true(), concat( "/content/view/full/", $object.main_node_id )|ezurl(no) )}">{$object.name|wash}</a> - {$object.current.creator.name|wash} (Owner: {$object.owner.name|wash})</p>

{def $last_version=fetch( bccontentdiffnotifications, last_archived_version, hash( id, $object.id ) )
     $diff=fetch( bccontentdiffnotifications, diff_versions, hash( object, $object, lastversion, $last_version ) )}

{$diff}

{undef $last_version $diff}

{section-else}
{set-block scope=root variable=subject}{$object.content_class.name|wash} {'"%name" was published'|i18n('design/standard/notification', '', hash( '%name', $object.name|wash ))} [{ezini("SiteSettings","SiteURL")} - {$object.main_node.parent.name|wash}]{/set-block}
{set-block scope=root variable=from}{concat($object.owner.name,' <', $sender, '>')}{/set-block}
{set-block scope=root variable=message_id}{concat('<node.',$object.main_node_id,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{/set-block}
{set-block scope=root variable=reply_to}{concat('<node.',$object.main_node.parent_node_id,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{/set-block}
{set-block scope=root variable=references}{section name=Parent loop=$object.main_node.parent.path_array}{concat('<node.',$:item,'.eznotification','@',ezini("SiteSettings","SiteURL"),'>')}{delimiter}{" "}{/delimiter}{/section}{/set-block}
<p>{"This email is to inform you that a new item has been published at %sitename."|i18n('design/standard/notification','',hash('%sitename',ezini("SiteSettings","SiteURL")))}<br/>
{"The item can be viewed by using the link below."|i18n('design/standard/notification')}</p>

<p><a href="http://{ezini("SiteSettings","SiteURL")}{cond( $use_url_translation, $object.main_node.url_alias|ezurl(no),
                                               true(), concat( "/content/view/full/", $object.main_node_id )|ezurl(no) )}">{$object.name|wash}</a> - {$object.owner.name|wash}</p>
{/section}

<p>{"If you do not wish to continue receiving these notifications,
change your settings at:"|i18n('design/standard/notification')}<br />
<a href="http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}">http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}</a></p>
<p>
--<br/>
{"%sitename notification system"
 |i18n('design/standard/notification',,
       hash('%sitename',ezini("SiteSettings","SiteURL")))}</p>
{/let}

</body>
</html>