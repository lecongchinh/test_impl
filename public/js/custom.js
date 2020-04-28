
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

// Enable pusher logging
    Pusher.logToConsole = true;

    var pusher = new Pusher('e176a1c51643fe2d7535', {
        cluster: 'ap1',
        forceTLS: true
    });

    var channel = pusher.subscribe('fork-channel');
    channel.bind('fork-repos', function(data) {
        let repos_name = (JSON.parse(JSON.stringify(data.data.repos_name)));
        let repos_url = (JSON.parse(JSON.stringify(data.data.repos_url)));
        $("#notifications").css("color", "#ff3300");
        $('.no-notification').hide();
        $(".notificationFork").append(
            "<div style=\"padding: 5px\" class=\"content-notification\" onclick=\"window.open("+"'"+repos_url+"'"+")\">\n" +
            "    Your fork is done and this is your new fork repo: <div style=\"color: #2176bd\">" +repos_name+ "</div>\n" +
            "</div>"
        );
    });
    //-------------------------//


    $(".data_show").on("change", function () {
        if($(".data_show").val() == "info_user") {
            $(".form_find_repo").hide();
        } else {
            $(".form_find_repo").show();
        }
    })

    $(".save_repo").on("click", function() {
        let repos_name = $(this).data("name");
        let repos_owner = $(this).data("owner");
        $.ajax({
            method: "POST",
            url: '/github/repos',
            data: {
                'repos_name': repos_name,
                'repos_owner': repos_owner
            },
            success: function (data) {
                alert(data.message);
            },
            error: function (data) {
                alert('Error: ' + data.responseJSON.message);
            }
        })
    })

    $(".fork_repos").on("click", function () {
        let repos_owner = $(this).data('repos-owner');
        let repos_name = $(this).data('repos-name');
        $.ajax({
            method: "POST",
            url: '/github/fork',
            data: {
                'repos_owner': repos_owner,
                'repos_name': repos_name,
            },
            success: function (data) {
                alert(data.message);
            },
            error: function (data) {
                console.log(data);
                alert('Fork error !');
            }
        })
    })
})
