$(document).ready(function(){
$.process_notifi = function(notif_id, loc_it, notif_type)
{
    if(typeof(notif_id)=='undefined'){return false;}

    if((typeof(loc_it)!='undefined') && (loc_it=='list'))
    var tar_fld = '#notifL_'+notif_id;
    else
    var tar_fld = '#notif_'+notif_id;

    var target = $(tar_fld).attr('target');
    //alert(target);

    if($(tar_fld).hasClass('unread'))
    {
        $(tar_fld).removeClass('unread');
        $(tar_fld).addClass('read');

        //#/ Mark as Read
        $.ajax({
        cache: false,
        type : 'Get',
        dataType: 'text',
        url: $.DOC_ROOT_+'notif?ni='+notif_id+'&nt='+notif_type,
        }).done(function(msg){
            if(msg=='1')
            {
                //#/ Change Count
                $.notifs_count--;
                if($.notifs_count>0)
                $('.notif_count').html($.notifs_count);
                else{
                $('.notif_count').html('');
                $('.notif_count_brackets').remove();
                }

                //#/ Take to Destination
                if(target!=''){
                location.href=target;
                return true;
                }
            }
        });
    }//end if unread....
    else
    {
        //#/ Take to Destination
        if(target!=''){
        location.href=target;
        return true;
        }
    }
};


$.visit_msgT = function(thread_id)
{
    if(thread_id<=0) return false;
    location.href = $.DOC_ROOT_+'message/'+thread_id;
}//end func...

});