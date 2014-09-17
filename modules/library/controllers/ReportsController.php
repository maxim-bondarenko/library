<?php

class ReportsController extends Controller
{
    public function init()
    {
        $this->layout = '/layouts/main';
    }
    public function actionIndex()
    {
        $book_model = Books::model();
        $authors_model = Authors::model();

        $params = array(
            'book_model'    =>$book_model,
            'authors_model' => $authors_model
        );
        $this->render('report', $params);
    }
}