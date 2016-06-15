<div class="item-info">
        
        {if (!empty($options['address'])) || (!empty($options['gpsLatitude'])) || (!empty($options['telephone'])) || (!empty($options['email'])) || (!empty($options['web']))}
        <dl class="item-address">
            
            <dt class="title"><h4>{__ 'Our address'}</h4></dt> 
            
            {if (!empty($options['address']))}
            <dt class="address">{__ 'Address:'}</dt>
            <dd class="data">{!$options['address']}</dd>
            {/if}
            
            {if (!empty($options['mobile']))}
            <dt class="mobile">{__ 'Mobile:'}</dt>
            <dd class="data">{$options['mobile']}</dd>
            {/if}
            
            {if (!empty($options['telephone']))}
            <dt class="phone">{__ 'Telephone:'}</dt>
            <dd class="data">{$options['telephone']}</dd>
            {/if}
            
            {if (!empty($options['email']))}
            <dt class="email">{__ 'Email:'} </dt>
            <dd class="data"><a href="mailto:{!$options['email']}">{!$options['email']}</a></dd>
            {/if}

            {if (!empty($options['web']))} 
            <dt class="web">{__ 'Web:'} </dt>
            <dd class="data"><a href="{!$options['web']}">{!$options['web']}</a></dd>
            {/if}
            
        </dl>
        {/if}

        {if (!empty($options['hoursMonday'])) || (!empty($options['hoursTuesday'])) || (!empty($options['hoursWednesday'])) || (!empty($options['hoursThursday'])) || (!empty($options['hoursFriday'])) || (!empty($options['hoursSaturday'])) || (!empty($options['hoursSunday']))}     
        <dl class="item-hours">
            
            <dt class="title"><h4>{__ 'Opening Hours'}</h4></dt> 
            
            {if (!empty($options['hoursMonday']))}
            <dt class="day">{__ 'Monday:'}</dt>
            <dd class="data">{!$options['hoursMonday']}</dd>
            {/if}
            
            {if (!empty($options['hoursTuesday']))}
            <dt class="day">{__ 'Tuesday:'}</dt>
            <dd class="data">{!$options['hoursTuesday']}</dd>
            {/if}
            
            {if (!empty($options['hoursWednesday']))}
            <dt class="day">{__ 'Wednesday:'}</dt>
            <dd class="data">{!$options['hoursWednesday']}</dd>
            {/if}
            
            {if (!empty($options['hoursThursday']))}
            <dt class="day">{__ 'Thursday:'}</dt>
            <dd class="data">{!$options['hoursThursday']}</dd>
            {/if}
            
            {if (!empty($options['hoursFriday']))}
            <dt class="day">{__ 'Friday:'}</dt>
            <dd class="data">{!$options['hoursFriday']}</dd>
            {/if}

            {if (!empty($options['hoursSaturday']))}
            <dt class="day">{__ 'Saturday:'}</dt>
            <dd class="data">{!$options['hoursSaturday']}</dd>
            {/if}
            
            {if (!empty($options['hoursSunday']))}
            <dt class="day">{__ 'Sunday:'}</dt>
            <dd class="data">{!$options['hoursSunday']}</dd>
            {/if}
            
        </dl>
        {/if}

    </div>
    
    
    <div id="item-small-map-wrap" style="float: right;">
        {if (empty($isServMapDisable)) }
        <div class="item-map clearfix"></div>
        {/if}
        {if (empty($isServGPSMapDisable)) } 
        <div style="text-align: center;">
            <a id="get-direction-button" class="get-direction button" style="width: 90%;" href="{$post->permalink}?direction=yes">{_ "How to get there"}</a>
        </div>
        {/if}   
    </div> 