{if $rows}
  <div id="ltype">
     <div class="form-item">
        {strip}
        <table cellpadding="0" cellspacing="0" border="0">
           <thead class="sticky">
            {foreach from=$headers item=header}
              <th>{$header}</th>
            {/foreach}
          </thead>
         {foreach from=$rows item=row}
         <tr>
          <td>{$row.IS}</td>
          <td>{$row.donor_name}</td>
          <td>{$row.address}</td>
          <td>{$row.state}</td>
          <td>{$row.donor_spouse_name}</td>
          <td>{$row.employer}</td>
          <td>{$row.searched_for}</td>
        </tr>
        {/foreach}
         </table>
        {/strip}

    </div>
  </div>

  <div class="action-link">
    <a href="{crmURL  p='civicrm/ds/open-search' q="reset=1"}" class="button">
      <span>
        <div class="icon add-icon"></div>
        {ts}New Donor Search{/ts}
      </span>
    </a>
  </div>
{else}
  <h1 class="help">
    <center>
      <p>No record found. Submit a new donor search <a href="{crmURL p='civicrm/ds/open-search' q='reset=1'}">here</a>.</p>
    </center>
  </h1>
{/if}
