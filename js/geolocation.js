(function () {
    'use strict';

    var App = {
        APIKEY: "355c3e27b861e13825ea906b7273b78e",
        lat: "",
        lng: "",

        init: function () {
            App.getLocation();
        },
        getLocation: function () {
            navigator.geolocation.getCurrentPosition(App.userPosition);
        },
        userPosition: function (pos) {
            // found the current user position
            App.lat = pos.coords.latitude;
            App.lng =  pos.coords.longitude;
            App.getPlaceName();
        },
        getPlaceName: function () {
            var link = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + App.lat + "," + App.lng + "&key=AIzaSyBVTDzjj88Vq6Zy1yP-MtFRi6_jfT6j630";

            window.jQuery.ajax({
                url: link,
                dataType: "json",
                success: function (data) {
                    $("#place").val(data.results[0].address_components[1].short_name + " " + data.results[0].address_components[0].short_name + ", " + data.results[0].address_components[2].short_name);
                }
            });
        }
    };

    App.init();

}());