<?php /* Smarty version Smarty-3.1.12, created on 2013-01-17 16:00:16
         compiled from "D:\Meki\Dropbox\Work\webshark-test\templates\regThankYou.tpl" */
?>
<?php /*%%SmartyHeaderCode:151250f8120086fc47-21292335%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        '07883f2fec624db38ab3ec2271a0fdbb2ad9efb0' =>
        array(
            0 => 'D:\\Meki\\Dropbox\\Work\\webshark-test\\templates\\regThankYou.tpl',
            1 => 1358434623,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '151250f8120086fc47-21292335',
    'function' =>
    array(),
    'has_nocache_code' => false,
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f81200978f76_95665874',
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f81200978f76_95665874')) {
    function content_50f81200978f76_95665874($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>


    <header>
        <h1>oSocial</h1>
    </header>
    <h2>Köszönjük, a regisztációt!</h2>
    <p>A felhasználód jóváhagyása után <a href="/">itt</a> tudsz bejelentkezni!</p>

    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>