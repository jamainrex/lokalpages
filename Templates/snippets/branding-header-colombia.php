{ifset $_GET['dir-register-status']}
<div id="ait-dir-register-notifications" class="{if $_GET['dir-register-status'] == '3'}error{else}info{/if}">
    {if $_GET['dir-register-status'] == '3'}
    <div class="message defaultContentWidth">
    {__ "You canceled payment. Your account was registered but without option to add items. Upgrade your account in admin to add items."}
    <div class="close"></div>
    </div>
    {/if}
</div>
{/ifset}

{ifset $registerErrors}
<div id="ait-dir-register-notifications" class="error">
    <div class="message defaultContentWidth">
    {!$registerErrors}
    <div class="close"></div>
    </div>
</div>
{/ifset}

{ifset $registerMessages}
<div id="ait-dir-register-notifications" class="info">
    <div class="message defaultContentWidth">
    {!$registerMessages}
    <div class="close"></div>
    </div>
</div>
{/ifset}

{ifset $themeOptions->advertising->showBox1}
<div id="advertising-box-1" class="advertising-box">
    <div class="defaultContentWidth clearfix">
        <div>{!$themeOptions->advertising->box1Content}</div>
     </div>
</div>
{/ifset}

<header id="globalheader" role="banner" class="clearfix">
    <nav id="globalnavheader" role="navigation">
        <div class="defaultContentWidth clearfix">
            <div id="lpc-logo">
                {if is_admin()}
                    {if !empty($themeOptions->general->easyadmin_logo_img)}
                    <a class="trademark" href="{$homeUrl}">
                        <img height="50" src="{linkTo $themeOptions->general->easyadmin_logo_img}" alt="logo" />
                    </a>
                    {else}
                    <a href="{$homeUrl}">
                        <span>{$themeOptions->general->logo_text}</span>
                    </a>
                    {/if}
                {else}
                    {if !empty($themeOptions->general->logo_img)}
                    <a class="trademark" href="{$homeUrl}">
                        <img height="50" src="{linkTo $themeOptions->general->logo_img}" alt="logo" />
                    </a>
                    {else}
                    <a href="{$homeUrl}">
                        <span>{$themeOptions->general->logo_text}</span>
                    </a>
                    {/if}
                {/if}
            </div>
                {if !is_admin()}
                    <h3 class="assistive-text">{__ 'Main menu'}</h3>
                    <div id="mobile_only">
                        <span id="mobile_menu_bar">Main Menu</span>
                        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    {menu 'theme_location' => 'primary-menu', 'fallback_cb' => 'default_menu', 'container' => 'nav', 'container_class' => 'mainmenu', 'menu_class' => 'menu' }
                {else}
                    
                    <!-- EASY ADMIN MENU -->
                    {var $screen = get_current_screen()}
                    <a href="{!admin_url('edit.php?post_type=ait-dir-item')}" class="items button{if (($screen->base == 'edit' && $screen->post_type == 'ait-dir-item') || ($screen->base == 'post' && $screen->post_type == 'ait-dir-item'))} button-primary{/if}">
                        {__ 'My Items'}
                    </a>
                    <a href="{!admin_url('edit.php?post_type=ait-rating')}" class="ratings button{if ($screen->base == 'edit' && $screen->post_type == 'ait-rating')} button-primary{/if}">
                        {__ 'Ratings'}
                    </a>
                    <a href="{!admin_url('profile.php')}" class="account button{if ($screen->base == 'profile')} button-primary{/if}">
                        {__ 'Account'}
                    </a>
                    <a href="{home_url()}" class="view-site button">
                        {__ 'View site'}
                    </a>

                    {if is_user_logged_in()}
                    <a href="{!wp_logout_url(home_url())}" class="menu-login menu-logout">{__ "Logout"}</a>
                    {/if}

                {/if}
                
            <ul class="lpc-sc social-icons">
                {if is_user_logged_in()} 
                    <li class="left"><a href="<?php echo admin_url('edit.php?post_type=ait-dir-item') ?>" title="Dashboard" class="btn btn-lp-blurb" style="padding: 12px;"><?php echo __('Dashboard','ait') ?></a>  </li>
                {else}
                    <li class="left"><a href="<?php echo wp_login_url(); ?>" title="Login" class="btn btn-lp-blurb" style="padding: 12px;"><?php echo __('Login','ait') ?></a>  </li> 
                {/if}
            </ul>
        </div> 
    </nav><!-- #accs -->    
    
</header><!-- #branding -->