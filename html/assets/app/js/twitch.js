$(function() {

    Twitch.init({clientId: twitch_client_id}, function(error, status) {
    if (error) {
        console.log(error);
        alert('Twitch is failed. Please retry later');
    }
    if (status.authenticated) {
        var token = Twitch.getToken();
        window.location.href = "http://stackoverflow.com";
    }
});

$('#twitchLoginBtn').click(function () {
    Twitch.login({
        scope: ['user_read', 'channel_read']
    });
});

$('#twitchLogoutBtn').click(function () {
    Twitch.logout(function(error) {
        console.log(error);
        alert('Twitch is failed. Please retry later');
    });
});

});
