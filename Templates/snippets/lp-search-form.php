            <div id="directory-search" data-interactive="{ifset $themeOptions->search->enableInteractiveSearch}yes{else}no{/ifset}">
                <div class="defaultContentWidth clearfix">
                    <form action="{$homeUrl}" id="dir-search-form" method="get" class="dir-searchform">
                        <div id="dir-search-inputs">
                            <div id="dir-holder">
                                <div class="dir-holder-wrap">
                                <input type="text" name="s" id="dir-searchinput-text" placeholder="{__ 'Search keyword...'}" class="dir-searchinput"{ifset $isDirSearch} value="{$site->searchQuery}"{/ifset}>

                                {ifset $themeOptions->search->showAdvancedSearch}
                                <div id="dir-searchinput-settings" class="dir-searchinput-settings">
                                    <div class="icon"></div>
                                    <div id="dir-search-advanced" style="display: none;">
                                        {ifset $themeOptions->search->advancedSearchText}<div class="text">{$themeOptions->search->advancedSearchText}</div>{/ifset}
                                        <div class="text-geo-radius clear">
                                            <div class="geo-radius">{__ 'Radius:'}</div>
                                            <div class="metric">km</div>
                                            <input type="text" name="geo-radius" id="dir-searchinput-geo-radius" value="{ifset $isGeolocation}{$geolocationRadius}{else}{ifset $themeOptions->search->advancedSearchDefaultValue}{$themeOptions->search->advancedSearchDefaultValue}{else}100{/ifset}{/ifset}" data-default-value="{ifset $themeOptions->search->advancedSearchDefaultValue}{$themeOptions->search->advancedSearchDefaultValue}{else}100{/ifset}">
                                        </div>
                                        <div class="geo-slider">
                                            <div class="value-slider"></div>
                                        </div>
                                        <div class="geo-button">
                                            <input type="checkbox" name="geo" id="dir-searchinput-geo"{ifset $isGeolocation} checked="true"{/ifset}>
                                        </div>
                                        <div id="dir-search-advanced-close"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="geo-lat" id="dir-searchinput-geo-lat" value="{ifset $_GET['geo-lat']}{$_GET['geo-lat']}{/ifset}">
                                <input type="hidden" name="geo-lng" id="dir-searchinput-geo-lng" value="{ifset $_GET['geo-lng']}{$_GET['geo-lng']}{/ifset}">
                                {/ifset}

                                <input type="text" id="dir-searchinput-category" placeholder="{__ 'All categories'}">
                                <input type="text" name="categories" id="dir-searchinput-category-id" value="0" style="display: none;">

                                <input type="text" id="dir-searchinput-location" placeholder="{__ 'All locations'}">
                                <input type="text" name="locations" id="dir-searchinput-location-id" value="0" style="display: none;">

                                <div class="reset-ajax"></div>
                                </div>
                            </div>
                        </div>
                        {ifset $themeOptions->search->showAdvancedSearch}

                        {/ifset}
                        <div id="dir-search-button">
                            <input type="submit" value="{__ 'Search'}" class="dir-searchsubmit">
                        </div>
                        <input type="hidden" name="dir-search" value="yes" />
                        <input type="hidden" name="post_type" value="ait-dir-item">
                    </form>
                </div>
            </div>