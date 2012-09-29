<style type="text/css">
.property {ldelim}
  width:auto !important;
}
</style>

<div class="titrePage">
	<h2>Comments Blacklist</h2>
</div>

<form method="post" action="" class="properties">
<fieldset>
  <ul>
    <li>
      <span class="property">{'Action'|@translate}</span>
      <label><input type="radio" name="action" value="moderate" {if $action=='moderate'}checked="checked"{/if}> {'Moderate'|@translate}</label>
      <label><input type="radio" name="action" value="reject" {if $action=='reject'}checked="checked"{/if}> {'Reject'|@translate}</label>
    </li>
    <li>
      <span class="property">{'Blacklist'|@translate} <span style="font-weight:normal;">({'one word per line, case insensitive'|@translate})</span></span>
      <br>
      <textarea name="content" style="width:500px;height:300px;">{$blacklist}</textarea>
    </li>
  </ul>
</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|@translate}"></p>

</form>