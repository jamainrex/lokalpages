{extends $layout}

{block content}

<article id="post-item-category">

	<header class="entry-header">
	
		<h1 class="entry-title">
			<span>{!$term->name}</span>
		</h1>

		<div class="category-breadcrumb clearfix">
			<span class="here">{__ 'You are here:'}</span>
			<span class="home"><a href="{!$homeUrl}">{__ 'Home'}</a>&nbsp;&nbsp;&gt;</span>
			{foreach $ancestors as $anc}
			{first}<span class="ancestors">{/first}
				<a href="{!$anc->link}">{!$anc->name}</a>&nbsp;&nbsp;&gt;
			{last}</span>{/last}
			{/foreach}
			<span class="name"><a href="{!$term->link}">{!$term->name}</a></span>
		</div>

	</header>

	<div class="entry-content">
		{!$term->description}
	</div>

	<div class="category-subcategories clearfix">
		{foreach $subcategories as $category}
		{first}<ul class="subcategories">{/first}
			<li class="category">
				<div class="category-wrap-table">
					<div class="category-wrap-row">
						<div class="icon" style="background: url('{timthumb src => $category->icon, w => 35, h => 35 }') no-repeat center top;"></div>
						<div class="description">
							<h3><a href="{!$category->link}">{!$category->name}</a></h3>
							{!$category->excerpt}
						</div>
					</div>
				</div>
			</li>
		{last}</ul>{/last}
		{/foreach}
	</div>

	<hr>

	<div class="category-items clearfix">
		
		{include 'snippets/sorting.php'}

		<ul n:foreach="$posts as $item" class="items">
			<li class="item clear{ifset $item->packageClass} {$item->packageClass}{/ifset}{ifset $item->optionsDir['featured']} featured{/ifset}">
				{if $item->thumbnailDir}
				<div class="thumbnail">
                    <a href="{$item->link}">
					<img src="{timthumb src => $item->thumbnailDir}" alt="{__ 'Item thumbnail'}">
                    </a>
					<div class="comment-count">{$item->commentsCount}</div>
				</div>
				{/if}
				
				<div class="description">
					<h3>
						<a id="lp-listing-title" href="{!$item->link}">{$item->title}</a>
						{if $item->rating}
						<span class="rating">
							{for $i = 1; $i <= $item->rating['max']; $i++}
								<span class="star{if $i <= $item->rating['val']} active{/if}"></span>
							{/for}
						</span>
						{/if}
					</h3>
					<div class="item-info">
						<dl class="item-address">
							<?php if ($item->optionsDir['address'] != ''){ ?>
							<dt class="address"></dt>
							<dd class="data">{!$item->optionsDir['address']}</dd>
							<?php } ?>
							<?php if ($item->optionsDir['telephone'] != ''){ ?> 
							<dt class="phone"></dt>
							<dd id="item-info-phone" class="data">{$item->optionsDir['telephone']}</dd>
							<?php } ?>
							<?php if ( $item->optionsDir['email'] != ''){ ?>
							<dt class="email"></dt>
							<dd id="item-info-email" class="data"><a href="mailto:{!$item->optionsDir['email']}">{!$item->optionsDir['email']}</a></dd>
							<?php } ?>
							<?php if ( $item->optionsDir['web'] != ''){ ?>
							<dt class="web"></dt>
							<dd id="item-info-web" class="data"><a href="{!$item->optionsDir['web']}">{!$item->optionsDir['web']}</a></dd>
							<?php } ?>
						</dl>
					</div>
                    <div class="item-info item-right-info">
                        <?php echo sprintf( __( '<span class="%1$s">Category:</span> %2$s', "lokalpages" ), 'category-title-search', $item->categoryList ); ?>
                    </div>
					<!--{!$item->excerpt}-->
				</div>
			</li>
		</ul>

	</div>
	
	{willPaginate}
	<nav class="paginate-links">
		{paginateLinks true}
	</nav>
	{/willPaginate}

</article><!-- /#post-item-category -->

{ifset $themeOptions->advertising->showBox4}
<div id="advertising-box-4" class="advertising-box">
    {!$themeOptions->advertising->box4Content}
</div>
{/ifset}

{/block}