$(document).ready(function () {
    var table = $('table');
    $.ajax({
        url: "api/books.php",
        method: "GET",
        dataType: "json",
        success: function (json) {
            for (var key in json) {
                table.append('<tr><td><p data-id="' + json[key].id + '">' + json[key].name + '</p><div></div></td></tr>');
            }
        }
    });
    //tytuły i przypisanie do nich eventów
    table.on('click', 'p', function () {
        var thisDiv = $(this).next();
        var id = $(this).data('id');
        //wczytanie opisu przez ajax
        $.ajax({
            url: "api/books.php?id=" + $(this).data('id'),
            method: "GET",
            dataType: "json",
            success: function (json) {
                console.log(id)
                thisDiv.html('<p>' + json[id].book_desc + '</p>').slideDown();
            }
        });
    });
});