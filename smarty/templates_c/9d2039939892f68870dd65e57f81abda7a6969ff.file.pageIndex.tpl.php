<?php /* Smarty version Smarty-3.1.12, created on 2013-01-17 17:46:22
         compiled from "C:\Users\overlord\Dropbox\Work\webshark-test\templates\pageIndex.tpl" */
?>
<?php /*%%SmartyHeaderCode:767050f7012e1e0126-75841386%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        '9d2039939892f68870dd65e57f81abda7a6969ff' =>
        array(
            0 => 'C:\\Users\\overlord\\Dropbox\\Work\\webshark-test\\templates\\pageIndex.tpl',
            1 => 1358424144,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '767050f7012e1e0126-75841386',
    'function' =>
    array(),
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f7012e69c623_60038124',
    'variables' =>
    array(
        'siteTitle' => 0,
        'loginForm' => 0,
        'registerForm' => 0,
    ),
    'has_nocache_code' => false,
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f7012e69c623_60038124')) {
    function content_50f7012e69c623_60038124($_smarty_tpl)
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