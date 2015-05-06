<?php

/**
 * GroupsMembers
 *
 * Модель для работы с участниками блога
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.group.models
 * @since 0.1
 *
 */

/**
 * This is the model class for table "groups_members".
 *
 * The followings are the available columns in table 'groups_members':
 * @property string $id
 * @property string $user_id
 * @property string $group_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $role
 * @property integer $status
 * @property string $note
 *
 * The followings are the available model relations:
 * @property Groups $group
 * @property User $user
 */
class GroupsMembers extends yupe\models\YModel
{
    const ROLE_USER = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMIN = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 2;
    const STATUS_DELETED = 3;
    const STATUS_CONFIRMATION = 4;

    /**
     * Returns the static model of the specified AR class.
     * @param  string $className
     * @return GroupsMembers the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{groups_members}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['user_id, group_id', 'required', 'except' => 'search'],
            ['role, status, user_id, group_id, create_time, update_time', 'numerical', 'integerOnly' => true],
            ['user_id, group_id, create_time, update_time, role, status', 'length', 'max' => 11],
            ['note', 'length', 'max' => 250],
            ['role', 'in', 'range' => array_keys($this->getRoleList())],
            ['status', 'in', 'range' => array_keys($this->getStatusList())],
            ['note', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
            ['id, user_id, group_id, create_time, update_time, role, status, note', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'group' => [self::BELONGS_TO, 'Groups', 'group_id'],
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
        ];
    }

    public function afterSave()
    {
        Yii::app()->cache->delete("Groups::GroupsMembers::{$this->user_id}");

        return parent::afterSave();
    }

    public function beforeDelete()
    {
        Yii::app()->cache->delete("Groups::GroupsMembers::{$this->user_id}");

        return parent::beforeDelete();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('GroupsModule.groups', 'id'),
            'user_id'     => Yii::t('GroupsModule.groups', 'User'),
            'group_id'    => Yii::t('GroupsModule.groups', 'Group'),
            'create_time' => Yii::t('GroupsModule.groups', 'Created at'),
            'update_time' => Yii::t('GroupsModule.groups', 'Updated at'),
            'role'        => Yii::t('GroupsModule.groups', 'Role'),
            'status'      => Yii::t('GroupsModule.groups', 'Status'),
            'note'        => Yii::t('GroupsModule.groups', 'Notice'),
        ];
    }

    /**
     * @return array customized attribute descriptions (name=>description)
     */
    public function attributeDescriptions()
    {
        return [
            'id'      => Yii::t('GroupsModule.groups', 'Member Id.'),
            'user_id' => Yii::t('GroupsModule.groups', 'Please choose a user which will become the member of the group'),
            'group_id' => Yii::t('GroupsModule.groups', 'Please choose id of the group.'),
            'role'    => Yii::t(
                    'GroupsModule.groups',
                    'Please choose user role:<br /><br /><span class="label label-success">User</span> &ndash; Can write and comment group posts.<br /><br /><span class="label label-warning">Moderator</span> &ndash; Can delete, edit or block posts and comments. Can ban, add or remove members.<br /><br /><span class="label label-important">Administrator</span> &ndash; Can block, add or remove groups and members.'
                ),
            'status'  => Yii::t(
                    'GroupsModule.groups',
                    'Please choose status of the member:<br /><br /><span class="label label-success">Active</span> &ndash; Active member of the group.<br /><br /><span class="label label-warning">blocked</span> &ndash; Cannot access the group.'
                ),
            'note'    => Yii::t('GroupsModule.groups', 'Short note about the group member.'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('group_id', $this->group_id);
        if ($this->create_time) {
            $criteria->compare('DATE(from_unixtime(t.create_time))', date('Y-m-d', strtotime($this->create_time)));
        }
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('role', $this->role);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('note', $this->note);

        $criteria->with = ['user', 'group'];

        return new CActiveDataProvider(get_class($this), [
            'criteria' => $criteria,
            'sort'     => [
                'defaultOrder' => 't.id DESC'
            ]
        ]);
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class'             => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
            ],
        ];
    }

    public function getRoleList()
    {
        return [
            self::ROLE_USER      => Yii::t('GroupsModule.groups', 'User'),
            self::ROLE_MODERATOR => Yii::t('GroupsModule.groups', 'Moderator'),
            self::ROLE_ADMIN     => Yii::t('GroupsModule.groups', 'Administrator'),
        ];
    }

    public function getRole()
    {
        $data = $this->getRoleList();

        return isset($data[$this->role])
            ? $data[$this->role]
            : Yii::t('GroupsModule.groups', '*unknown*');
    }

    public function getStatusList()
    {
        return [
            self::STATUS_ACTIVE       => Yii::t('GroupsModule.groups', 'Active'),
            self::STATUS_BLOCK        => Yii::t('GroupsModule.groups', 'Blocked'),
            self::STATUS_DELETED      => Yii::t('GroupsModule.groups', 'Deleted'),
            self::STATUS_CONFIRMATION => Yii::t('GroupsModule.groups', 'Confirmation')
        ];
    }

    public function getStatus()
    {
        $data = $this->getStatusList();

        return isset($data[$this->status])
            ? $data[$this->status]
            : Yii::t('GroupsModule.groups', '*unknown*');
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isConfirmation()
    {
        return $this->status === self::STATUS_CONFIRMATION;
    }

    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;

        return $this;
    }

}