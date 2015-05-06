<?php

/**
 * m000000_000000_groups_base
 *
 * Group install migration
 * Класс миграций для модуля Group:
 *
 * @category YupeMigration
 * @package  yupe.modules.groups.install.migrations
 * @author yupe team <team@yupe.ru>
 * @license  BSD https://raw.github.com/yupe/yupe/master/LICENSE
 * @link     http://yupe.ru
 **/
class m000000_000000_groups_base extends yupe\components\DbMigration
{
    /**
     * Функция настройки и создания таблицы:
     *
     * @return null
     **/
    public function safeUp()
    {
        //group
        $this->createTable(
            '{{groups}}',
            [
                'id'             => "pk",
                'name'           => "varchar(250) NOT NULL",
                'description'    => "text",
                'icon'           => "varchar(250) NOT NULL DEFAULT ''",
                'slug'           => "varchar(150) NOT NULL",
                'lang'           => "char(2) DEFAULT NULL",
                'type'           => "integer NOT NULL DEFAULT '1'",
                'status'         => "integer NOT NULL DEFAULT '1'",
                'create_user_id' => "integer NOT NULL",
                'update_user_id' => "integer NOT NULL",
                'create_time'    => "integer NOT NULL",
                'update_time'    => "integer NOT NULL",
                'member_status'  => "integer NOT NULL DEFAULT '1'",
                'post_status'    => "integer NOT NULL DEFAULT '1'"
            ],
            $this->getOptions()
        );

        // ix
        $this->createIndex("ux_{{groups}}_slug_lang", '{{groups}}', "slug,lang", true);
        $this->createIndex("ix_{{groups}}_create_user", '{{groups}}', "create_user_id", false);
        $this->createIndex("ix_{{groups}}_update_user", '{{groups}}', "update_user_id", false);
        $this->createIndex("ix_{{groups}}_status", '{{groups}}', "status", false);
        $this->createIndex("ix_{{groups}}_type", '{{groups}}', "type", false);
        $this->createIndex("ix_{{groups}}_create_time", '{{groups}}', "create_time", false);
        $this->createIndex("ix_{{groups}}_update_time", '{{groups}}', "update_time", false);
        $this->createIndex("ix_{{groups}}_lang", '{{groups}}', "lang", false);
        $this->createIndex("ix_{{groups}}_slug", '{{groups}}', "slug", false);

        // fk
        $this->addForeignKey(
            "fk_{{groups}}_create_user",
            '{{groups}}',
            'create_user_id',
            '{{user_user}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        // post
        $this->createTable(
            '{{groups_post}}',
            [
                'id'             => "pk",
                'group_id'      => "integer NOT NULL",
                'create_user_id' => "integer NOT NULL",
                'update_user_id' => "integer NOT NULL",
                'create_time'    => "integer NOT NULL",
                'update_time'    => "integer NOT NULL",
                'publish_time'   => "integer NOT NULL",
                'slug'           => "varchar(150) NOT NULL",
                'lang'           => "char(2) DEFAULT NULL",
                'title'          => "varchar(250) NOT NULL",
                'quote'          => "varchar(500) NOT NULL DEFAULT ''",
                'content'        => "text NOT NULL",
                'link'           => "varchar(250) NOT NULL DEFAULT ''",
                'status'         => "integer NOT NULL DEFAULT '0'",
                'comment_status' => "integer NOT NULL DEFAULT '1'",
                'create_user_ip' => "varchar(20) NOT NULL",
                'access_type'    => "integer NOT NULL DEFAULT '1'",
                'keywords'       => "varchar(250) NOT NULL DEFAULT ''",
                'description'    => "varchar(250) NOT NULL DEFAULT ''",
                'image'          => 'varchar(300) DEFAULT NULL',
                'category_id'    => 'integer DEFAULT NULL'
            ],
            $this->getOptions()
        );

        //ix
        $this->createIndex("ux_{{groups_post}}_lang_slug", '{{groups_post}}', "slug,lang", true);
        $this->createIndex("ix_{{groups_post}}_group_id", '{{groups_post}}', "group_id", false);
        $this->createIndex("ix_{{groups_post}}_create_user_id", '{{groups_post}}', "create_user_id", false);
        $this->createIndex("ix_{{groups_post}}_update_user_id", '{{groups_post}}', "update_user_id", false);
        $this->createIndex("ix_{{groups_post}}_status", '{{groups_post}}', "status", false);
        $this->createIndex("ix_{{groups_post}}_access_type", '{{groups_post}}', "access_type", false);
        $this->createIndex("ix_{{groups_post}}_comment_status", '{{groups_post}}', "comment_status", false);
        $this->createIndex("ix_{{groups_post}}_lang", '{{groups_post}}', "lang", false);
        $this->createIndex("ix_{{groups_post}}_slug", '{{groups_post}}', "slug", false);
        $this->createIndex("ix_{{groups_post}}_publish_time", '{{groups_post}}', "publish_time", false);
        $this->createIndex("ix_{{groups_post}}_category_id", '{{groups_post}}', "category_id", false);

        //fks
        $this->addForeignKey(
            "fk_{{groups_post}}_group",
            '{{groups_post}}',
            'group_id',
            '{{groups}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{groups_post}}_create_user",
            '{{groups_post}}',
            'create_user_id',
            '{{user_user}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{groups_post}}_update_user",
            '{{groups_post}}',
            'update_user_id',
            '{{user_user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{groups_post}}_category_id",
            '{{groups_post}}',
            'category_id',
            '{{category_category}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );

        // user to group
        $this->createTable(
            '{{groups_members}}',
            [
                'id'          => "pk",
                'user_id'     => "integer NOT NULL",
                'group_id'    => "integer NOT NULL",
                'create_time' => "integer NOT NULL",
                'update_time' => "integer NOT NULL",
                'role'        => "integer NOT NULL DEFAULT '1'",
                'status'      => "integer NOT NULL DEFAULT '1'",
                'note'        => "varchar(250) NOT NULL DEFAULT ''",
            ],
            $this->getOptions()
        );

        //ix
        $this->createIndex(
            "ux_{{groups_members}}_user_group",
            '{{groups_members}}',
            "user_id,group_id",
            true
        );

        $this->createIndex(
            "ix_{{groups_members}}_user_id",
            '{{groups_members}}',
            "user_id",
            false
        );

        $this->createIndex(
            "ix_{{groups_members}}_group_id",
            '{{groups_members}}',
            "group_id",
            false
        );

        $this->createIndex(
            "ix_{{groups_members}}_status",
            '{{groups_members}}',
            "status",
            false
        );

        $this->createIndex("ix_{{groups_members}}_role", '{{groups_members}}', "role", false);

        //fk
        $this->addForeignKey(
            "fk_{{groups_members}}_groups_members_user_id",
            '{{groups_members}}',
            'user_id',
            '{{user_user}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{groups_members}}_groups_members_id",
            '{{groups_members}}',
            'group_id',
            '{{groups}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        // tags
        $this->createTable(
            '{{groups_tag}}',
            [
                'id'   => 'pk',
                'name' => 'varchar(255) NOT NULL',
            ],
            $this->getOptions()
        );

        //ix
        $this->createIndex("ux_{{groups_tag}}_tag_name", '{{groups_tag}}', "name", true);

        // post to tag
        $this->createTable(
            '{{groups_post_to_tag}}',
            [
                'post_id' => 'integer NOT NULL',
                'tag_id'  => 'integer NOT NULL',
                'PRIMARY KEY (post_id, tag_id)'
            ],
            $this->getOptions()
        );

        //ix
        $this->createIndex("ix_{{groups_post_to_tag}}_post_id", '{{groups_post_to_tag}}', "post_id", false);
        $this->createIndex("ix_{{groups_post_to_tag}}_tag_id", '{{groups_post_to_tag}}', "tag_id", false);

        //fk
        $this->addForeignKey(
            "fk_{{groups_post_to_tag}}_post_id",
            '{{groups_post_to_tag}}',
            'post_id',
            '{{groups_post}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{groups_post_to_tag}}_tag_id",
            '{{groups_post_to_tag}}',
            'tag_id',
            '{{groups_tag}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

    }

    /**
     * Удаляем талицы
     *
     * @return null
     **/
    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{groups_post_to_tag}}');
        $this->dropTableWithForeignKeys('{{groups_tag}}');
        $this->dropTableWithForeignKeys('{{groups_post}}');
        $this->dropTableWithForeignKeys('{{groups_members}}');
        $this->dropTableWithForeignKeys('{{groups}}');
    }
}
