<div class="form">
    <?php echo TbHtml::beginForm(Yii::app()->createUrl('/library/search/search'), 'post', array('enctype'=>'multipart/form-data')); ?>
    <div class="form-group has-success clearfix">
        <div class="wr col-sm-6">
            <?php echo TbHtml::TextField('search_field', "" ,array(
                'placeholder'=> 'Введите фразу для поиска',
                'required'=>'true',
            )); ?>
        </div>
        <div>
            <?php echo TbHtml::submitButton('Выдать', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        </div>
        <?php echo TbHtml::endForm(); ?>
    </div>
</div>

<?php
if(isset($result))
{
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $result,
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название книги',
                'htmlOptions' => array('color' =>'width: 60px'),
            )
        ),
    ));
}
?>