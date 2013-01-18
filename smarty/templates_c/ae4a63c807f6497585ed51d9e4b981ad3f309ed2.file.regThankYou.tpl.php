<?php /* Smarty version Smarty-3.1.12, created on 2013-01-17 20:00:27
         compiled from "C:\Users\overlord\Dropbox\Work\webshark-test\templates\regThankYou.tpl" */
?>
<?php /*%%SmartyHeaderCode:2464650f84a4bc186c2-17651374%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        'ae4a63c807f6497585ed51d9e4b981ad3f309ed2' =>
        array(
            0 => 'C:\\Users\\overlord\\Dropbox\\Work\\webshark-test\\templates\\regThankYou.tpl',
            1 => 1358434623,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '2464650f84a4bc186c2-17651374',
    'function' =>
    array(),
    'has_nocache_code' => false,
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f84a4bca2af9_56262881',
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f84a4bca2af9_56262881')) {
    function content_50f84a4bca2af9_56262881($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>


    <header>
        <h1>oSocial</h1>
    </header>
    <h2>Köszönjük, a regisztációt!</h2>
    <p>A felhasználód jóváhagyása után <a href="/">itt</a> tudsz bejelentkezni!</p>

    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>