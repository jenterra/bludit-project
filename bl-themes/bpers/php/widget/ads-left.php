<!-- Ads Widget - Left Sidebar -->
<!-- 
This widget is for the LEFT sidebar only.
Use a UNIQUE ad slot ID here (different from the right sidebar).

Current ad slot: 6985781915 (LEFT SIDEBAR)
If you want different ads in left and right, create a new ad unit in AdSense
and use a different slot ID for the right sidebar.
-->
<div class="ads-widget sidebar-widget mb-4">
	<div class="widget-content">
		<div class="ad-container ad-container-left">
			<!-- Google AdSense Ad - Left Sidebar -->
			<!-- Testing -->
			<ins class="adsbygoogle"
			     id="adsense-left-slot"
			     style="display:inline-block;width:200px;height:500px"
			     data-ad-client="ca-pub-7019258972443870"
			     data-ad-slot="6985781915"></ins>
			<script>
			// Prevent duplicate initialization (fixes 400 error: iframe partially inserted)
			(function() {
				// Initialize tracking object
				if (!window._adsenseSlots) {
					window._adsenseSlots = new Set();
				}
				
				const slotId = '6985781915';
				
				// Check if this slot was already processed
				if (window._adsenseSlots.has(slotId)) {
					console.warn('AdSense slot ' + slotId + ' already initialized - skipping duplicate');
					return;
				}
				
				// Mark this slot as processed BEFORE pushing
				window._adsenseSlots.add(slotId);
				
				// Push to adsbygoogle array (only once per slot)
				(window.adsbygoogle = window.adsbygoogle || []).push({});
			})();
			</script>
		</div>
	</div>
</div>

