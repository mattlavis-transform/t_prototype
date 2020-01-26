<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Custom Row Renderer Scenario - jsGrid Demo</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,600,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/demos.css" />
    <link rel="stylesheet" type="text/css" href="/css/jsgrid.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/jsgrid-theme.min.css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/cupertino/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    
    <script>window.jQuery || document.write('<script src="/js/jquery-1.8.3.js"><\/script>')</script>

    <script src="/js/jsgrid.min.js"></script>
    <script src="/js/i18n/jsgrid-fr.js"></script>
    <script src="db.js"></script>
</head>
<body>
<h1>Custom Row Renderer Scenario</h1>

<style>
    .client-photo { float: left; margin: 0 20px 0 10px; }
    .client-photo img { border-radius: 50%; border: 1px solid #ddd; }
    .client-info { margin-top: 10px; }
    .client-info p { line-height: 25px; }
</style>

<div id="jsGrid"></div>

<script>
$(function() {

    $("#jsGrid").jsGrid({
        height: "400px",
        width: "100%",

        autoload: true,
        paging: true,

        controller: {
            loadData: function() {
                var deferred = $.Deferred();

                $.ajax({
                    url: 'http://api.randomuser.me/?results=40',
                    dataType: 'jsonp',
                    success: function(data){
                        deferred.resolve(data.results);
                    }
                });

                return deferred.promise();
            }
        },

        rowRenderer: function(item) {
            var user = item;
            var $photo = $("<div>").addClass("client-photo").append($("<img>").attr("src", user.picture.large));
            var $info = $("<div>").addClass("client-info")
                .append($("<p>").append($("<strong>").text(user.name.first.capitalize() + " " + user.name.last.capitalize())))
                .append($("<p>").text("Location: " + user.location.city.capitalize() + ", " + user.location.street))
                .append($("<p>").text("Email: " + user.email))
                .append($("<p>").text("Phone: " + user.phone))
                .append($("<p>").text("Cell: " + user.cell));

            return $("<tr>").append($("<td>").append($photo).append($info));
        },

        fields: [
            { title: "Clients" }
        ]
    });


    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    };

});
</script>

</body>
</html>