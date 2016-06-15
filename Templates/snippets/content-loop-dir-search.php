{include 'sorting.php'}

{foreach $posts as $item}
{first}<ul class="items">{/first}     
	<li class="item clear{ifset $item->packageClass} {$item->packageClass}{/ifset}{ifset $item->optionsDir['featured']} featured{/ifset}">
		{if $item->thumbnailDir}
        <div class="thumbnail">
			<a href="{$item->link}">
            <img src="{timthumb src => $item->thumbnailDir, w => 100, h => 100}" alt="{__ 'Item thumbnail'}">
            </a>
			<div class="comment-count">{$item->comment_count}</div>
		</div>
		{/if}
		
		<div id="item-information" class="description">
			<h3>
				<a id="lp-listing-title" href="{!$item->link}">{$item->post_title}</a>
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
		</div>
	</li>
{last}</ul>{/last}
{/foreach}
