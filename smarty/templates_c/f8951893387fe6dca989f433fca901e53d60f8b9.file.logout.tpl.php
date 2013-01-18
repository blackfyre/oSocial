<?php /* Smarty version Smarty-3.1.12, created on 2013-01-18 15:12:15
         compiled from "D:\Meki\Dropbox\Work\webshark-test\templates\logout.tpl" */
?>
<?php /*%%SmartyHeaderCode:1240250f803c6af26c4-48970544%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        'f8951893387fe6dca989f433fca901e53d60f8b9' =>
        array(
            0 => 'D:\\Meki\\Dropbox\\Work\\webshark-test\\templates\\logout.tpl',
            1 => 1358511799,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '1240250f803c6af26c4-48970544',
    'function' =>
    array(),
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f803c6b809e1_70972457',
    'has_nocache_code' => false,
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f803c6b809e1_70972457')) {
    function content_50f803c6b809e1_70972457($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>

    <header>
        <h1>oSocial</h1>
    </header>
    <p>Köszönjük, hogy itt jártál!</p>
    <p>Elfejeltettél megnézni valamit? <a href="/">Jelentkezz be újra</a></p>

    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>