<?php

use yii\db\Migration;
use yii\db\Schema;

class m170610_124856_request extends Migration
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
        $this->createTable('request',[
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'origin' =>$this->string(255)->comment("Source Document"),
            'sn_number' =>$this->string(255)->comment("หมายเลขคุรภัณฑ์"),
            'brand' => $this->string(255)->comment("รุ่น/ยี่ห่อ"),
            'problem' => $this->string(100)->comment('แจ้งปัญหา'),
            'building' => $this->string(255)->comment("แจ้งซ่อมอาคาร"),
            'officer' => $this->string(255)->comment("แจ้งเจ้าหน้าที่ธุรการ"),
            'other' => $this->string(255)->comment('อื่นๆ'),
            'description' =>$this->text()->comment("รายละเอียด"),
            'requested_by' => $this->integer()->comment('Requested By'),
            'state' =>$this->string(20)->comment("Status (wait,repair,close,clame,buy,cancel,endjob)"),
            'date_create' => $this->date()->comment('วันที่บันทึก'),
            'repair_id' => $this->integer()->comment('ผู้ดำเนินการซ่อม'),

            'builder1_id' => $this->integer()->comment('ผู้ซ่อมอาคาร'),
            'builder2_id' => $this->integer()->comment('ผู้ซ่อมอาคาร'),
            'builder3_id' => $this->integer()->comment('ผู้ซ่อมอาคาร'),
            'builder4_id' => $this->integer()->comment('ผู้ซ่อมอาคาร'),
            'officer_id' => $this->integer()->comment('บริการงานธุรการ'),

            'date_repair' => $this->date()->comment('วันที่ดำเนินการซ่อม'),

            'date_close' => $this->date()->comment('วันที่ปิดงานซ่อม'),
            'answer' => $this->text()->comment("ปัญหา/สาเหตุ"),
            'detail_work' => $this->text()->comment('รายละเอียดการแก้ปัญหา'),
            'close_id'=> $this->integer()->comment("ผู้ปิดงานซ่อม"),
            'detail_building' => $this->text()->comment("รายละเอียดบริการงานซ่อมอาคาร"),
            'detail_officer' => $this->text()->comment("รายละเอียดบริการงานธุรการ"),

            'date_comment' => $this->date()->comment('วันที่ประเมิน'),
            'comment_detail' => $this->text()->comment("ข้อเสนอแนะ"),
            'comment_state' => $this->string(50)->comment('สถานะประเมิน'),
            'date_end_job' => $this->date()->comment('วันที่จบรายการ'),

            'budget' => $this->string(255)->comment("งบประมาณ"),
            'date_clame' => $this->date()->comment("วันที่เคลม"),

            'date_approve' => $this->date()->comment('วันที่อนุมัติ'),
            'approver_note' => $this->text()->comment('หมายเหตุอน'),
            'date_cancel' => $this->date()->comment('วันที่ยกเลิก'),
            'note_cancel' => $this->text()->comment("หมายเหตุยกเลิก"),
            'cancel_id' => $this->text()->comment('ผู้ยกเลิก'),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ],$this->tableOptions());
    }

    public function down()
    {
       $this->dropTable('request');
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
