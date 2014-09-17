<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true,
));
echo TbHtml::button('Выдать книгу', array(
    'style' => TbHtml::BUTTON_COLOR_SUCCESS,
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'class'=> 'btn btn-primary',
    'data-toggle' => 'modal',
    'data-target' => '#myModal',
));
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $model->getLibraryBook(),
    'columns' => array(
        array(
            'name' => 'book.name',
            'header' => 'Наименование Книги',
            'htmlOptions' => array('color' =>'width: 60px'),
        ),
        array(
            'name' => 'reader.reader_name',
            'header' => 'Читатель',
        ),
        array(
            'name' => 'creation_date',
            'header' => 'Дата выдачи',
        ),
        array(
            'name' => 'count_books',
            'header' => 'Количество',
        ),
        array
        (
            'class'=>'CButtonColumn',
            'template'=>'{delete}{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'Редактировать',
                    'url'=>'$this->grid->controller->createUrl("library/getLibraryDataById", array("id"=>$data->id))',
                    'options'=>array(
                        'ajax'=>array(
                            'type'=>'GET',
                            'url'=>'js:$(this).attr("href")',
                            'success' => 'function(data){
                                openModal( "myModal", "Редактирование читателя", data);
                            }',
                        ),

                    )
                ),
                'delete' => array(
                    'label'=>'Удалить',
                    'url'=>'$this->grid->controller->createUrl("library/delete", array("book_id"=>$data->id, "model_name"=>"library"))',
                )
            )
        )
    ),
));

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'myModal',
    'header' => 'Выдача книги читателю',
    'content' => $this->renderPartial('_libraryForm', array(
            'model'         =>$model,
            'reader_list'   => $reader_model->getReadersList(),
            'books_list'    => $book_model->getBookList(),
            'request_url'   =>Yii::app()->createUrl('/library/library/giveBookToReader')
        ), true),
));
?>

<script type="text/javascript">
    function openModal( id, header, body){
        var closeButton = '<button data-dismiss="modal" class="close" type="button">×</button>';
        $("#" + id + " .modal-header").html( closeButton + '<h3>'+ header + '</h3>');
        $("#" + id + " .modal-body").html(body);
        $("#" + id).modal("show");
    }
</script>