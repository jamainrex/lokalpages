<div id="site-generator" class="clearfix">
	<div class="defaultContentWidth">
		<div id="footer-text">
			{!$themeOptions->general->footer_text}
		</div>
		{if (!is_admin())}
		{menu 'theme_location' => 'footer-menu', 'fallback_cb' => 'default_footer_menu', 'container' => 'nav', 'container_class' => 'footer-menu', 'menu_class' => 'menu', 'depth' => 1 }
		{/if}
        
        {ifset $themeOptions->socialIcons->displayIcons}
            {ifset $themeOptions->socialIcons->icons}
            <ul class="social-icons">
                {foreach $themeOptions->socialIcons->icons as $icon}
                <li class="left"><a href="{if !empty($icon->link)}{$icon->link}{else}#{/if}"><img src="{$icon->iconUrl}" height="24" width="24" alt="{$icon->title}" title="{$icon->title}"></a></li>
                {/foreach}
            </ul>
            {/ifset}
        {/ifset}
	</div>
</div>