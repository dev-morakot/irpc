<?php

use yii\db\Migration;

class m170605_141214_asset_config extends Migration
{

    public function tableOptions() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    public function up()
    {
        $this->createTable('asset_categories', [
            'id' => $this->primaryKey(),
            'code'=>$this->string(100)->comment('code'),
            'name' => $this->string(255)->comment('name'),
            'remark' => $this->text()->comment("หมายเหตุ"),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ], $this->tableOptions());

        $this->createTable('asset_unit', [
            'id' => $this->primaryKey(),            
            'name' => $this->string(255)->comment('name'),
            
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ], $this->tableOptions());

        $this->createTable('asset_location', [
            'id' => $this->primaryKey(),            
            'name' => $this->string(255)->comment('name'),
            
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ], $this->tableOptions());

        $this->createTable('asset_asset', [
            'id' => $this->primaryKey(),
            'categories_id' => $this->integer()->comment("ประเภทสินทรัพย์"),
            'certificate' => $this->string()->comment('เลขที่ใบรับรอง'),
            'name' => $this->string()->comment('หมายเลขครุภัณฑ์'),
            'description' => $this->string()->comment('รายการ'),
            'notes' => $this->text()->comment("หมายเหตุ"),
            'qty' => $this->float()->comment("จำนวน"),
            'unit_id' => $this->integer()->comment("หน่วย"),
            
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ], $this->tableOptions());

        $this->createTable('asset_order', [
            'id' => $this->primaryKey(),
            'date_order' => $this->date()->comment("วันที่สร้าง"),
            'date_approve' => $this->date()->comment("วันที่อนุมัติ"),
            'date_cancel' => $this->date()->comment("วันที่ยกเลิก"),
            'state' => $this->string()->comment("สถานะ [wait, approve, cancel]"),
            'name' => $this->string()->comment('เลขเอกสาร Doc'),
            'full_name' => $this->string()->comment("ชื่อผู้ขอเบิก"),
            'group_id' => $this->integer()->comment("ฝ่าย/แผนก"),
            'notes' => $this->text()->comment("หมายเหตุ"),
            'location_id' => $this->integer()->comment("สถานที่ ห้อง"),
            'approver_id' => $this->integer()->comment("ผู้อนุมัติ"),
            'cancel_id' => $this->integer()->comment("ผู้ยกเลิก"),
            'create_id' => $this->integer()->comment("ผู้ทำรายการ"),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on

        ],$this->tableOptions());

        $this->createTable("asset_order_line",[
            'id' => $this->primaryKey(),
            'asset_id' => $this->integer()->comment("ตาราง ทะเบียนครุภัฑณ์"),
            'order_id' => $this->integer()->comment("ตาราง asset order"),
            'qty' => $this->float()->comment("จำนวนที่เบิก"),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ],$this->tableOptions());
        $this->addForeignKey('fk-asset_order_line-order_id','asset_order_line','order_id','asset_order','id','CASCADE');


        // return 
        $this->createTable('return_order', [
            'id' => $this->primaryKey(),
            'date_order' => $this->date()->comment("วันที่สร้าง"),
            'date_approve' => $this->date()->comment("วันที่อนุมัติ"),
            'date_cancel' => $this->date()->comment("วันที่ยกเลิก"),
            'state' => $this->string()->comment("สถานะ [wait, approve, cancel]"),
            'name' => $this->string()->comment('เลขเอกสาร'),
            'full_name' => this->string()->comment('ชื่อผู้ขอคืน'),
            'group_id' => $this->integer()->comment("ฝ่าย/แผนก"),
            'notes' => $this->text()->comment("หมายเหตุ"),
            'location_id' => $this->integer()->comment("สถานที่ ห้อง"),
            'approver_id' => $this->integer()->comment("ผู้อนุมัติ"),
            'cancel_id' => $this->integer()->comment("ผู้ยกเลิก"),
            'create_id' => $this->integer()->comment("ผู้ทำรายการ"),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on

        ],$this->tableOptions());

        $this->createTable("return_order_line",[
            'id' => $this->primaryKey(),
            'asset_id' => $this->integer()->comment("ตาราง ทะเบียนครุภัฑณ์"),
            'order_id' => $this->integer()->comment("ตาราง asset order"),
            'qty' => $this->float()->comment("จำนวนที่คืน"),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ],$this->tableOptions());
        $this->addForeignKey('fk-return_order_line-order_id','return_order_line','order_id','return_order','id','CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-asset_order_line-order_id', 'asset_order_line');
        $this->dropForeignKey('fk-return_order_line-order_id', 'return_order_line');
        $this->dropTable('asset_categories');
        $this->dropTable('asset_unit');
        $this->dropTable('asset_location');
        $this->dropTable('asset_asset');
        $this->dropTable('asset_order');
        $this->dropTable('asset_order_line');
        $this->dropTable('return_order');
        $this->dropTable('return_order_line');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
