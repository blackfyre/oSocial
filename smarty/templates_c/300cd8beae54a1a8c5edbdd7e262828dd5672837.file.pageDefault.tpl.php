<?php /* Smarty version Smarty-3.1.12, created on 2013-01-18 13:48:03
         compiled from "D:\Meki\Dropbox\Work\webshark-test\templates\pageDefault.tpl" */
?>
<?php /*%%SmartyHeaderCode:2449750f6bc81bb8128-17805423%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        '300cd8beae54a1a8c5edbdd7e262828dd5672837' =>
        array(
            0 => 'D:\\Meki\\Dropbox\\Work\\webshark-test\\templates\\pageDefault.tpl',
            1 => 1358511800,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '2449750f6bc81bb8128-17805423',
    'function' =>
    array(),
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f6bc81c5ee18_24193302',
    'variables' =>
    array(
        'sideMenu' => 0,
        'i' => 0,
        'siteLang' => 0,
        'messages' => 0,
        'content' => 0,
    ),
    'has_nocache_code' => false,
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f6bc81c5ee18_24193302')) {
    function content_50f6bc81c5ee18_24193302($_smarty_tpl)
    { ?><?php echo $_smarty_tpl->getSubTemplate('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>


    <header>
        <h1>oSocial</h1>
    </header>

    <nav class="sideLeft contentBox">
        <ul class="sideNav">
            <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
            $_from = $_smarty_tpl->tpl_vars['sideMenu']->value; if (!is_array($_from) && !is_object($_from)) {
            settype($_from, 'array');
        }
            foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value) {
                $_smarty_tpl->tpl_vars['i']->_loop = true;
                ?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['i']->value['href'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['i']->value['title'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['i']->value['target'];?>
" hreflang="<?php echo $_smarty_tpl->tpl_vars['siteLang']->value;?>
">
                    <li><?php echo $_smarty_tpl->tpl_vars['i']->value['text'];?>
                    </li>
                </a>
                <?php } ?>
        </ul>
    </nav>
    <div class="mainContent contentBox">

        <?php echo $_smarty_tpl->tpl_vars['messages']->value;?>

        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>


    </div>
    <?php echo $_smarty_tpl->getSubTemplate('sideRight.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>


    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>