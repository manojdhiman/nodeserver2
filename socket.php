<script type="text/javascript">
    // socket code
    var curr_userid = <?php echo isset(Yii::app()->user->id)?Yii::app()->user->id:"0"; ?>;
    if (curr_userid != 0) {
        var socket = io.connect('<?php echo Yii::app()->params['socket_server']; ?>');
        var msg = {
            type: 'connect',
            user_id: curr_userid,
            message: "",
            to_users: "",
            socket_id: ""
        };
        socket.emit("message", msg);
			
        socket.on('message', function(msg) {
			console.log(msg.type);
			//alert(msg.type);
			//alert(msg.type);
            if (msg.type == "notification") {
               increase_top_noti("#notify");
               displayflash('success',msg.message);
               if(msg.data!='')
               $('a[data-rel="'+msg.guid+'"]').find('likes').text(msg.data);
               
               
            } else if (msg.type == "message") {
				var url=base_url + "/inbox/getmessage/"+msg.guid;
				var convocationid=msg.convoid;
				ajaxcall(url, {convoid:convocationid}, function(output) 
				{
					increase_top_messages();
					var data = jQuery.parseJSON(output);
                      if($('#'+msg.convoid+' ul').length)
					  $('#'+msg.convoid+' ul').append(data.content);
					  else
					  $('#message-body ul').append(data.content);
					  if($("[data-rel='"+msg.convoid+"']").length)
					  $("[data-rel='" + msg.convoid+ "']").replaceWith(data.conversation); 
					  else
					  $('.convowrapper').append(data.conversation);
                });
            }else if(msg.type == "comment")
            {
				var data = jQuery.parseJSON(msg.message);
				if(data.status=='success')
				{
					$('a[data-rel="'+data.postguid+'"]').find('comment').text(data.commentcount);
					 $(data.content).hide().prependTo("#coment-con-" + data.postid).fadeIn("slow");
					
				}
				
			}else if(msg.type == "friendrequest")
			{
				increase_top_noti('#frnd_count');
				increase_top_noti("#notify");
                displayflash('success',msg.message);
				
			}



        });

        function set_message_readed() {
            jQuery.ajax({
                type: "post",
                url: jQuery("#base_url").val() + "/messages/readed_messages"
            });
        }

        function increase_top_noti(id) {
            var top_notfy = parseInt(jQuery(id).text());
            if (isNaN(top_notfy) == false) {
                top_notfy = top_notfy + 1;
            } else {
                top_notfy = 1;
            }
            jQuery(id).html(top_notfy);
        }

        function increase_top_messages() {
            var top_notfy = parseInt(jQuery("#msg_count").text());
            if (isNaN(top_notfy) == false) {
                top_notfy = top_notfy + 1;
            } else {
                top_notfy = 1;
            }
            jQuery("#msg_count").html(top_notfy);
        }

    }
function expire_session(job_id) {
        var url = jQuery("#base_url").val() + "/login/expire_session";
        jQuery.post(url, "job_id=" + job_id, function(msg) {
            alert(msg);
            window.location.reload();
        });
        }
</script>
