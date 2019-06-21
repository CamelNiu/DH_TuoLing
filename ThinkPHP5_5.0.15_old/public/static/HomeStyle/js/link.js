function click_num( link_id ){ 
    var post_url = window.location.href.substr(0,window.location.href.lastIndexOf('/'))+'/'+'click_num';
    var data = {link_id:link_id}
    $.ajax({ 
        url:post_url,
        data:data,
        type:'GET',
        dataType:'json',
        timeout:60000,
        success:function(data){ 
            if(!data){ 
                alert('网络错误,请联系站长');
                history.go(-1);
            }
        }
    })
}