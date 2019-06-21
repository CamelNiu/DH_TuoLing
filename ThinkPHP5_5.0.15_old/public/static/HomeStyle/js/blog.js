function click_blognum(blog_id)
{ 
    var post_url = window.location.href.substr(0,window.location.href.lastIndexOf('/'))+'/'+'click_num';
    var data = {bloginfo_id:blog_id};
    $.ajax({ 
        url:post_url,
        data:data,
        type:'GET',
        dataType:'json',
        timeout:60000,
        success:function(data){ 
            if(!data){ 
                alert('网络错误');
                history.go(-1);
            }
        }
    })
}