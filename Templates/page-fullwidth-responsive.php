{extends $layout}

{block content}
<article id="post-{$post->id}" class="{$post->htmlClasses}">

    <header class="entry-header">
        
        <h1 class="entry-title" style="background: none;">
            <a style="background: none;" href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a>
        </h1>

{*
        <div class="entry-meta">
            <span class="sep">{__ 'Posted on'} </span>
            <a href="{dayLink $post->date}" title="{$post->date|date:$site->dateFormat}" rel="bookmark">
                <time class="entry-date" datetime="{$post->date|date:$site->dateFormat}" pubdate="">{$post->date|date:$site->dateFormat}</time>
            </a>
            <span class="by-author">
                <span class="sep"> {__ 'by'} </span>
                <span class="author vcard">
                    <a class="url fn n" href="{$post->author->postsUrl}" title="{__ 'View all posts by'} {$post->author->name}" rel="author"> {$post->author->name}</a>
                </span>
            </span>
        </div>
*}

    </header>
    
    {if $post->thumbnailSrc}
    <a href="{!$post->thumbnailSrc}">
        <div class="entry-thumbnail"><img src="{timthumb src => $post->thumbnailSrc, w => 940, h => 250}" alt=""></div>
    </a>
    {/if}

    <div class="entry-content">
        {!$post->content}
    </div>

</article><!-- /#post-{$post->id} -->

{ifset $themeOptions->advertising->showBox4}
<div id="advertising-box-4" class="advertising-box">
    {!$themeOptions->advertising->box4Content}
</div>
{/ifset}

{/block}