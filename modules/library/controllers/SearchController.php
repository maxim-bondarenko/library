<?php

class SearchController extends Controller
{
    public function init()
    {
        $this->layout = '/layouts/main';
    }
    public function actionIndex()
    {
        $this->render('search');
    }

    public function actionSearch()
    {
        $model = Books::model();

        $params = array('result'=>$model->searchPhrase($_POST['search_field']));
        $this->render('search', $params);
        //echo '<pre>'; print_r($_POST); echo'</pre>';
    }
}