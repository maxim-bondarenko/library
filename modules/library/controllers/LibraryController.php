<?php

class LibraryController extends Controller
{

    public function init()
    {
        $this->layout = '/layouts/main';
    }

    public function actionTabs()
    {
        $tab_id = (isset($_GET['tab_id'])) ? $_GET['tab_id'] : 'books';

        switch($tab_id)
        {
            case 'authors': $this->authors(); break;
            case 'readers': $this->readers(); break;
            case 'library': $this->library(); break;
            default: $this->books();
        }
    }

    private function books()
    {
        $model = new Books();
        $authors_models = Authors::model();
        $params = array(
            'model'=>$model,
            'authors' => $authors_models
        );
        $this->render('books', $params);
    }

    private function authors()
    {
        $model = new Authors();
        $params = array(
            'model'=>$model
        );
        $this->render('authors', $params);
    }

    private function readers()
    {
        $model = new Readership();
        $params= array('model'=>$model);
        $this->render('readers', $params);
    }

    private function library()
    {
        $model = new ReadersBooksRelation();
        $reader_model = Readership::model();
        $book_model = Books::model();
        $params= array(
            'model'         =>$model,
            'reader_model'  => $reader_model,
            'book_model'    => $book_model
        );
        $this->render('library', $params);
    }

    public function actionDelete($book_id, $model_name)
    {
        $model = null;
        switch($model_name)
        {
            case 'book': $model = Books::model(); break;
            case 'author': $model = Authors::model(); break;
            case 'reader': $model = Readership::model(); break;
            case 'library': $model = ReadersBooksRelation::model(); break;
        }
        if(!is_null($model))
        {
            $model->deleteByPk($book_id);
        }
    }


    //////////////////////////////////////////////////Author

    public function actionAddAuthor()
    {
        $attributes = $_POST['Authors'];
        $attributes['author_creation_date'] = date('Y-m-d H:i:s');

        $authors_model = new Authors();
        $authors_model->attributes = $attributes;

        if($authors_model->validate())
        {
            if($authors_model->save(false))
            {
                Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
                    'Автор Добавлен');
                $this->redirect(array('library/tabs&tab_id=authors'));
            }
        }
    }

    public function actionUpdateAuthorByPk($id)
    {
        $model = Authors::model();
        $params = $_POST['Authors'];
        $result = $model->updateAuthor($id, $params);
        $message_text = ($result) ? 'Успешно' : 'Ошибка!!!!!!!';

        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
            $message_text);
        $this->redirect(array('library/tabs&tab_id=authors'));
    }

    public function actionGetAuthorById($id)
    {
        $author_model = Authors::model();
        $result = $author_model->actionGetAuthorById($id) ;
        if($result)
        {
            $params = array(
                'model'=>$author_model,
                'name'=>$result->author_name,
                'request_url' => Yii::app()->createUrl('/library/library/updateAuthorByPk', array('id'=>$id))
            );
            echo $this->renderPartial('_authorForm', $params, true);
        }else{
            echo "Нет информации....";
        }
    }

    //////////////////////////////////////////////////Author end
    //////////////////////////////////////////////////Book

    public function actionAddNewBook()
    {
        $transaction =  Yii::app()->db->beginTransaction();
        try
        {
            $book_model = new Books();

            $book_attributes = $_POST['Books'];
            $book_attributes['creation_date'] = date('Y-m-d H:i:s');
            $book_model->attributes = $book_attributes;
            if($book_model->validate())
            {
                if($book_model->save())
                {
                    $last_insert_book_id = $book_model->id;
                    $relation_array = array();
                    foreach($_POST['author_list'] as $author)
                    {
                        $relation_array[] = array('book_id'=>$last_insert_book_id, 'author_id'=>$author);
                    }

                    $builder=Yii::app()->db->schema->commandBuilder;
                    $command=$builder->createMultipleInsertCommand('authors_books_relation',  $relation_array);
                    $command->execute();

                    $transaction->commit();
                    Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
                        'Новая книга добавлена');
                    $this->redirect(array('library/tabs&tab_id=books'));
                }
            }
        }catch(CDbException $e)
        {
            $transaction->rollback();
        }
    }

    public function actionGetBookById($id)
    {
        $book_model = Books::model();
        $result = $book_model->getBookInfoById($id);
        if($result)
        {
            $params = array(
                'model'=>$book_model,
                'result'=> $result,
                'author_list' => CHtml::listData($result->authors, 'id', 'author_name'),
                'request_url' => Yii::app()->createUrl('/library/library/updateBookByPk', array('id'=>$id))
            );
            echo $this->renderPartial('_booksForm', $params, true);
        }
    }


    public function actionUpdateBookByPk($id)
    {
        $transaction =  Yii::app()->db->beginTransaction();
        try
        {
            $relation_model = AuthorsBooksRelation::model();
            $relation_model->deleteAll('book_id=:id',array(':id'=>$id));
            $attribute = $_POST['Books'];
            $book_model = Books::model();
            $book_model->attributes = $attribute;
            if($book_model->validate())
            {
                if($book_model->updateByPk($id, $attribute))
                {
                    foreach($_POST['author_list'] as $author)
                    {
                        $relation_array[] = array('book_id'=>$id, 'author_id'=>$author);
                    }
                    $builder=Yii::app()->db->schema->commandBuilder;
                    $command=$builder->createMultipleInsertCommand('authors_books_relation',  $relation_array);
                    $command->execute();
                    $transaction->commit();
                    $this->redirect(array('library/tabs&tab_id=books'));
                }
            }
        }catch(CDbException $e)
        {
            $transaction->rollback();
        }
    }
    //////////////////////////////////////////////////Book end
    //////////////////////////////////////////////////Reader

    public function actionAddReader()
    {
        $attributes = $_POST['Readership'];
        $attributes['reader_creation_date'] = date('Y-m-d H:i:s');

        $authors_model = new Readership();
        $authors_model->attributes = $attributes;

        if($authors_model->validate())
        {
            if($authors_model->save(false))
            {
                Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
                    'Новый читатель Добавлен');
                $this->redirect(array('library/tabs&tab_id=readers'));
            }
        }
    }

    public function actionGetReaderById($id)
    {
        $reader_model = Readership::model();
        $result = $reader_model->actionGetReaderDataById($id) ;
        if($result)
        {
            $params = array(
                'model'=>$reader_model,
                'name'=>$result->reader_name,
                'request_url' => Yii::app()->createUrl('/library/library/updateReaderByPk', array('id'=>$id))
            );
            echo $this->renderPartial('_readerForm', $params, true);
        }else{
            echo "Нет информации....";
        }
    }

    public function actionUpdateReaderByPk($id)
    {
        $model = Readership::model();
        $params = $_POST['Readership'];

        $model->attributes = $params;
        if($model->validate())
        {
            if($model->updateByPk($id, $params))
            {
                $this->redirect(array('library/tabs&tab_id=readers'));
            }
        }
    }
    //////////////////////////////////////////////////Reader end
    //////////////////////////////////////////////////Library

    public function actionGiveBookToReader()
    {
       $params = $_POST['ReadersBooksRelation'];
       $book_id = $_POST['books_list'];
       $params['book_id'] = $book_id;
       $params['reader_id'] = $_POST['reader_list'];
       $message = '';
       $color = '';

       $free_book = Books::model()->isFreeBook($book_id, $params['count_books']);
       if(!$free_book)
       {
           $message = 'Все экземпляры книги находятся у читателей!!!';
           $color = TbHtml::ALERT_COLOR_WARNING;
       }
       else
       {
           $model = new ReadersBooksRelation;
           $model->attributes = $params;

           if($model->validate())
           {
               if($model->save())
               {
                   $message = 'Книга выдана';
                   $color   = TbHtml::ALERT_COLOR_SUCCESS;
               }
           }
       }
        Yii::app()->user->setFlash($color, $message);
        $this->redirect(array('library/tabs&tab_id=library'));
    }

    public function actionGetLibraryDataById($id)
    {
        $model = ReadersBooksRelation::model();
        $reader_model = Readership::model();
        $book_model = Books::model();

        $result = $model->getDataRelationById($id);

        if($result)
        {
            $params = array(
                'model'             =>$model,
                'book_id_checked'   => $result->book_id,
                'reader_id_checked' =>$result->reader_id,
                'count'             => $result->count_books,
                'reader_list'       => $reader_model->getReadersList(),
                'books_list'        => $book_model->getBookList(),
                'request_url' => Yii::app()->createUrl('/library/library/updateLibraryData', array('id'=>$id))
            );
            echo $this->renderPartial('_libraryForm', $params, true);
        }else{
            echo "Нет информации....";
        }
    }

    public function actionUpdateLibraryData($id)
    {
        $params = $_POST['ReadersBooksRelation'];
        $book_id = $_POST['books_list'];
        $params['book_id'] = $book_id;
        $params['reader_id'] = $_POST['reader_list'];
        $model = new ReadersBooksRelation();
        $last_count = ($res = $model->getDataRelationById($id)) ? ((int)$params['count_books'] - (int)$res->count_books ): $params['count_books'];

        $free_book = Books::model()->isFreeBook($book_id, $last_count);
        $message = '';
        $color = '';
        if(!$free_book)
        {
            $message = 'Все экземпляры книги находятся у читателей!!!';
            $color = TbHtml::ALERT_COLOR_WARNING;
        }
        else
        {
            $model->deleteByPk($id);
            $model->attributes = $params;
            if($model->validate())
            {
                if($model->save())
                {
                    $message = 'Обновление Прошло успешно';
                    $color   = TbHtml::ALERT_COLOR_SUCCESS;
                }
            }
        }
        Yii::app()->user->setFlash($color, $message);
        $this->redirect(array('library/tabs&tab_id=library'));
    }
    //////////////////////////////////////////////////Library End
}