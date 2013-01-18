{include file='header.tpl'}

<header>
    <h1>oSocial</h1>
</header>

<nav class="sideLeft contentBox">
    <ul class="sideNav">
    {foreach from=$sideMenu item=i}
        <a href="{$i.href}" title="{$i.title}" target="{$i.target}" hreflang="{$siteLang}">
            <li>{$i.text}</li>
        </a>
    {/foreach}
    </ul>
</nav>
<div class="mainContent contentBox">

{$messages}
{$content}

</div>
{include file='sideRight.tpl'}

{include file='footer.tpl'}