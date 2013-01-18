<?php /* Smarty version Smarty-3.1.12, created on 2013-01-18 15:12:18
         compiled from "D:\Meki\Dropbox\Work\webshark-test\templates\pageIndex.tpl" */
?>
<?php /*%%SmartyHeaderCode:1006550f6c1124da761-32869350%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        'a10be762ed489a500f3cf5f7e7f47586754837fb' =>
        array(
            0 => 'D:\\Meki\\Dropbox\\Work\\webshark-test\\templates\\pageIndex.tpl',
            1 => 1358512263,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '1006550f6c1124da761-32869350',
    'function' =>
    array(),
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f6c11260b607_73184223',
    'variables' =>
    array(
        'siteTitle' => 0,
        'loginForm' => 0,
        'registerForm' => 0,
    ),
    'has_nocache_code' => false,
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f6c11260b607_73184223')) {
    function content_50f6c11260b607_73184223($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>

    <header>
        <h1><?php echo $_smarty_tpl->tpl_vars['siteTitle']->value;?>
        </h1>
    </header>

    <div class="mainContent contentBox">

        Reklám és/vagy egyéb dolgok helye

    </div>

    <div class="sideRight contentBox">
        <div>
            <?php echo $_smarty_tpl->tpl_vars['loginForm']->value;?>

        </div>

        <div>
            <?php echo $_smarty_tpl->tpl_vars['registerForm']->value;?>

        </div>
    </div>


    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>