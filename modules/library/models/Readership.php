<?php

/**
 * This is the model class for table "readership".
 *
 * The followings are the available columns in table 'readership':
 * @property integer $id
 * @property string $reader_name
 * @property string $reader_creation_date
 * @property string $last_update
 *
 * The followings are the available model relations:
 * @property ReadersBooksRelation[] $readersBooksRelations
 */
class Readership extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'readership';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reader_name', 'required'),
            array('reader_name', 'length', 'max'=>255),
            array('reader_creation_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reader_name, reader_creation_date, last_update', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'readersBooksRelations' => array(self::HAS_MANY, 'ReadersBooksRelation', 'reader_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'reader_name' => 'Reader Name',
            'reader_creation_date' => 'Reader Creation Date',
            'last_update' => 'Last Update',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('reader_name',$this->reader_name,true);
        //$criteria->compare('reader_creation_date',$this->reader_creation_date,true);
        $criteria->compare('last_update',$this->last_update,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Readership the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /***
     * return reader info
     * @param $id
     * @return array|bool|CActiveRecord|mixed|null
     */
    public function actionGetReaderDataById($id)
    {
        $result = self::model()->findByPk($id);
        return ($result) ? $result : false;
    }

    /***
     * return Reader list
     * @return array
     */
    public function getReadersList()
    {
        $result = self::model()->findAll();
        return CHtml::listData($result, 'id', 'reader_name');
    }
}