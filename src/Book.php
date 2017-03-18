<?php

class Book implements JsonSerializable
{

    private $id;
    private $name;
    private $author;
    private $book_desc;
    public static $conn;
    
    public function __construct($name = '', $author = '', $book_desc = '')
    {
        $this->id = -1;
        $this->name = $name;
        $this->author = $author;
        $this->book_desc = $book_desc;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getBook_desc()
    {
        return $this->book_desc;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setBook_desc($book_desc)
    {
        $this->book_desc = $book_desc;
    }

    public function loadFromDB($id)
    {
        $safe_id = self::$conn->real_escape_string($id);
        $sql = "SELECT * FROM books WHERE id = $safe_id";

        if ($result = self::$conn->query($sql)) {
            $row = $result->fetch_assoc();

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->author = $row['author'];
            $this->book_desc = $row['book_desc'];

            return true;
            
        } else {
            
            return false;
        }
    }
    
    public static function loadAllBooks()
    {
        $sql = "SELECT * FROM books";
        $ret = [];
        $result = self::$conn->query($sql);
        if ($result !== false) {
            foreach ($result as $row) {
                $book = new Book();
                $book->id = $row['id'];
                $book->name = $row['name'];
                $book->author = $row['author'];
                $book->book_desc = $row['book_desc'];
                $ret[] = $book;
            }
        } else {
            $ret = 'no books in DB!';
        }
        return $ret;
    }

    public function create( $name, $author, $book_desc)
    {
        $safe_name = self::$conn->real_escape_string($name);
        $safe_author = self::$conn->real_escape_string($author);
        $safe_book_desc = self::$conn->real_escape_string($book_desc);

        $sql = "INSERT INTO books(name, author, book_desc) VALUES ('$safe_name', '$safe_author', '$safe_book_desc')";

        if ($result = self::$conn->query($sql)) {
            $this->id = self::$conn->insert_id;
            $this->name = $name;
            $this->author = $author;
            $this->book_desc = $book_desc;

            return true;
        } else {
            return false;
        }
    }

    public function update($name, $author, $book_desc)
    {
        $safe_name = self::$conn->real_escape_string($name);
        $safe_author = self::$conn->real_escape_string($author);
        $safe_book_desc = self::$conn->real_escape_string($book_desc);
        $safe_id = self::$conn->real_escape_string($this->id);

        $sql = "UPDATE books SET name='$safe_name', author='$safe_author', book_desc='$safe_book_desc'"
            . "WHERE id=$safe_id";
        $result = self::$conn->query($sql);

        if ($result = self::$conn->query($sql)) {
            $this->name = $name;
            $this->author = $author;
            $this->book_desc = $book_desc;

            return true;
        } else {
            return false;
        }
    }

    public function deleteFromDB()
    {
        $safe_id = self::$conn->real_escape_string($this->id);

        $sql = "DELETE FROM books WHERE id=$safe_id";

        if ($result = self::$conn->query($sql)) {
            $this->name = '';
            $this->author = '';
            $this->book_desc = '';
            $this->id = -1;
            
            return true;
            
        } else {
            
            return false;
            
        }
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'book_desc' => $this->book_desc
        ];
    }

    static public function getBooksIds()
    {
        $sql = "SELECT id FROM books ORDER BY author, name";
        $ret = [];

        $result = self::$conn->query($sql);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $loadedBook = new Book();
                $loadedBook->id = $row['id'];

                $ret[$loadedBook->id] = $loadedBook;
            }
        } else {
            return NULL;
        }

        return $ret;
    }

}
