if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");
$(document).ready(function(){
    $("#add_more").click(function(){
        $.ajax({
            type: "GET",
            url: template_link+'/class/add_shop_field.php',
            success: function (data) {
                console.log(data);
                $("#shop_field tbody").append(data);
            }
        }); 
    });
});