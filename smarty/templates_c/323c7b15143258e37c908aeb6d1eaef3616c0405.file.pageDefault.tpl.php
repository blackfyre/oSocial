<?php /* Smarty version Smarty-3.1.12, created on 2013-01-17 18:13:28
         compiled from "C:\Users\overlord\Dropbox\Work\webshark-test\templates\pageDefault.tpl" */
?>
<?php /*%%SmartyHeaderCode:686750f479701c3aa0-83452117%%*/
if (!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array(
    'file_dependency' =>
    array(
        '323c7b15143258e37c908aeb6d1eaef3616c0405' =>
        array(
            0 => 'C:\\Users\\overlord\\Dropbox\\Work\\webshark-test\\templates\\pageDefault.tpl',
            1 => 1358442801,
            2 => 'file',
        ),
    ),
    'nocache_hash' => '686750f479701c3aa0-83452117',
    'function' =>
    array(),
    'version' => 'Smarty-3.1.12',
    'unifunc' => 'content_50f479704925b3_42372347',
    'variables' =>
    array(
        'sideMenu' => 0,
        'i' => 0,
        'siteLang' => 0,
        'content' => 0,
    ),
    'has_nocache_code' => false,
), false); /*/%%SmartyHeaderCode%%*/
?>
<?php if ($_valid && !is_callable('content_50f479704925b3_42372347')) {
    function content_50f479704925b3_42372347($_smarty_tpl)
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

        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>


    </div>
    <?php echo $_smarty_tpl->getSubTemplate('sideRight.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>


    <?php echo $_smarty_tpl->getSubTemplate('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0); ?>
    <?php }
} ?>