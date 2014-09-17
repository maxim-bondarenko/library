<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true,
));
echo TbHtml::button('Добавить автора', array(
    'style' => TbHtml::BUTTON_COLOR_SUCCESS,
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'class'=> 'btn btn-primary',
    'data-toggle' => 'modal',
    'data-target' => '#myModal',
));
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $model->search(),
    'template' => "{items}",
    'columns' => array(
        array(
            'name' => 'author_name',
            'header' => 'Автор',
            'htmlOptions' => array('color' =>'width: 60px'),
        ),
        array(
            'name' => 'author_creation_date',
            'header' => 'Дата Добавления',
        ),
        array(
            'name' => 'last_update',
            'header' => 'Дата последнего обновления',
        ),
        array
        (
            'class'=>'CButtonColumn',
            'template'=>'{delete}{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'Редактировать',
                    'url'=>'$this->grid->controller->createUrl("library/getAuthorById", array("id"=>$data->id))',
                    'options'=>array(
                       'ajax'=>array(
                            'type'=>'GET',
                            'url'=>'js:$(this).attr("href")',
                            'success' => 'function(data){
                                openModal( "myModal", "Редактирование автора", data);
                            }',
                        ),

                    )
                ),
                'delete' => array(
                    'label'=>'Удалить',
                    'url'=>'$this->grid->controller->createUrl("library/delete", array("book_id"=>$data->id, "model_name"=>"author"))',
                )
            )
        )
    ),
));

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'myModal',
    'header' => 'Добавление автора',
    'content' => $this->renderPartial('_authorForm', array('model'=>$model, 'request_url'=>Yii::app()->createUrl('/library/library/addAuthor')), true),
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