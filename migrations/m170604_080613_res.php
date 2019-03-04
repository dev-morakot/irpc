<?php

use yii\db\Migration;

class m170604_080613_res extends Migration
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

        $this->createTable('res_doc_sequence', [
            'id' => $this->primaryKey(),
            'code'=>$this->string(64)->comment('refference name')->notNull()->unique(),
            'name' => $this->string(100)->comment('name'),
            'prefix' => $this->string(10)->comment('Prefix'),
            'date_format' => $this->string(10)->comment('date include in doc no (php format)'),
            'running_length' => $this->integer()->comment('Running number length'),
            'counter' => $this->integer()->comment("Current counter for document"),
            'type'=>$this->string(64)->comment('type'),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
                ], $this->tableOptions());
        $this->batchInsert('res_doc_sequence', ['id', 'code','name', 'prefix', 'date_format', 'running_length', 'counter','type'], [
            [1, 'it_doc_no','เลขเอกสาแจ้งซ่อม(IT)', 'IT', 'y', 5, 1,'help.request'],
            [2, 'as_doc_no','เลขเอกสารครุภัณฑ์(Asset)','AS','y',5,1,'asset.request'],
            [3,'re_doc_no','เลขเอกสารครุภัณฑ์(Return)','RE','y',5,1,'return.request'],
            
        ]);

        $this->createTable('res_doc_message', [
            'id'=>$this->bigPrimaryKey(),
            'name'=>$this->string(20)->comment('Doc Name'),
            'message'=>$this->text(),
            'ref_id'=>$this->integer()->comment('Reference ID'),
            'ref_model'=>$this->string(64)->comment('Reference Type [pr,po]'),
            //'prev_state'=>$this->string(50)->comment("Previouse document state"),
            //'to_state'=>$this->string(50)->comment("Current State"),
            'user_id'=>$this->integer()->comment("User"),
            //
            'company_id'=>$this->integer(),
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid' => $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ]);
        

    }

    public function down()
    {
        
        $this->dropTable('res_group_docseq_rel');
      
        $this->dropTable('res_doc_sequence');
       
        $this->dropTable('res_doc_message');
        
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
