<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true,
));
echo TbHtml::button('Добавить читателя', array(
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
            'name' => 'reader_name',
            'header' => 'Читатель',
            'htmlOptions' => array('color' =>'width: 60px'),
        ),
        array(
            'name' => 'reader_creation_date',
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
                    'url'=>'$this->grid->controller->createUrl("library/getReaderById", array("id"=>$data->id))',
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
                    'url'=>'$this->grid->controller->createUrl("library/delete", array("book_id"=>$data->id, "model_name"=>"reader"))',
                )
            )
        )
    ),
));

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'myModal',
    'header' => 'Добавление читателя',
    'content' => $this->renderPartial('_readerForm', array('model'=>$model, 'request_url'=>Yii::app()->createUrl('/library/library/addReader')), true),
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