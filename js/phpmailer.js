/**
 * 发送邮件验证
 * @author lxl
 */
var PHPMailer={
    /**
     * 发送邮件
     */
    'sendEmail' : function() {
        $.ajax(
            {
                type: "POST",
                url : "http://hostidc.com/mail/send71wx.php",
                crossDomain: true,
                data: {
                    'userEmail'		 : $("#userEmail").val(),
                    'userName'		 : $("#userName").val(),
                    'userTitle'      : $("#userTitle").val(),
                    'userMsg'		 : "你好，<br>我们已经收到你的信息。<br><br>----------------<br><br>"+$("#userMsg").val().replace(/\\n/g,"<br />")
                },
                async: false,
                dataType: "json",
				//jsonp: "callback",
				//jsonpCallback:"sendEmailHandler",
                beforeSend: function (xhr) {
                    //pjax 实现
                    //xhr.setRequestHeader('X-PJAX', 'true');

                    /** 邮件发送中 **/
                    $("#send_email").css('display','none');
                    $("#loading").show();
                },
                success: function (result) {
                    //pjax 实现
                    //history.pushState(null, '', '/PHPMailer-Demo');

                    $("#loading").hide();
                    if (result.code==='ok') {
                        /** 发送成功 **/
                        $("#alert-success").show();
                        $("#alert-danger").hide();
                    } else {
	                    $("#send_email").show();
                        /** 发送失败 **/
                        $("#alert-success").hide();
                        $("#alert-danger").show();
                    }
                }
            });
    }
};

$(function(){
    $("#send_email").click(function(){
        ///** 验证 **/
        //if (PHPMailer.checkEmpty()) {
            /** 发送邮件 **/
            PHPMailer.sendEmail();
        //}
    });
});
