<?php /* Smarty version Smarty-3.1.12, created on 2013-01-17 18:04:45
         compiled from "C:\Users\overlord\Dropbox\Work\webshark-test\templates\logout.tpl" */
?>
<?php /*%%SmartyHeaderCode:1686050f82f2db69465-66989163%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        'ff2a6fd72c0f7d65d46e8265f35d98e58b1ca830' =>
        array(
            0 => 'C:\\Users\\overlord\\Dropbox\\Work\\webshark-test\\templates\\logout.tpl',
            1 => 1358431300,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '1686050f82f2db69465-66989163',
    'function' =>
    array(),
    'has_nocache_code' => false,
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f82f2dbf0424_70789466',
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f82f2dbf0424_70789466')) {
    function content_50f82f2dbf0424_70789466($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>

    <header>
        <h1>oSocial</h1>
    </header>
    <p>Köszönjük, hogy itt jártál!</p>
    <p>Elfejeltettél megnézni valamit? <a href="/">Jelentkezz be újra</a></p>

    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>