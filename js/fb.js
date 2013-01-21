// JavaScript Document
	$('.album').click(function(){
		var album_id=this.id;
		var album_name=this.name;
	$('#maximage').load("album.php",{id:album_id},
						function(){
							$('a.gallery').colorbox({
								slideshow:true, slideshowSpeed:2500, preloading:true,rel:'gallery'
							})
							$('a.gallery:first').trigger('click');
						}
					);
	});
	$('.download').click(function(){
		var album_id=this.id;
		var album_name=this.title;
		$('a.download').css('display','none');	
		$('#image'+ album_id).css('display','block');
		var request=$.ajax({
			type: "POST",
			data: {id:album_id,name:album_name},
			url: "download.php",
			success: function(){
			return true;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				return false;
			}
		}); 
		request.done(function(){
		var	newrequest=$.ajax({
				type: "POST",
				url: "download_new.php",
				success: function(){
				return true;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					return false;
				}
			}); 
		newrequest.done(function(){
		$('#image'+ album_id).css('display','none');
		$('a.download').css('display','block');	
		});
		});
	});
