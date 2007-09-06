{*
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head>
<body bgcolor="#ffffff" text="#000000">
*}
{foreach $object.data_map as $attr}
<div class="block">
<label>{$attr.contentclass_attribute.name}:</label>
<div class="attribute-view-diff">
    {attribute_diff_gui view=diff attribute=$attr old=$oldVersion new=$newVersion diff=$diff[$attr.contentclassattribute_id]}
</div>
</div>
{/foreach}
{*
</body>
</html> *}
