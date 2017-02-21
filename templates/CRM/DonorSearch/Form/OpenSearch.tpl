
{foreach from=$donorFields key='elementName' item='apiName'}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{literal}
<script type="text/javascript">
  CRM.$(function($) {
    var $form = $("form.{/literal}{$form.formClass}{literal}");

    function showHideFields() {
      $('div.crm-section', $form).not(':first').toggle(!!$(this).val());
    }

    function autoFill() {
      var cid = $(this).val(),
        spouseTypes = {/literal}{$spouseTypes|@json_encode}{literal},
        donorFields = {/literal}{$donorFields|@json_encode}{literal},
        contactFields = [];
      $.each(donorFields, function(k, v) {
        if (v.indexOf('.') < 0) {
          contactFields.push(v);
        }
      });
       CRM.api3({
        contact: ['Contact', 'getsingle', {id: cid, return: contactFields}],
        relationship1: ['Relationship', 'get', {
          sequential: 1,
          is_active: 1,
          end_date: {'IS NULL': 1},
          relationship_type_id: {IN: spouseTypes},
          contact_id_a: cid,
          return: ['contact_id_b.first_name', 'contact_id_b.middle_name', 'contact_id_b.last_name']
        }],
        relationship2: ['Relationship', 'get', {
          sequential: 1,
          is_active: 1,
          end_date: {'IS NULL': 1},
          relationship_type_id: {IN: spouseTypes},
          contact_id_b: cid,
          return: ['contact_id_a.first_name', 'contact_id_a.middle_name', 'contact_id_a.last_name']
        }]
      }).done(function(result) {
         $.each(result.relationship1.values.concat(result.relationship2.values), function(k, rel) {console.log(rel);
           result.contact['spouse.first_name'] = rel['contact_id_a.first_name'] || rel['contact_id_b.first_name'];
           result.contact['spouse.middle_name'] = rel['contact_id_a.middle_name'] || rel['contact_id_b.middle_name'];
           result.contact['spouse.last_name'] = rel['contact_id_a.last_name'] || rel['contact_id_b.last_name'];
         });
         $.each(donorFields, function(field, name) {
           $('input[name=' + field + ']', $form).val(result.contact[name] || '');
         });
       });
    }

    $('input[name=id]', $form).each(showHideFields).change(showHideFields).change(autoFill);
  });
</script>
{/literal}