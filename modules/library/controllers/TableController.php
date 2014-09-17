<?php

class TableController extends Controller
{
    public function actionIndex()
    {
        echo"Table Controller";
    }


    public function actionCreateTable()
    {
        $transaction =  Yii::app()->db->beginTransaction();
        try
        {
            $book = Yii::app()->db->createCommand(
                'CREATE TABLE IF NOT EXISTS books (id int(10) NOT NULL AUTO_INCREMENT,
                      name varchar(255) NOT NULL,
                      creation_date TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00",
                      last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      count_books int(5) NOT NULL,
                      reserved_count int(5) NOT NULL DEFAULT 0,
                      PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
                      ENGINE=InnoDB;'
            );
            $book->execute();

            $authors = Yii::app()->db->createCommand(
                'CREATE TABLE IF NOT EXISTS authors (id int(10) NOT NULL AUTO_INCREMENT,
                      author_name varchar(255) NOT NULL,
                      author_creation_date TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00",
                      last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
                      ENGINE=InnoDB;'
            );
            $authors->execute();

            $readership = Yii::app()->db->createCommand(
                'CREATE TABLE IF NOT EXISTS readership (id int(10) NOT NULL AUTO_INCREMENT,
                      reader_name varchar(255) NOT NULL,
                      reader_creation_date TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00",
                      last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
                      ENGINE=InnoDB;'
            );
            $readership->execute();

            $authors_books_relation = Yii::app()->db->createcommand(
                'CREATE TABLE IF NOT EXISTS authors_books_relation (id int(10) NOT NULL AUTO_INCREMENT,
                      book_id int(10) NOT NULL,
                      author_id int(10) NOT NULL,
                      PRIMARY KEY (id),
                      unique(book_id, author_id),
                      CONSTRAINT rel_book_id FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE,
                      CONSTRAINT rel_auth_id FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE
                      ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
                      ENGINE=InnoDB;'
            );

            $authors_books_relation->execute();

            $readers_books_relation = Yii::app()->db->createcommand(
                'CREATE TABLE IF NOT EXISTS readers_books_relation (id int(10) NOT NULL AUTO_INCREMENT,
                      book_id int(10) NOT NULL,
                      reader_id int(10) NOT NULL,
                      creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      count_books int(5) NOT NULL DEFAULT 1,
                      PRIMARY KEY (id),
                      CONSTRAINT reader_rel_book_id FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE,
                      CONSTRAINT reader_rel_read_id FOREIGN KEY (reader_id) REFERENCES readership (id) ON DELETE CASCADE
                      ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
                      ENGINE=InnoDB;'
            );

            $readers_books_relation->execute();

            $transaction->commit();
            echo"Tables was created";

        }catch(CDbException $e)
        {
            $transaction->rollback();
            echo"Error: Data base create failure";
        }
    }
}