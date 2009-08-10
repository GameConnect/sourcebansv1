          <div id="admin-page-menu">
            <ul>
              {if $permission_list_admins}
              <li id="tab-list"><a href="#list">{$lang_list_admins}</a></li>
              {/if}
              {if $permission_add_admins}
              <li id="tab-add"><a href="#add">{$lang_add_admin}</a></li>
              {/if}
              {if $permission_import_admins}
              <li id="tab-import"><a href="#import">{$lang_import_admins}</a></li>
              {/if}
              {if $permission_list_actions}
              <li id="tab-actions"><a href="#actions">{$lang_actions_log}</a></li>
              {/if}
              {foreach from=$admin_tabs item=tab}
              <li{if !empty($tab.id)} id="tab-{$tab.id}"{/if}><a href="{$tab.url}">{$tab.name}</a></li>
              {/foreach}
            </ul>
            <br />
            <div align="center">
              <img alt="{$page_title}" src="themes/{$theme_dir}/images/admin/admins.png" title="{$page_title}" />
            </div>
          </div>
          <div id="admin-page-content">
            {if $permission_list_admins}
            <div id="pane-list">
              <h3>{$lang_admins} (<span id="admins_count">{$total}</span>)</h3>
              Click on an admin to see more detailed information and actions to perform on them.<br /><br />
              <div align="center">
                <table class="listtable" cellpadding="0" cellspacing="0" width="80%">
                  <tr class="sea_open">
                    <th class="left" colspan="3">{$lang_advanced_search} <span class="normal">({$lang_click})</span></th>
                  </tr>
                  <tr>
                    <td>
                      <form action="{$active}" class="panel" method="get">
                        <fieldset>
                          <table class="listtable" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                              <td class="listtable_1" width="8%" align="center"><input id="name_" name="type" type="radio" value="name" /></td>
                              <td class="listtable_1" width="26%">{$lang_name}</td>
                              <td class="listtable_1" width="66%"><input id="nick" value="" onmouseup="$('name_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 249px;" /></td>
                            </tr>
                            <tr>
                              <td align="center" class="listtable_1"><input id="steam_" type="radio" name="type" value="steam" /></td>
                              <td class="listtable_1">Steam ID</td>
                              <td class="listtable_1">
                                <input id="steamid" onmouseup="$('steam_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 145px;" />
                                <select id="steam_match" onmouseup="$('steam_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 100px;">
                                  <option label="exact" value="0">Exact Match</option>
                                  <option label="partial" value="1">Partial Match</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td align="center" class="listtable_1"><input id="srvadmgroup_" type="radio" name="type" value="srvadmgroup" /></td>
                              <td class="listtable_1">{$lang_server_admin_group}</td>
                              <td class="listtable_1">
                                <select id="srvadmgroup" onmouseup="$('srvadmgroup_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 251px;">
                                  {foreach from=$server_admin_groups item=group key=group_id}
                                  <option value="{$group_id}">{$group.name}</option>
                                  {/foreach}
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td align="center" class="listtable_1"><input id="webadmgroup_" type="radio" name="type" value="webadmgroup" /></td>
                              <td class="listtable_1">{$lang_web_admin_group}</td>
                              <td class="listtable_1">
                                <select id="webadmgroup" onmouseup="$('webadmgroup_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 251px;">
                                  {foreach from=$web_admin_groups item=group key=group_id}
                                  <option value="{$group_id}">{$group.name}</option>
                                  {/foreach}
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td class="listtable_1" align="center"><input id="admsrvflags_" name="type" type="radio" value="admsrvflags" /></td>
                              <td class="listtable_1">{$lang_server_permissions}</td>
                              <td class="listtable_1">
                                <select id="admsrvflag" name="admsrvflag" size="5" multiple="multiple" onmouseup="$('admsrvflags_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 251px;">
                                  {foreach from=$admin_server_flags item=flag}
                                  <option value="{$flag.flag}">{$flag.name}</option>
                                  {/foreach}
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td class="listtable_1" align="center"><input id="admwebflags_" name="type" type="radio" value="admwebflags" /></td>
                              <td class="listtable_1">{$lang_web_permissions}</td>
                              <td class="listtable_1">
                                <select id="admwebflag" name="admwebflag" size="5" multiple="multiple" onmouseup="$('admwebflags_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 251px;">
                                  {foreach from=$admin_web_flags item=flag}
                                  <option value="{$flag.flag}">{$flag.name}</option>
                                  {/foreach}
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td class="listtable_1" align="center"><input id="server_" name="type" type="radio" value="server" /></td>
                              <td class="listtable_1">{$lang_server}</td>
                              <td class="listtable_1">
                                <select id="server" onmouseup="$('server_').checked = true" style="border: 1px solid #000000; font-size: 12px; background-color: rgb(215, 215, 215); width: 251px;">
                                  {foreach from=$servers item=server key=server_id}
                                  <option id="host_{$server_id}" value="{$server_id}">Querying Server Data...</option>
                                  {/foreach}
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td class="center" colspan="3"><input class="ok btn" type="submit" value="{$lang_search}" /></td>
                            </tr>
                          </table>
                        </fieldset>
                      </form>
                    </td>
                  </tr>
                </table>
              </div>
              <div id="banlist-nav">
                {eval var=$lang_displaying_results}
                {if $total_pages > 1}
                {if $smarty.get.page > 1}
                | <strong><a href="{$active}{build_query page=$smarty.get.page-1}"><img alt="{$lang_prev|ucfirst}" src="images/left.gif" style="vertical-align: middle;" title="{$lang_prev|ucfirst}" /> {$lang_prev}</a></strong>
                {/if}
                {if $smarty.get.page < $total_pages}
                | <strong><a href="{$active}{if isset($smarty.get.page)}{build_query page=$smarty.get.page+1}{else}{build_query page=2}{/if}">{$lang_next} <img alt="{$lang_next|ucfirst}" src="images/right.gif" style="vertical-align: middle;" title="{$lang_next|ucfirst}" /></a></strong>
                {/if}
                <select onchange="SetContent('content', 'admin_admins.php?page=' + this.options[this.selectedIndex].value);">
                  {section loop=$total_pages name=page}
                  <option{if $smarty.get.page == $smarty.section.page.iteration} selected="selected"{/if} value="{$smarty.section.page.iteration}">{$smarty.section.page.iteration}</option>
                  {/section}
                </select>
                {/if}
              </div>
              <table width="99%" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <th class="icon"><input {nid id="admins_select"} type="checkbox" value="-1" /></th>
                  <th><a href="{$active}{build_query sort=name}">{$lang_name}</a></th>
                  <th width="30%"><a href="{$active}{build_query sort=srv_groups}">{$lang_server_admin_group}</a></th>
                  <th width="30%"><a href="{$active}{build_query sort=web_group}">{$lang_web_admin_group}</a></th>
                </tr>
                {foreach from=$admins item=admin key=admin_id}
                <tr class="opener tbl_out">
                  <td class="listtable_1 icon"><input name="admins[]" type="checkbox" value="{$admin_id}" /></td>
                  <td class="admin-row" style="padding: 3px;">{$admin.name} (<a href="banlist.php?search={$admin_id}&amp;type=admin" title="Show bans">{$admin.ban_count} bans</a> | <a href="banlist.php?search={$admin_id}&amp;type=nodemo" title="Show bans without demo">{$admin.nodemo_count} w.d.</a>)</td>
                  <td class="admin-row" style="padding: 3px;">{', '|implode:$admin.srv_groups|default:$lang_none}</td>
                  <td class="admin-row" style="padding: 3px;">{$admin.web_group|default:$lang_none}</td>
                </tr>
                <tr>
                  <td colspan="4">
                    <div class="opener" align="center" border="1">
                      <table width="100%" cellspacing="0" cellpadding="3" bgcolor="#eaebeb">
                        <tr>
                          <td width="35%" class="front-module-line"><strong>{$lang_server_permissions}</strong></td>
                          <td width="35%" class="front-module-line"><strong>{$lang_web_permissions}</strong></td>
                          <td width="30%" valign="top" class="front-module-line"><strong>{$lang_action}</strong></td>
                        </tr>
                        <tr>
                          <td class="permissions" valign="top">
                            {if empty($admin.srv_flags)}
                            <em>{$lang_none}</em>
                            {else}
                            <ul>
                              {if $admin.permission_reservation}
                              <li>{$lang_reservation_desc}</li>
                              {/if}
                              {if $admin.permission_generic}
                              <li>{$lang_generic_desc}</li>
                              {/if}
                              {if $admin.permission_kick}
                              <li>{$lang_kick_desc}</li>
                              {/if}
                              {if $admin.permission_ban}
                              <li>{$lang_ban_desc}</li>
                              {/if}
                              {if $admin.permission_unban}
                              <li>{$lang_unban_desc}</li>
                              {/if}
                              {if $admin.permission_slay}
                              <li>{$lang_slay_desc}</li>
                              {/if}
                              {if $admin.permission_changemap}
                              <li>{$lang_changemap_desc}</li>
                              {/if}
                              {if $admin.permission_cvar}
                              <li>{$lang_cvar_desc}</li>
                              {/if}
                              {if $admin.permission_config}
                              <li>{$lang_config_desc}</li>
                              {/if}
                              {if $admin.permission_chat}
                              <li>{$lang_chat_desc}</li>
                              {/if}
                              {if $admin.permission_vote}
                              <li>{$lang_vote_desc}</li>
                              {/if}
                              {if $admin.permission_password}
                              <li>{$lang_password_desc}</li>
                              {/if}
                              {if $admin.permission_rcon}
                              <li>{$lang_rcon_desc}</li>
                              {/if}
                              {if $admin.permission_cheats}
                              <li>{$lang_cheats_desc}</li>
                              {/if}
                              {if $admin.permission_custom1}
                              <li>{$lang_custom1_desc}</li>
                              {/if}
                              {if $admin.permission_custom2}
                              <li>{$lang_custom2_desc}</li>
                              {/if}
                              {if $admin.permission_custom3}
                              <li>{$lang_custom3_desc}</li>
                              {/if}
                              {if $admin.permission_custom4}
                              <li>{$lang_custom4_desc}</li>
                              {/if}
                              {if $admin.permission_custom5}
                              <li>{$lang_custom5_desc}</li>
                              {/if}
                              {if $admin.permission_custom6}
                              <li>{$lang_custom6_desc}</li>
                              {/if}
                            </ul>
                            {/if}
                          </td>
                          <td class="permissions" valign="top">
                            {if empty($admin.web_flags)}
                            <em>{$lang_none}</em>
                            {else}
                            <ul>
                              {if $admin.permission_list_admins}
                              <li>{$lang_list_admins}</li>
                              {/if}
                              {if $admin.permission_add_admins}
                              <li>{$lang_add_admins}</li>
                              {/if}
                              {if $admin.permission_edit_admins}
                              <li>{$lang_edit_admins}</li>
                              {/if}
                              {if $admin.permission_delete_admins}
                              <li>{$lang_delete_admins}</li>
                              {/if}
                              {if $admin.permission_import_admins}
                              <li>{$lang_import_admins}</li>
                              {/if}
                              {if $admin.permission_list_groups}
                              <li>{$lang_list_groups}</li>
                              {/if}
                              {if $admin.permission_add_groups}
                              <li>{$lang_add_groups}</li>
                              {/if}
                              {if $admin.permission_edit_groups}
                              <li>{$lang_edit_groups}</li>
                              {/if}
                              {if $admin.permission_delete_groups}
                              <li>{$lang_delete_groups}</li>
                              {/if}
                              {if $admin.permission_import_groups}
                              <li>{$lang_import_groups}</li>
                              {/if}
                              {if $admin.permission_list_mods}
                              <li>{$lang_list_mods}</li>
                              {/if}
                              {if $admin.permission_add_mods}
                              <li>{$lang_add_mods}</li>
                              {/if}
                              {if $admin.permission_edit_mods}
                              <li>{$lang_edit_mods}</li>
                              {/if}
                              {if $admin.permission_delete_mods}
                              <li>{$lang_delete_mods}</li>
                              {/if}
                              {if $admin.permission_list_servers}
                              <li>{$lang_list_servers}</li>
                              {/if}
                              {if $admin.permission_add_servers}
                              <li>{$lang_add_servers}</li>
                              {/if}
                              {if $admin.permission_edit_servers}
                              <li>{$lang_edit_servers}</li>
                              {/if}
                              {if $admin.permission_delete_servers}
                              <li>{$lang_delete_servers}</li>
                              {/if}
                              {if $admin.permission_add_bans}
                              <li>{$lang_add_bans}</li>
                              {/if}
                              {if $admin.permission_edit_all_bans}
                              <li>{$lang_edit_all_bans}</li>
                              {elseif $permission_edit_group_bans}
                              <li>{$lang_edit_group_bans}</li>
                              {elseif $permission_edit_own_bans}
                              <li>{$lang_edit_own_bans}</li>
                              {/if}
                              {if $admin.permission_unban_all_bans}
                              <li>{$lang_unban_all_bans}</li>
                              {elseif $permission_unban_group_bans}
                              <li>{$lang_unban_group_bans}</li>
                              {elseif $permission_unban_own_bans}
                              <li>{$lang_unban_own_bans}</li>
                              {/if}
                              {if $admin.permission_delete_bans}
                              <li>{$lang_delete_bans}</li>
                              {/if}
                              {if $admin.permission_import_bans}
                              <li>{$lang_import_bans}</li>
                              {/if}
                              {if $admin.permission_ban_protests}
                              <li>{$lang_ban_protests}</li>
                              {/if}
                              {if $admin.permission_ban_submissions}
                              <li>{$lang_ban_submissions}</li>
                              {/if}
                              {if $admin.permission_notify_prot}
                              <li>{$lang_notify_protests}</li>
                              {/if}
                              {if $admin.permission_notify_sub}
                              <li>{$lang_notify_submissions}</li>
                              {/if}
                              {if $admin.permission_settings}
                              <li>{$lang_settings}</li>
                              {/if}
                            </ul>
                            {/if}
                          </td>
                          <td width="30%" valign="top">
                            <ul class="ban-edit">
                              {if $permission_edit_admins}
                              <li><a href="admin_admins_editdetails.php?id={$admin_id}"><img alt="{$lang_edit_details|ucwords}" class="icon" src="images/details.png" title="{$lang_edit_details|ucwords}" /> {$lang_edit_details|ucwords}</a></li>
                              <li><a href="admin_admins_editgroups.php?id={$admin_id}"><img alt="{$lang_edit_groups|ucwords}" class="icon" src="images/groups.png" title="{$lang_edit_groups|ucwords}" /> {$lang_edit_groups|ucwords}</a></li>
                              {/if}
                              {if $permission_delete_admins}
                              <li><a href="#" onclick="DeleteAdmin({$admin_id}, '{$admin.name}');"><img alt="{$lang_delete_admin|ucwords}" class="icon" src="images/delete.png" title="{$lang_delete_admin|ucwords}" /> {$lang_delete_admin|ucwords}</a></li>
                              {/if}
                            </ul>
                            <div class="front-module-line" style="padding: 3px;">{$lang_immunity_level}: <strong>{$admin.srv_immunity}</strong></div>
                            <div class="front-module-line" style="padding: 3px;">{$lang_last_visit}: <strong><small>{if empty($admin.lastvisit)}{$lang_unknown}{else}{$admin.lastvisit|date_format:$date_format}{/if}</small></strong></div>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
                {/foreach}
              </table>
              <select id="action_select">
                <option value="0">{$lang_action}</option>
                <option value="delete">{$lang_delete}</option>
                <optgroup label="Add to server admin group">
                  {foreach from=$server_admin_groups item=group key=group_id}
                  <option value="{$group_id}">{$group.name}</option>
                  {/foreach}
                </optgroup>
                <optgroup label="Set web admin group">
                  {foreach from=$web_admin_groups item=group key=group_id}
                  <option value="{$group_id}">{$group.name}</option>
                  {/foreach}
                </optgroup>
              </select>
            </div>
            {/if}
            {if $permission_add_admins}
            <form action="{$active}" id="pane-add" method="post">
              <fieldset>
                <h3>{$lang_details}</h3>
                <p>{$lang_help_desc}</p>
                <div>
                  <label for="name">{help_icon title="$lang_username" desc="This is the username the admin will use to login to their admin panel. Also this will identify the admin on any bans they make."}{$lang_username}</label>
                  <input class="submit-fields" {nid id="name"} />
                  <span class="mandatory">*</span>
                </div>
                <div class="badentry" id="name.msg"></div>
                <div>
                  <label for="auth">{help_icon title="$lang_type" desc="This is the admin's authentication type."}{$lang_type}</label>
                  <select class="submit-fields" {nid id="auth"}>
                    <option value="{$smarty.const.STEAM_AUTH_TYPE}">Steam ID</option>
                    <option value="{$smarty.const.IP_AUTH_TYPE}">{$lang_ip_address}</option>
                    <option value="{$smarty.const.NAME_AUTH_TYPE}">{$lang_name}</option>
                  </select>
                  <span class="mandatory">*</span>
                </div>
                <div class="badentry" id="type.msg">Invalid type supplied.</div>
                <div>
                  <label for="identity">{help_icon title="Identity" desc="This is the admin's identity. This must be set so that admins can use their admin rights ingame."}Identity</label>
                  <input class="submit-fields" {nid id="identity"} value="STEAM_" />
                  <span class="mandatory">*</span>
                </div>
                <div class="badentry" id="identity.msg"></div>
                <div>
                  <label for="email">{help_icon title="$lang_email_address" desc="Set the admin's e-mail address. This will be used for sending out any automated messages from the system, and for use when you forget your password."}{$lang_email_address}</label>
                  <input class="submit-fields" {nid id="email"} />
                </div>
                <div class="badentry" id="email.msg"></div>
                <div>
                  <label for="password">{help_icon title="$lang_password" desc="The password the admin will need to access the admin panel."}{$lang_password}</label>
                  <input class="submit-fields" {nid id="password"} type="password" />
                </div>
                <div class="badentry" id="password.msg"></div>
                <div>
                  <label for="password_confirm">{help_icon title="$lang_confirm_password" desc="$lang_confirm_password_desc"}{$lang_confirm_password}</label>
                  <input class="submit-fields" {nid id="password_confirm"} type="password" />
                </div>
                <div class="badentry" id="password_confirm.msg"></div>
                <div>
                  <label for="srv_password">{help_icon title="Server Admin Password" desc="If this box is checked, you will need to specify this password in the game server before you can use your admin rights."}Use as admin password?</label>
                  <input {nid id="srv_password"} type="checkbox" />
                </div>
                <div>
                  <label for="activation">{help_icon title="Activation E-mail" desc="If this box is checked, an e-mail will be sent to the admin to activate his/her account."}Send activation e-mail?</label>
                  <input {nid id="activation"} type="checkbox" />
                </div>
                <h3>{$lang_groups}</h3>
                <div>
                  <label for="srv_group">{help_icon title="$lang_server_admin_group" desc="<strong>Custom Permisions:</strong><br />Select this to choose custom permissions for this admin.<br /><br /><strong>New Group:</strong><br />Select this to choose custom permissions and then save the permissions as a new group.<br /><br /><strong>Groups:</strong><br />Select a pre-made group to add the admin to."}{$lang_server_admin_group}</label>
                  <select class="submit-fields" {nid id="srv_group"}>
                    <option value="0">{$lang_none}</option>
                    <optgroup label="{$lang_groups}">
                      {foreach from=$server_admin_groups item=group key=group_id}
                      <option value="{$group_id}">{$group.name|escape}</option>
                      {/foreach}
                    </optgroup>
                  </select>
                </div>
                <div class="badentry" id="srv_groups.msg"></div>
                <div>
                  <label for="web_group">{help_icon title="$lang_web_admin_group" desc="<strong>Custom Permisions:</strong><br />Select this to choose custom permissions for this admin.<br /><br /><strong>New Group:</strong><br />Select this to choose cusrom permissions and then save the permissions as a new group.<br /><br /><strong>Groups:</strong><br />Select a pre-made group to add the admin to."}{$lang_web_admin_group}</label>
                  <select class="submit-fields" {nid id="web_group"}>
                    <option value="0">{$lang_none}</option>
                    <optgroup label="{$lang_groups}">
                      {foreach from=$web_admin_groups item=group key=group_id}
                      <option value="{$group_id}">{$group.name|escape}</option>
                      {/foreach}
                    </optgroup>
                  </select>
                </div>
                <div id="web_group.msg" class="badentry"></div>
                <div class="center">
                  <input name="action" type="hidden" value="add" />
                  <input class="btn ok" type="submit" value="{$lang_save}" />
                  <input class="back btn cancel" type="button" value="{$lang_back}" />
                </div>
              </fieldset>
            </form>
            {/if}
            {if $permission_import_admins}
            <form action="{$active}" enctype="multipart/form-data" id="pane-import" method="post">
              <fieldset>
                <h3>{$lang_import_admins|ucwords}</h3>
                <p>{$lang_help_desc}</p>
                <label for="file">{help_icon title="$lang_file" desc="Select the admins.cfg file to upload and add admins."}{$lang_file}</label>
                <input class="submit-fields" {nid id="file"} type="file" />
                <div id="file.msg" class="badentry"></div>
                <div class="center">
                  <input name="action" type="hidden" value="import" />
                  <input class="btn ok" type="submit" value="{$lang_save}" />
                  <input class="back btn cancel" type="button" value="{$lang_back}" />
                </div>
              </fieldset>
            </form>
            {/if}
            {if $permission_list_actions}
            <div id="pane-actions">
              <h3>{$lang_actions_log}{if $permission_clear_actions} ( <a id="clear_actions" href="#">Clear Actions</a> ){/if}</h3>
              <p>Click on a row to see more details about the event.</p>
              <div id="banlist-nav">{$page_numbers}</div>
              <br /><br />
              <table width="100%" cellspacing="0" cellpadding="0" align="center" class="listtable">
                <tr>
                  <th width="5%" align="center">{$lang_admin}</th>
                  <th width="28%" align="center">Message</th>
                  <th width="28%" align="center">{$lang_name}</th>
                  <th>{$lang_date}/{$lang_time}</td>
                </tr>
                {foreach from=$actions item=action}
                <tr class="opener tbl_out">
                  <td class="listtable_1">{$action.admin_name}</td>
                  <td class="listtable_1">{$action.message|escape}</td>
                  <td class="listtable_1">
                    {if empty($action.name)}
                    <em class="not_applicable">{$lang_not_applicable}.</em>
                    {else}
                    {$action.name}
                    {/if}
                  </td>
                  <td class="listtable_1">{$action.time|date_format:$date_format}</td>
                </tr>
                <tr>
                  <td colspan="7" align="center">
                    <div class="opener">
                      <table width="80%" cellspacing="0" cellpadding="0" class="listtable">
                        <tr>
                          <th colspan="3">{$lang_details}</th>
                        </tr>
                        <tr>
                          <td class="listtable_1" width="20%">MOD</td>
                          <td class="listtable_1">{$action.mod_name}</td>
                        </tr>
                        <tr>
                          <td class="listtable_1">{$lang_server}</td>
                          <td class="listtable_1">{$action.server_ip}:{$action.server_port}</td>
                        </tr>
                        <tr>
                          <td class="listtable_1">Steam ID</td>
                          <td class="listtable_1">
                            {if empty($action.steam)}
                            <em class="not_applicable">{$lang_not_applicable}.</em>
                            {else}
                            <a href="http://steamcommunity.com/profiles/{$action.community_id}">{$action.steam}</a>
                            {/if}
                          </td>
                        </tr>
                        <tr>
                          <td class="listtable_1">{$lang_ip_address}</td>
                          <td class="listtable_1">
                            {if empty($action.ip)}
                            <em class="not_applicable">{$lang_not_applicable}.</em>
                            {else}
                            {$action.ip}
                            {/if}
                          </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
                {/foreach}
              </table>
            </div>
            {/if}
          </div>