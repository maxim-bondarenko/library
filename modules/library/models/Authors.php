<?php

/**
 * This is the model class for table "authors".
 *
 * The followings are the available columns in table 'authors':
 * @property integer $id
 * @property string $author_name
 * @property string $author_creation_date
 * @property string $last_update
 *
 * The followings are the available model relations:
 * @property AuthorsBooksRelation[] $authorsBooksRelations
 */
class Authors extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'authors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('author_name, ', 'required'),
            array('author_name', 'length', 'max'=>255),
            array('author_creation_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, author_name, author_creation_date, last_update', 'safe', 'on'=>'search'),
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
            'authorsBooksRelations' => array(self::HAS_MANY, 'AuthorsBooksRelation', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'author_name' => 'Author Name',
            'author_creation_date' => 'Author Creation Date',
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
        $criteria->compare('author_name',$this->author_name,true);
        //$criteria->compare('author_creation_date',$this->author_creation_date,true);
        $criteria->compare('last_update',$this->last_update,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Authors the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getAuthorsList()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('author_name',$this->author_name,true);
        $result = self::model()->findAll($criteria);
        return CHtml::listData($result, 'id', 'author_name');
    }

    public function updateAuthor($id, $params)
    {
        return (self::model()->updateByPk($id, array('author_name'=>$params['author_name']))) ? true : false;
    }

    /**
     * return nifo author by id
     * @param $id
     * @return array|bool|CActiveRecord|mixed|null
     */
    public function actionGetAuthorById($id)
    {
        $result = self::model()->findByPk($id);
        return ($result) ? $result : false;
    }

    public function getAuthorsWhereReadReader()
    {
        $criteria = new CDbCriteria;
        $criteria->group='`t`.`id`';
        $criteria->join = 'INNER JOIN authors_books_relation ON `t`.`id` = authors_books_relation.author_id';
        $criteria->join .= ' INNER JOIN readers_books_relation ON authors_books_relation.book_id = readers_books_relation.book_id';
        $criteria->having = 'COUNT(readers_books_relation.reader_id) >= 3';

        return new CActiveDataProvider($this, array(
            'pagination'=>false,
            'criteria'=>$criteria,
        ));

    }
}