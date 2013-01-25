// JavaScript Document
	//As the user clicks over album thumbnail this jquery function is called to post the Album Id 
	$('.album').click(function(){
		var album_id=this.id;
		$('#maximage').load("album.php",{id:album_id,type:'slideshow'},//Loads Slideshow content in div maximage, Post Album ID to album .php to get all the photos.
														function(){
															$('a.gallery').colorbox({
																slideshow:true, slideshowSpeed:2500, preloading:true,rel:'gallery'
															});
															$('a.gallery:first').trigger('click'); // Acts as Click event for First Album photo to start the slideshow
														}
							);
	});
	$('.download').click(function(){
		var album_id=this.id;
		var album_name=this.title;
		$('a.download').css('display','none');	// removes the download link for other albums
		$('#image'+ album_id).css('display','block'); //Loading image till user is prompt for download option
		var request=$.ajax({ //Ajax call to download script to get the photos and zip them
			type: "POST",
			data: {id:album_id,name:album_name,type:'download'},
			url: "album.php",
			success: function(){
					self.location='download_new.php' //On Completion of Zipping all the files, Request for headers to prompt user for download
					$('#image'+ album_id).css('display','none'); //Loading bar is removed
					$('a.download').css('display','block');	//download option for other albums is reactivated
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				return false;
			}
		}); 
	});

