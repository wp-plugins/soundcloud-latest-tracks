jQuery(document).ready(function() {
	var _sltPageCount = 1;

	function cascadeFadeIn(divIdNumber){
		if(jQuery(("#slt-track-"+divIdNumber)).length !== 0){
			jQuery(("#slt-track-"+divIdNumber)).fadeIn( 1300, function() {
				cascadeFadeIn((divIdNumber+1));
			});			
		}
		else if(jQuery(("#slt-track-"+(divIdNumber-1))).length !== 0){
			$('html, body').animate({
		        scrollTop: $(("#slt-track-"+(divIdNumber-1))).offset().top
		    }, 2000);
		}
	}

	function getUserTracks(trackPage){
		var _trackCount = (((trackPage-1)*slt.tracks));
		SC.get("/users/"+slt.userId+"/tracks", {limit: slt.tracks, offset : ((trackPage-1)*slt.tracks)}, function(tracks){
			for (var i = 0; i < slt.tracks; i++) {				
				var track = tracks[i];
				jQuery('<div/>', { id: ("slt-track-"+_trackCount) }).css('display', (trackPage == 1 ? "block" : "none")).appendTo('#slt-tracks-container');				
				if(slt.maxheight != 0)
		  			SC.oEmbed(track.uri, { maxheight: slt.maxheight, show_comments: slt.show_comments}, document.getElementById(("slt-track-"+_trackCount)));
		  		else
		  			SC.oEmbed(track.uri, { show_comments: slt.show_comments }, document.getElementById(("slt-track-"+_trackCount)));
		  		_trackCount++;
			};
			if(trackPage > 1)
				cascadeFadeIn((((trackPage-1)*slt.tracks)));  
		});
	}

	SC.initialize({
		client_id: '19878bb8fec7b82ae70433555ec7d7dd'
	});
	slt.tracks = parseInt(slt.tracks);
	slt.maxheight = parseInt(slt.maxheight);
	slt.show_comments = parseInt(slt.show_comments);
	getUserTracks(_sltPageCount);

	jQuery('#slt-hear-more').click(function(e) {
		e.preventDefault();
		_sltPageCount++;
		getUserTracks(_sltPageCount);
	});
	
});