{if $rows}
  <div id="ltype">
     <div>
        {strip}
        <table cellpadding="0" cellspacing="0" border="0">
           <thead class="sticky">
            {foreach from=$headers item=header}
              <th>{$header}</th>
            {/foreach}
          </thead>
         {foreach from=$rows item=row}
         <tr>
          <td>{$row.donor_name}</td>
          <td>{$row.address}</td>
          <td>{$row.state}</td>
          <td>{$row.donor_spouse_name}</td>
          <td>{$row.employer}</td>
          <td>{$row.creator}</td>
           <td>
             <a href="{$row.IS}" title="{ts}Edit Search{/ts}" class="action-item crm-hover-button"><i class="crm-i fa-pencil"></i></a>
             <a href="{$row.delete}" title="{ts}Delete{/ts}" class="action-item crm-hover-button"><i class="crm-i fa-trash"></i></a>
           </td>
        </tr>
        {/foreach}
         </table>
        {/strip}

    </div>
  </div>

  <div class="action-link">
    <a href="{crmURL  p='civicrm/ds/open-search' q="reset=1"}" class="button">
      <span>
        <div class="crm-i fa-plus"></div>
        {ts}New Donor Search{/ts}
      </span>
    </a>
  </div>
{else}
  <div class="messages status no-popup crm-empty-table">
    <div class="icon inform-icon"></div>
    {ts}None found.{/ts}
  </div>
{/if}
