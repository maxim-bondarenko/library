<?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true,
    ));
    echo TbHtml::button('Добавить книгу', array(
        'style' => TbHtml::BUTTON_COLOR_SUCCESS,
        'size' => TbHtml::BUTTON_SIZE_SMALL,
        'class'=> 'btn btn-primary',
        'data-toggle' => 'modal',
        'data-target' => '#myModal',
    ));
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $model->getBooks(),
        'template' => "{items}{pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название Книги',
                'htmlOptions' => array('color' =>'width: 60px'),
            ),
            array(
                'name' => 'creation_date',
                'header' => 'Дата Добавления',
            ),
            array(
                'name' => 'count_books',
                'header' => 'Количество',
            ),
            array(
                'name' => 'authors.author_name',
                'header' => 'Автор',
                'type' => 'raw',
                'value'=>'implode(", ", CHtml::listData($data->authors, "id", "author_name"))',
            ),
            array
            (
                'class'=>'CButtonColumn',
                'template'=>'{delete}{update}',
                'buttons'=>array(
                    'update'=>array(
                        'label'=>'Редактировать',
                        'url'=>'$this->grid->controller->createUrl("library/getBookById", array("id"=>$data->id))',
                        'options'=>array(
                            'ajax'=>array(
                                'type'=>'GET',
                                'url'=>'js:$(this).attr("href")',
                                'success' => 'function(data){
                                    openModal( "myModal", "Редактирование книги", data);
                                }',
                            ),

                        )
                    ),
                    'delete' => array(
                        'label'=>'Удалить',
                        'url'=>'$this->grid->controller->createUrl("library/delete", array("book_id"=>$data->id, "model_name"=>"book"))',
                    )
                )
            )

        ),
));

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'myModal',
    'header' => 'Добавление Новой книги',
    'content' => $this->renderPartial('_booksForm', array(
            'model'=>$model,
            'author_list' => $authors->getAuthorsList(),
            'request_url'=>Yii::app()->createUrl('/library/library/addNewBook')), true),

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