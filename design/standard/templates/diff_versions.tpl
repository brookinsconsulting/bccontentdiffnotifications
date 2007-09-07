{foreach $object.data_map as $attr}
<div class="block">
<label>{$attr.contentclass_attribute.name|wash}:</label>
<div class="attribute-view-diff">
    {attribute_diff_gui view=diff attribute=$attr old=$oldVersion new=$newVersion diff=$diff[$attr.contentclassattribute_id]}
</div>
</div>
{/foreach}