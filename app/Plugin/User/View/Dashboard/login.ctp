<div id="login_container">
    <div id="login">
        <form id="login_form" action="<?php $html->url('/dashboard/login'); ?>" id="login_form" method="post">
            <table width="230"  border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="36" colspan="2" align="left" valign="middle"><input type="text" name="data[User][username]" id="user" value="<?php __t('Username'); ?>" onFocus="if (this.value == '<?php __t("Username"); ?>') {this.value = ''}" onBlur="if (this.value == '') {this.value = '<?php __t("Username"); ?>'}" class="text"/>
                </tr>
                <tr>
                    <td width="210" align="left" valign="middle"><input type="password" name="data[User][password]" id="pass" class="text"/></td>
                    <td width="190" align="left" valign="middle"><a href="" onClick="$('login_form').submit();" class="login-btn" id="login-btn"></a></td>
                </tr>
                <tr>
                    <td colspan="2" width="230" align="left" valign="middle">
                         <input name="data[User][remember]" type="checkbox" id="remember" value="1" /> <?php __t('Remember me'); ?>
                    </td>
                </tr>

            </table>
        </form>
    </div>
</div>