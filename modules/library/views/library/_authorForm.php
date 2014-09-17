<div class="form">
    <?php echo TbHtml::beginForm($request_url, 'post', array('enctype'=>'multipart/form-data')); ?>
    <div class="form-group has-success clearfix">
        <div class="wr col-sm-6">
            <?php echo TbHtml::activeTextField($model, 'author_name',array(
                'placeholder'=>'ФИО Автора',
                'required'=>'true',
                'autofocus'=>'true',
                'class'=>'form-control',
                'value'=> (isset($name)) ? $name : ''
            )); ?>
        </div>
    </div>
    <div>
        <?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    </div>
    <?php echo TbHtml::endForm(); ?>
</div>