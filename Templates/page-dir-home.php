{extends $layout}

{block content}

<article id="post-{$post->id}" class="{$post->htmlClasses}">

	<header class="entry-header">
		
		<h1 class="entry-title">
			<a href="{$post->permalink}" title="{__ 'Permalink to'} {$post->title}" rel="bookmark">{$post->title}</a>
		</h1>

	</header>
	
	{if $post->thumbnailSrc}
	<a href="{!$post->thumbnailSrc}">
		<div class="entry-thumbnail"><img src="{timthumb src => $post->thumbnailSrc, w => 140, h => 200}" alt=""></div>
	</a>
	{/if}

	<div class="entry-content">
		{!$post->content}
	</div>

<div class="entry-content-adtl">
<div class="row">
    <div class="col-xs-6 col-md-4">
	{ifset $themeOptions->directory->showTopCategories}
		{if !empty($themeOptions->directory->topCategoriesTitle)}
		<h2 class="subcategories-title">{$themeOptions->directory->topCategoriesTitle}</h2>
		{/if}
		<div class="category-subcategories clearfix">
			{foreach $subcategories as $category}
            {first}<div class="list-group">{/first} 
              <a href="{!$category->link}" class="list-group-item">{!$category->name}{!$category->excerpt}</a>
            {last}<a href="http://lokalpages.com/business-categories/" class="list-group-item item-list-view-more">View More</a></div>{/last}
            
			{/foreach}
		</div>
	{/ifset}
    </div>
    
    <div class="col-xs-6 col-md-4">
	{ifset $themeOptions->directory->showTopLocations}
		{if !empty($themeOptions->directory->topLocationsTitle)}
		<h2 class="subcategories-title">{$themeOptions->directory->topLocationsTitle}</h2>
		{/if}
		<div class="category-subcategories clearfix">
			{foreach $sublocations as $location}
            {first}<div class="list-group">{/first} 
              <a href="{!$location->link}" class="list-group-item">{!$location->name}{!$location->excerpt}</a>
            {last}<a href="http://lokalpages.com/business-locations/" class="list-group-item item-list-view-more">View More</a></div>{/last}
			{/foreach}
		</div>
	{/ifset}
    </div>
	
    <div class="col-xs-6 col-md-4">
    <h2>CEBUAKNOW</h2>
        <div class="list-group">
            <a href="http://lokalpages.com/commercial-airline-asia/" class="list-group-item">First Airline in Asia</a>
            <a href="http://lokalpages.com/nato-asia/" class="list-group-item">NATO in ASIA</a>
            <a href="http://lokalpages.com/philippine-expeditionary-forces-korea/" class="list-group-item">Philippine troops in KOREA</a>
            <a href="http://lokalpages.com/leon-kilat/" class="list-group-item">LEON KILAT</a>
            <a href="http://lokalpages.com/opon-lapu-lapu-city-vice-versa/" class="list-group-item">Where is OPON in Lapu-Lapu?</a>
            <a href="http://lokalpages.com/cebuwaknow/" class="list-group-item item-list-view-more">View More</a>
        </div>
    
	{if $themeOptions->directory->dirHomepageAltContent}
	<div class="alternative-content">
		{!$themeOptions->directory->dirHomepageAltContent}
	</div>
	{/if}
    </div>
   
   </div>

   <div class="row">
        <div class="col-xs-12 col-md-4">
        <div class="lp-advertising">
                <a href="http://lokalpages.com/we-help-customers-find-your-business/"><div class="ads-image"></div></a>
            </div>
        </div>
        <div class="col-xs-6 col-md-8"><?php echo do_shortcode('[tptn_list]'); ?></div>  
    </div>
</div>  

</article><!-- /#post-{$post->id} -->

{ifset $themeOptions->advertising->showBox4}
<div id="advertising-box-4" class="advertising-box">
    {!$themeOptions->advertising->box4Content}
</div>
{/ifset}

{/block}
