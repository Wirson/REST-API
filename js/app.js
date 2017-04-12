$(document).ready(function () {
    var table = $('table');
    //loading all books
    $.ajax({
        url: "api/books.php",
        method: "GET",
        dataType: "json",
        success: function (json) {
            for (var key in json) { //dataset id = id książki z bazy
                table.append('<tr><td><p data-id="' + json[key].id + '">' + json[key].name + '</p><div></div></td></tr>');
            }
        }
    });
    //przypisanie eventów do tytułów w <p>
    table.on('click', 'p', function () {
        var id = $(this).data('id');
        //wczytanie opisu przez ajax do <div>, dodanie przycisku usuwającego
        var thisDiv = $(this).next();
        $.ajax({
            url: "api/books.php?id=" + $(this).data('id'),
            method: "GET",
            dataType: "json",
            success: function (json) {                       //button usuwający książkę z datasetem = id książki z bazy
                thisDiv.html('<p>' + json[id].book_desc + '</p><p>' + json[id].author + '</p><button data-id="' + json[id].id + '" id="delete">Delete book</button><button data-id="' + json[id].id + '" id="update">Update book</button>').slideDown();
            }
        });
    });
    //dodawanie książki, dane z inputów
    var name = $('#name');
    var author = $('#author');
    var bookDesc = $('#bookDesc');

    var btn = $('#submit');
    //event na submicie
    btn.on('click', function () {
        $(this).preventDefault;
        $.ajax({
            url: "api/books.php",
            data: {
                name: name.val(),
                author: author.val(),
                book_desc: bookDesc.val()
            },
            type: "POST",
            dataType: "json"
        });
    });
    //modify
    table.on('click', '#update', function () {

        var bookId = $(this).data('id');
        var updateForm = $(this).find('.update');

        $.ajax({
            url: 'api/books.php',
            method: 'GET',
            dataType: "json",
            success: function (json) {
                $('#updateId').attr('value', json[bookId].id);
                $('#updateName').attr('value', json[bookId].name);
                $('#updateAuthor').attr('value', json[bookId].author);
                $('#updateBookDesc').attr('value', json[bookId].book_desc);
            }
        })
        $('#updateBtn').on('click', function () {
            $(this).preventDefault;
            $.ajax({
                url: "api/books.php",
                method: "PUT",
                data: {
                    id: $('#updateId').val(),
                    name: $('#updateName').val(),
                    author: $('#updateAuthor').val(),
                    desc: $('#updateBookDesc').val()
                },
                dataType: "json",
                success: function () {
                    updateForm.slideUp();
                }
            })
        })
    });
    //usuwanie książki
    table.on('click', '#delete', function () {
        var btn = $(this).parent().parent().parent();
        //usuwanie rekodu z bazy
        $.ajax({
            url: "api/books.php?id=",
            data: {
                id: $(this).data('id')
            },
            method: "DELETE"
        }).//usuwanie wpisu ze strony
        done(function (data) {
            btn.remove();
        });
    });
});