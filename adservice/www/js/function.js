//修改投放状态
function change_campaign_status(campaign_id,campaign_status)
{
	if(campaign_status==0)
	{
		var msg = '确定要运行投放吗？';
	}else{
		var msg = '确定要暂停投放吗？';
	}
	var button = confirm(msg);
	if(button==true)
	{
		 $.post(
					 "/advertise/campaign/change_campaign_status",
					 {campaign_id:campaign_id,campaign_status:campaign_status},
					 function(result){
						 var data = JSON.parse(result);
						 if(data.success)
						 {
							 alert(data.msg);
							 window.location.reload();
						 }
				 });
	}
}