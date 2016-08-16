{extends $layout}

{block content}

<article id="post-{$post->id}" class="{$post->htmlClasses}">

	<header class="entry-header">

		<h1 class="entry-title">
			<a href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a>
			{if $rating}
			<span class="rating">
				{for $i = 1; $i <= $rating['max']; $i++}
					<span class="star{if $i <= $rating['val']} active{/if}"></span>
				{/for}
			</span>
			{/if}
		</h1>
		
		<div class="category-breadcrumb clearfix">
			<span class="here">{__ 'You are here'}</span>
			<span class="home"><a href="{!$homeUrl}">{__ 'Home'}</a>&nbsp;&nbsp;&gt;</span>
			{foreach $ancestors as $anc}
				{first}<span class="ancestors">{/first}
				<a href="{!$anc->link}">{!$anc->name}</a>&nbsp;&nbsp;&gt;
				{last}</span>{/last}
			{/foreach}
			{ifset $term}<span class="name"><a href="{!$term->link}">{!$term->name}</a></span>{/ifset}
			<span class="title"> >&nbsp;&nbsp;{$post->title}</span>
		</div>

	</header>
{if (!empty($isDirection) && empty($isServGPSMapDisable) ) } 
        {include single-ait-dir-item-direction.php}
{else}


	<div class="entry-content clearfix">
		
		<div class="item-image" style="width:100%">
			{if $post->thumbnailSrc}
			<img src="<?php echo $post->thumbnailSrc; ?>" style="max-width: 95%" alt="{__ 'Item image'}">
			{/if}
			
		</div>
        <div style="width:100%; text-align: center;">
            {if (empty($isServGPSMapDisable)) }
                <a id="get-direction-button" class="get-direction button" href="{$post->permalink}?direction=yes">{_ "How to get there"}</a>   
            {/if}

			{if (isset($themeOptions->directory->enableClaimListing)) && (!$hasAlreadyOwner)}
                <a id="claim-listing-button" class="claim-listing-button" href="#claim-listing-form-popup">{_ "Own this business?"}</a>
            {/if}
            {if (isset($themeOptions->directory->enableClaimListing)) && ($hasAlreadyOwner)}
                <a id="suggest-edit-button" class="claim-listing-button" href="{$post->permalink}?suggestedit=yes">{_ "Edit this business?"}</a>
            {/if}
        </div>
		
        <hr>
        
		{if isset($options['emailContactOwner']) && (!empty($options['email']))}
		<!-- contact owner form -->
		<div id="contact-owner-form-popup" style="display:none;">
                <div id="contact-owner-form" data-email="{$options['email']}">
				
				<h3>{_ "Contact Owner"}</h3>

				<div class="input name">
					<input type="text" id="cowner-name" name="cowner-name" value="" placeholder="{_ 'Your name'}">
				</div>
				<div class="input email">
					<input type="text" id="cowner-email" name="cowner-email" value="" placeholder="{_ 'Your email'}">
				</div>
				<div class="input subject">
					<input type="text" id="cowner-subject" name="cowner-subject" value="" placeholder="{_ 'Subject'}">
				</div>
                <div class="input agent">
                    <input type="text" id="agent-name" name="agent-name" value="" placeholder="{_ 'Agent name'}">
                </div>
				<div class="input message">
					<textarea id="cowner-message" name="cowner-message" cols="30" rows="4" placeholder="{_ 'Your message'}"></textarea>
				</div>
				<button class="contact-owner-send">{_ "Send message"}</button>
				
				<div class="messages">
					<div class="success" style="display: none;">{_ "Your message has been successfully sent"}</div>
					<div class="error validator" style="display: none;">{_ "Please fill out email, subject and message"}</div>
					<div class="error server" style="display: none;"></div>
				</div>

			</div>
		</div>
		{/if}
        
        <!-- Ask Business Service Button -->
        <a id="ask-business-service-button" class="ask-business-service-button" style="display: none;" href="#ask-business-service-form-popup">This is Hidden</a>
        
        <!-- Ask Business Service form -->
        <div id="ask-business-service-form-popup" style="display:none;">
            
            {if (!empty($options['email']))  }
                <div id="ask-business-service-form" data-email="{$options['email']}">
            {/if}
            
            {if (empty($options['email']))  }
                <div id="ask-business-service-form" data-email="kitprimor@gmail.com">
            {/if}
                
                <h3>Ask <a href="{$post->permalink}" title="{$post->title}">{$post->title}</a> a price of their service or product?</h3>

                <div class="input name">
                    <input type="text" id="abs-name" name="abs-name" value="" placeholder="{_ 'Your name'}">
                </div>
                <div class="input email">
                    <input type="text" id="abs-email" name="abs-email" value="" placeholder="{_ 'Your email'}">
                </div>
                <div class="input phone">
                    <input type="text" id="abs-contact" name="abs-contact" value="" placeholder="{_ 'Your contact'}">
                </div>
                
                <div class="input subject">
                    <input type="text" id="abs-subject" name="abs-subject" value="" placeholder="{_ 'Subject'}">
                </div>
                <div class="input message">
                    <textarea id="abs-message" name="abs-message" cols="30" rows="4" placeholder="{_ 'Your message'}"></textarea>
                </div>
                <input type="hidden" id="abs-busines-name" name="abs-busines-name" value="{$post->title}">
                <button class="ask-business-service-send">{_ "Send message"}</button>
                
                <div class="messages">
                    <div class="success" style="display: none;">{_ "Your message has been successfully sent"}</div>
                    <div class="error validator" style="display: none;">{_ "Please fill out email, subject and message"}</div>
                    <div class="error server" style="display: none;"></div>
                </div>

            </div>
        </div>

		{if (isset($themeOptions->directory->enableClaimListing)) && (!$hasAlreadyOwner)}
		<!-- claim listing form -->
		<div id="claim-listing-form-popup" style="display:none;">
			<div id="claim-listing-form" data-item-id="{$post->id}">

				<h3>{_ "Enter your claim"}</h3>

				<div class="input name">
					<input type="text" id="claim-name" name="claim-name" value="" placeholder="{_ 'Your name'}">
				</div>
				<div class="input email">
					<input type="text" id="claim-email" name="claim-email" value="" placeholder="{_ 'Your email'}">
				</div>
				<div class="input number">
					<input type="text" id="claim-number" name="claim-number" value="" placeholder="{_ 'Your phone number'}">
				</div>
				<div class="input username">
					<input type="text" id="claim-username" name="claim-username" value="" placeholder="{_ 'Username'}">
				</div>
				<div class="input message">
					<textarea id="claim-message" name="claim-message" cols="30" rows="4" placeholder="{_ 'Your claim message'}"></textarea>
				</div>
                <div>
                    <p class="clickedit">By clicking on the "Submit" button below, you certify that you have read and agree to our <a href="/terms" title="Terms of use" target="_blank">terms of use</a> and <a href="/privacy" title="Privacy policy" target="_blank">privacy policy</a>.</p>
                </div>
				<button class="claim-listing-send">{_ "Submit"}</button>
				
				<div class="messages">
					<div class="success" style="display: none;">{_ "Your claim has been submitted, and is pending for approval. Kindly check your e-mail within 24 hours."}</div>
					<div class="error validator" style="display: none;">{_ "Please fill out inputs!"}</div>
					<div class="error server" style="display: none;"></div>
				</div>

			</div>
		</div>
		{/if}
		
		{include single-ait-dir-item-info.php}
        
        <hr>
        {if (!empty($isServShowInformation)) }       
        <div class="lp-listing-description lp-listing-overlay">
            {!$post->content}      
        </div>
        {/if}
        {if (empty($isServShowInformation)) }  
        <div class="lp-listing-description lp-listing-overlay" style="text-align: center;">
            {if (isset($themeOptions->directory->enableClaimListing)) && (!$hasAlreadyOwner)}
                <a id="claim-listing-button" class="claim-listing-button" href="#claim-listing-form-popup">{_ "Claim this business, to show hidden contents."}</a>
            {/if}
        </div>
        {/if}    
        
	</div>
    
    

	{ifset $themeOptions->directory->showShareButtons}
	<div class="item-share">
		<!-- facebook -->
		<div class="social-item fb">
			<iframe src="//www.facebook.com/plugins/like.php?href={$post->permalink}&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21&amp;appId=1455689078032521" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width:150px;" allowTransparency="true"></iframe>
		</div>
		<!-- twitter -->
		<div class="social-item">
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="{$post->permalink}" data-text="{$themeOptions->directory->shareText} {$post->permalink}" data-lang="en">Tweet</a>
			<script>!function(d,s,id){ var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){ js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<!-- google plus -->
		<!-- Place this tag where you want the +1 button to render. -->
		<div class="social-item">
			<div class="g-plusone"></div>
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/plusone.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>

	</div>
	{/ifset}
    
    

{/if}

	<hr>
	
	{if (!empty($options['alternativeContent']))}
	<div class="item-alternative-content">
		{!do_shortcode($options['alternativeContent'])}
	</div>
	{/if}

</article><!-- /#post-{$post->id} -->

{if (empty($isServRevRateDisable)) }
{!getAitRatingElement($post->id)}
{/if}

{include comments-dir.php, closeable => $themeOptions->general->closeComments, defaultState => $themeOptions->general->defaultPosition}

{ifset $themeOptions->advertising->showBox4}
<div id="advertising-box-4" class="advertising-box">
    {!$themeOptions->advertising->box4Content}
</div>
{/ifset}

{if isset($footerWidget) && $footerWidget == 'item'}
                {isActiveSidebar footer-item}
                <hr>
                <div id="footer-item-widget" role="complementary">
                    {dynamicSidebar footer-item}
                </div>
                {/isActiveSidebar}
{/if}

{if (!empty($options['lp_after_listing_output']))}
    <div class="item-alternative-content">
        {!do_shortcode($options['lp_after_listing_output'])}
    </div>
{/if}

{if (!empty($facebookPageId))}
      {include snippets/facebook-messenger.php}
{/if}

{/block}