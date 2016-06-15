		<footer id="colophon" role="contentinfo">
			
			<div id="supplementary" class="widgets defaultContentWidth">
				
				{isActiveSidebar footer-widgets}
				<div id="footer-widgets" class="widget-area" role="complementary">
					{dynamicSidebar footer-widgets}
				</div>
				{/isActiveSidebar}

			</div>

			{include 'snippets/branding-footer.php'}

		</footer>

	</div><!-- #page -->

	{footer}

	{if isset($themeOptions->general->ga_code) && ($themeOptions->general->ga_code!="")}
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', {$themeOptions->general->ga_code}]);
		_gaq.push(['_trackPageview']);

		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	{/if}

</body>
</html>