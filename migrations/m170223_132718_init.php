<?php

use yii\db\Migration;
use yii\db\Schema;

class m170223_132718_init extends Migration
{
    
    public function tableOptions(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }
    
    public function safeUp()
    {
        // User group
        $this->createTable('res_group',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(255)->comment('ฝ่าย'),
            'department'=>$this->string(255)->comment('แผนก'),
            
            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ],$this->tableOptions());
        
        $this->batchInsert('res_group', ['id','name','department'], [
            [1,'สายงานฝ่ายบริหาร','สายงานฝ่ายบริหาร'],
            [2,'ที่ปรึกษาและเจ้าหน้าที่สังกัดกรรมการผู้จัดการ','ที่ปรึกษาและเจ้าหน้าที่สังกัดกรรมการผู้จัดการ'],
            [3,'ฝ่ายบริหารสำนักงาน','แผนกบริหารทรัพยากรมนุษย์'],
            [4,'ฝ่ายบริหารสำนักงาน','แผนกบริหารทั่วไป'],
            [5,'ฝ่ายบริหารสำนักงาน','แผนกพัสดุและยานพาหนะ'],
            [6,'ฝ่ายบริหารสำนักงาน','แผนกการเงิน'],
            [7,'ฝ่ายบริหารสำนักงาน','แผนกบัญชี'],
            [8,'ฝ่ายบริหารสำนักงาน','แผนกจัดซื้อ'],
            [9,'ฝ่ายแผนงานและความร่วมมือ','แผนกแผนงานและยุทธศาสตร์องค์กร'],
            [10,'ฝ่ายแผนงานและความร่วมมือ','แผนกโครงการพิเศษและความร่วมมือ'],
            [11,'ฝ่ายแผนงานและความร่วมมือ','แผนกสื่อสารองค์กร'],
            [12,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาช่างยนต์'],
            [13,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาช่างกลโรงงาน'],
            [14,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาเคมีอุตสาหกรรม'],
            [15,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาช่างไฟฟ้า'],
            [16,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาช่างอิเล็กทรอนิกส์'],
            [17,'ฝ่ายวิชาการช่างอุตสาหกรรม','แผนกวิชาเทคโนโลยีสารสนเทศ'],
            [18,'ฝ่ายพาณิชยกรรม','แผนกวิชาคอมพิวเตอร์ธุรกิจ'],
            [19,'ฝ่ายพาณิชยกรรม','แผนกวิชาบัญชีและการขาย'],
            [20,'ฝ่ายพาณิชยกรรม','แผนกพื้นฐาน'],
            [21,'ฝ่ายพาณิชยกรรม','แผนกวิชาภาษาต่างประเทศ'],
            [22,'ฝ่ายกิจกรรมและพัฒนานักศึกษา','แผนกกิจกรรม'],
            [23,'ฝ่ายกิจกรรมและพัฒนานักศึกษา','แผนกพัฒนาวินัย'],
            [24,'ฝ่ายกิจกรรมและพัฒนานักศึกษา','แผนกแนะแนวและพยาบาล'],
            [25,'ฝ่ายมาตรฐานและคุณภาพการศึกษา','แผนกทะเบียนและหลักสูตร'],
            [26,'ฝ่ายมาตรฐานและคุณภาพการศึกษา','แผนกห้องสมุด'],
            [27,'ฝ่ายมาตรฐานและคุณภาพการศึกษา','แผนกประกันคุณภาพ'],
            [28,'ฝ่ายมาตรฐานและคุณภาพการศึกษา','แผนกพัฒนาระบบสารสนเทศ'],
            [29,'ฝ่ายศูนย์บริการเทคโนโลยีไออาร์พีซี','แผนกบริการฝึกอบรมมาตราฐานอาชีพและการจัดการ'],
            [30,'ฝ่ายศูนย์บริการเทคโนโลยีไออาร์พีซี','แผนกการตลาดและลูกค้าสัมพันธ์'],
            [31,'ฝ่ายศูนย์บริการเทคโนโลยีไออาร์พีซี','แผนกบริการศูนย์ฝึกอบรม'],
            [32,'ฝ่ายศูนย์บริการเทคโนโลยีไออาร์พีซี','แผนกสหการร้านค้า'],
            [33,'ฝ่ายพาณิชยกรรม','เลขานุการฝ่ายพาณิชยกรรม'],
            [34,'ฝ่ายศูนย์บริการเทคโนโลยีไออาร์พีซี','แผนกอาวุโสศูนย์บริการเทคโนโลยีไออาร์พีซี'],
        ]);

        // Resource User
        $this->createTable('res_users', [
            'id' => Schema::TYPE_PK,
            'username' => $this->string(50)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verifymail_token' => $this->string(50),
            'email' => $this->string(100)->notNull(),
            'code' => $this->string(30)->comment("รหัสพนักงาน"),
            'firstname' => $this->string(30),
            'lastname' =>$this->string(30),
            'tel' => $this->string(20),
            'active' => $this->boolean(),
            'company_id' => $this->integer(),
            'group_id' => $this->integer(),        
            'login_date' =>$this->dateTime()->comment("Login Date"),
            'img' => $this->string(255)->comment("รูปภาพประจำตัว"),
            'select_admin' => $this->boolean(),
            'select_help' => $this->boolean(),
            'select_asset' => $this->boolean(),
            'rule_admin' => $this->string(50)->comment('ระบบ แอดมิน'),
            'rule_help' => $this->string(50)->comment('แจ้งซ่อม'),
            'rule_asset' => $this->string(50)->comment('สินทรัพย์'),

            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp(), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp(), // Last Updated on
        ],$this->tableOptions());

        $password_hash = Yii::$app->security->generatePasswordHash('password');

        $this->batchInsert('res_users', ['id','username','auth_key','password_hash','email','firstname','lastname','create_uid','group_id'],[

            [1,'phothiwat.p@irpct.ac.th','',$password_hash,'phothiwat.p@irpct.ac.th','ดร.โพธิวัฒน์','เผ่าพงศ์ช่วง',1,1],
            [2,'montree.f@irpct.ac.th', '',$password_hash,'montree.f@irpct.ac.th','นายมนตรี','ฟ้าประทานชัย',1,1],
            [3,'wisit.k@irpct.ac.th','',$password_hash,'wisit.k@irpct.ac.th','นายวิสิทธิ์','คลังสิน',1,1],
            [4,'rungnirun.t@irpct.ac.th','',$password_hash,'rungnirun.t@irpct.ac.th','นายรุ่งนิรัญ','เที่ยงธรรม',1,1],
            [5,'kaitisak.b@irpct.ac.th','',$password_hash,'kaitisak.b@irpct.ac.th','นายเกียรติศักดิ์','บุญกล่อม',1,1],
            [6,'pichit.s@irpct.ac.th','',$password_hash,'pichit.s@irpct.ac.th','นายพิชิต','สาทิสรัตนโสภิต',1,1],
            [7,'chordsupa.s@irpct.ac.th','',$password_hash,'chordsupa.s@irpct.ac.th','ม.ล.โชติสุภา','สายสนั่น',1,1],
            [8,'chalermpun.y@irpct.ac.th','',$password_hash,'chalermpun.y@irpct.ac.th','นายเฉลิมพันธ์','ยศสมบัติ',1,1],
            [9,'narumit.p@irpct.ac.th','',$password_hash,'narumit.p@irpct.ac.th','นายนฤมิตร','ภักดี',1,1],
            [10,'prassamon.p@irpct.ac.th','',$password_hash,'prassamon.p@irpct.ac.th','นางปรัศมน','ภาภัทรมนตรี',1,1],
            [11,'yuttana.k@irpct.ac.th','',$password_hash,'yuttana.k@irpct.ac.th','นายยุทธนา','กลิ่นหอม',1,1],
            [12,'natthaporn.s@irpct.ac.th','',$password_hash,'natthaporn.s@irpct.ac.th','นางสาวนัฏฐพร','สงวนหงษ์',1,1],
            [13,'sombath@irpct.ac.th','', $password_hash,'sombath@irpct.ac.th','ดร.สมบัติ','สุวรรณพิทักษ์',1,2],
            [14,'oraping@irpct.ac.th','',$password_hash,'oraping@irpct.ac.th','นางอรพินธุ์','สว่างแจ้ง',1,2],
            [15,'chaninat.w@irpct.ac.th','',$password_hash,'chaninat.w@irpct.ac.th','นางสาวชนินาฎ','วัฒนา',1,2],
            [16,'chanisara.h@irpct.ac.th','',$password_hash,'chanisara.h@irpct.ac.th','นางสาวชนิสรา','หอมเกษร',1,2],
            [17,'janya.s@irpct.ac.th','',$password_hash,'janya.s@irpct.ac.th','นางสาวจรรยา','แซ่ก้วย',1,3],
            [18,'suwarin.t@irpct.ac.th','',$password_hash,'suwarin.t@irpct.ac.th','นางสาวสุวรินทร์','ทีปกร',1,3],
            [19,'chanthalak.t@irpct.ac.th','',$password_hash,'chanthalak.t@irpct.ac.th','นาวสาวจันทรลักษณ์','เตจ๊ะนัง',1,3],
            [20,'wanida.r@irpct.ac.th','',$password_hash,'wanida.r@irpct.ac.th','นางสาววนิดา','รอดเลี้ยง',1,3],
            [21,'poosit.s@irpct.ac.th','',$password_hash,'poosit.s@irpct.ac.th','นายภูสิทธิ์','สมสอางค์',1,4],
            [22,'audomsuk.p@irpct.ac.th','',$password_hash,'audomsuk.p@irpct.ac.th','นายอุดมสุข','ภมรคล',1,4],
            [23,'natapong.g@irpct.ac.th','',$password_hash,'natapong.g@irpct.ac.th','นายณัฐพงศ์','กลิ่นสุนทร',1,4],
            [24,'jakpat.p@irpct.ac.th','',$password_hash,'jakpat.p@irpct.ac.th','นายจักรภัทร','พูลฉันทกรณ์',1,4],
            [25,'thaksin.s@irpct.ac.th','',$password_hash,'thaksin.s@irpct.ac.th','นายทักษิณ','ศรีสุทน',1,4],
            [26,'rattana.p@irpct.ac.th','',$password_hash,'rattana.p@irpct.ac.th','นายรัตนะ','ปะทานัง',1,4],
            [27,'wipa.w@irpct.ac.th','',$password_hash,'wipa.w@irpct.ac.th','นางวิภา','หวานระรื่น',1,4],
            [28,'souwapa.c@irpct.ac.th','',$password_hash,'souwapa.c@irpct.ac.th','นางเสาวภา','เชื้อบุญมี',1,5],
            [29,'wipawee.w@irpct.ac.th','',$password_hash,'wipawee.w@irpct.ac.th','นางสาววิภาวี','วงศ์แวว',1,5],
            [30,'somwong.m@irpct.ac.th','',$password_hash,'somwong.m@irpct.ac.th','นายสมวงษ์','หมั่นดี',1,5],
            [31,'samad.w@irpct.ac.th','',$password_hash,'samad.w@irpct.ac.th','นายสามารถ','ไวยศิลป์',1,5],
            [32,'wantanee.h@irpct.ac.th','',$password_hash,'wantanee.h@irpct.ac.th','นางวรรทนี','หอมชู',1,6],
            [33,'tirada.r@irpct.ac.th','',$password_hash,'tirada.r@irpct.ac.th','นางสาวถิรดา','รัตนะ',1,6],
            [34,'mattree.k@irpct.ac.th','',$password_hash,'mattree.k@irpct.ac.th','นางสาวมัทรี','คุ้มเสือ',1,7],
            [35,'napawan.s@irpct.ac.th','',$password_hash,'napawan.s@irpct.ac.th','นางนภาวรรณ','สกุลพิทักษ์',1,7],
            [36,'marisa.p@irpct.ac.th','',$password_hash,'marisa.p@irpct.ac.th','นางสาวมาริสา','แป้นเหมือน',1,7],
            [37,'suriyo.w@irpct.ac.th','',$password_hash,'suriyo.w@irpct.ac.th','นายสุริโย','วดีศิริศักดิ์',1,8],
            [38,'bangon.s@irpct.ac.th','',$password_hash,'bangon.s@irpct.ac.th','นางสาวบังอร','สิงห์สุวรรณ',1,8],
            [39,'reerat.c@irpct.ac.th','',$password_hash,'reerat.c@irpct.ac.th','นางสาวรีรัต','ชูพิชัย',1,9],
            [40,'gritiyapon.g@irpct.ac.th','',$password_hash,'gritiyapon.g@irpct.ac.th','นางสาวกฤติยาภรณ์','แก้วดอนรี',1,9],
            [41,'supapon.p@irpct.ac.th','',$password_hash,'supapon.p@irpct.ac.th','นางสาวศุภพร','พัวพันประสงค์',1,10],
            [42,'yuwadee.j@irpct.ac.th','',$password_hash,'yuwadee.j@irpct.ac.th','นางสาวยุวดี','จันทร์เอียด',1,10],
            [43,'katipot.j@irpct.ac.th','',$password_hash,'katipot.j@irpct.ac.th','นายคติพจน์','จินดาวงศ์',1,11],
            [44,'yuttayong.g@irpct.ac.th','',$password_hash,'yuttayong.g@irpct.ac.th','นายยุทธยง','แก่กล้า',1,11],
            [45,'wichaya.t@irpct.ac.th','',$password_hash,'wichaya.t@irpct.ac.th','นายวิชชยา','ทองเทศ',1,11],
            [46,'rattana.c@irpct.ac.th','',$password_hash,'rattana.c@irpct.ac.th','นางสาวรัตนา','เฉลยรักษ์',1,11],
            [47,'kanitta.g@irpct.ac.th','',$password_hash,'kanitta.g@irpct.ac.th','นางสาวขนิษฐา','แก้วกำไกร',1,12],
            [48,'wongkit.i@irpct.ac.th','',$password_hash,'wongkit.i@irpct.ac.th','นายวงศ์กฤต','อินเอี่ยม',1,12],
            [49,'pinyo.t@irpct.ac.th','',$password_hash,'pinyo.t@irpct.ac.th','นายภิญโญ','ทองดารา',1,12],
            [50,'chanchai.n@irpct.ac.th','',$password_hash,'chanchai.n@irpct.ac.th','นายชาญชัย','แนวประเสริฐ',1,12],
            [51,'arcom@irpct.ac.th','',$password_hash,'arcom@irpct.ac.th','นายอาคม','ศรีเทพ',1,12],
            [52,'tawatchai.p@irpct.ac.th','',$password_hash,'tawatchai.p@irpct.ac.th','นายธวัชชัย','ปุ้ยสิน',1,12],
            [53,'chatwan.m@irpct.ac.th','',$password_hash,'chatwan.m@irpct.ac.th','นายชัชวาลย์','มั่นคง',1,12],
            [54,'jaroon.c@irpct.ac.th','',$password_hash,'jaroon.c@irpct.ac.th','นายจรูญ','ชาติไทยเจริญ',1,12],
            [55,'tatree.f@irpct.ac.th','',$password_hash,'tatree.f@irpct.ac.th','นายธาตรี','ฟ้าประทานชัย',1,12],
            [56,'kimhan.s@irpct.ac.th','',$password_hash,'kimhan.s@irpct.ac.th','นายคิมหันต์','สังข์สุวรรณ',1,12],
            [57,'piyasak.w@irpct.ac.th','',$password_hash,'piyasak.w@irpct.ac.th','นายปิยศักดิ์','วรรณโสภา',1,12],
            [58,'thosspron.y@irpct.ac.th','',$password_hash,'thosspron.y@irpct.ac.th','นายทศพร','แย้มแสงทอง',1,12],
            [59,'chairak.s@irpct.ac.th','',$password_hash,'chairak.s@irpct.ac.th','นายชัยรักษ์','สมงาม',1,12],
            [60,'wiganda.g@irpct.ac.th','',$password_hash,'wiganda.g@irpct.ac.th','นางสาววิกานดา','เกตสอาด',1,12],
            [61,'chuchat.s@irpct.ac.th','',$password_hash,'chuchat.s@irpct.ac.th','นายชูฉัตร','สมานสินธ์',1,12],
            [62,'arun.s@irpct.ac.th','',$password_hash,'arun.s@irpct.ac.th','นายอรุณ','ศรีสกุล',1,13],
            [63,'panuwat.w@irpct.ac.th','',$password_hash,'panuwat.w@irpct.ac.th','นายภาณุวัฒน์','ว่องไว',1,13],
            [64,'pongwet.j@irpct.ac.th','',$password_hash,'pongwet.j@irpct.ac.th','นายสุชาติ','จิตตาศิรินุวัตร',1,13],
            [65,'rapin.s@irpct.ac.th','',$password_hash,'rapin.s@irpct.ac.th','นายระพิน','แสงสุด',1,13],
            [66,'sampan.d@irpct.ac.th','',$password_hash,'sampan.d@irpct.ac.th','นายสัมพันธ์','ดอนเมฆ',1,13],
            [67,'pimuk.k@irpct.ac.th','',$password_hash,'pimuk.k@irpct.ac.th','นายพิมุข','กล้าหาญ',1,13],
            [68,'nirut.s@irpct.ac.th','',$password_hash,'nirut.s@irpct.ac.th','นายนิรุต','สถาพร',1,13],
            [69,'napagan.s@irpct.ac.th','',$password_hash,'napagan.s@irpct.ac.th','นายนภกานค์','ยิ่งสังข์',1,13],
            [70,'piyabhorn.s@irpct.ac.th','',$password_hash,'piyabhorn.s@irpct.ac.th','นางสาวปิยะพร','สุขประเสริฐ',1,13],
            [71,'mahittanupap.t@irpct.ac.th','',$password_hash,'mahittanupap.t@irpct.ac.th','นายมหิทธานุภาพ','โตแก้ว',1,13],
            [72,'rukthai.p@irpct.ac.th','',$password_hash,'rukthai.p@irpct.ac.th','นายรักษ์ไทน์','พันกาฬสินธ์',1,13],
            [73,'aungmyo@irpct.ac.th','',$password_hash,'aungmyo@irpct.ac.th','Mr.Aung','Myo',1,13],
            [74,'hansoe@irpct.ac.th','',$password_hash,'hansoe@irpct.ac.th','Mr.Han','Soe',1,13],
            [75,'wasan.b@irpct.ac.th','',$password_hash,'wasan.b@irpct.ac.th','นายวสันต์','บุญเทพ',1,13],
            [76,'rattana.l@irpct.ac.th','',$password_hash,'rattana.l@irpct.ac.th','นางรัตนา','ลานทอง',1,14],
            [77,'nonthawan.y@irpct.ac.th','',$password_hash,'nonthawan.y@irpct.ac.th','นางนนทวัน','ยศสมบัติ',1,14],
            [78,'patamawan.s@irpct.ac.th','',$password_hash,'patamawan.s@irpct.ac.th','นางสาวปัทมาวรรณ','แสวงผล',1,14],
            [79,'suwapap.a@irpct.ac.th','',$password_hash,'suwapap.a@irpct.ac.th','นางสาวสุวภาพ','อาริยะกุล',1,14],
            [80,'nongnut.p@irpct.ac.th','',$password_hash,'nongnut.p@irpct.ac.th','นางสาวนงนุช','พรมรงค์',1,14],
            [81,'surin.n@irpct.ac.th','',$password_hash,'surin.n@irpct.ac.th','นายสุรินทร์','นวลฉาย',1,15],
            [82,'jakkapan.p@irpct.ac.th','',$password_hash,'jakkapan.p@irpct.ac.th','นายจักรพันธ์','ปาระโมงค์',1,15],
            [83,'wongsagon.n@irpct.ac.th','',$password_hash,'wongsagon.n@irpct.ac.th','นายวงศกร','แน่นหนา',1,15],
            [84,'narongsak.s@irpct.ac.th','',$password_hash,'narongsak.s@irpct.ac.th','นายณรงค์ศักดิ์','ศิริมาสกุล',1,15],
            [85,'gamonwan.r@irpct.ac.th','',$password_hash,'gamonwan.r@irpct.ac.th','นางสาวกมลวรรณ','เรืองรัตน์สุนทร',1,15],
            [86,'srapun.s@irpct.ac.th','',$password_hash,'srapun.s@irpct.ac.th','นายศราพันธ์','สัญญาทิตย์',1,15],
            [87,'sirilak.r@irpct.ac.th','',$password_hash,'sirilak.r@irpct.ac.th','นางสาวศิริลักษณ์','เรี่ยมทอง',1,15],
            [88,'tanakrit.a@irpct.ac.th','',$password_hash,'tanakrit.a@irpct.ac.th','นายธนกฤต','อมรทัศนสุข',1,16],
            [89,'supatchakarn.p@irpct.ac.th','',$password_hash,'supatchakarn.p@irpct.ac.th','นางสาวสุภัทชกาญจน์','ภาเรือง',1,16],
            [90,'thongchai.j@irpct.ac.th','',$password_hash,'thongchai.j@irpct.ac.th','นายธงชัย','เจริญศุข',1,16],
            [91,'atipong.o@irpct.ac.th','',$password_hash,'atipong.o@irpct.ac.th','นายอธิพงษ์','อ่อนโสภาพร',1,16],
            [92,'akarawin.k@irpct.ac.th','',$password_hash,'akarawin.k@irpct.ac.th','นายอัครวินท์','กิติอาษา',1,16],
            [93,'pornprom.n@irpct.ac.th','',$password_hash,'pornprom.n@irpct.ac.th','นางสาวพรพรหม','นิสัยมั่น',1,16],
            [94,'thanasak.s@irpct.ac.th','',$password_hash,'thanasak.s@irpct.ac.th','นายธนาศักดิ์','สิ้นเคราะห์',1,17],
            [95,'arut.m@irpct.ac.th','',$password_hash,'arut.m@irpct.ac.th','นายอรุษ','มาเพ็ชร์',1,17],
            [96,'jittawan.r@irpct.ac.th','',$password_hash,'jittawan.r@irpct.ac.th','นางสาวจิตราวัลย์','รัตนจิราภิวัฒน์',1,17],
            [97,'atcharapon.s@irpct.ac.th','',$password_hash,'atcharapon.s@irpct.ac.th','นางสาวอัจฉราพร','สุนะไตร',1,33],
            [98,'sumalee.y@irpct.ac.th','',$password_hash,'sumalee.y@irpct.ac.th','นางสาวสุมาลี','ยืนยงนาวิน',1,18],
            [99,'wilaiwan.n@irpct.ac.th','',$password_hash,'wilaiwan.n@irpct.ac.th','นางสาววิไลวรรณ','นวลละออง',1,18],
            [100,'banpot.n@irpct.ac.th','',$password_hash,'banpot.n@irpct.ac.th','นายบรรพต','นิลพาณิชย์',1,18],
            [101,'thitirat.c@irpct.ac.th','',$password_hash,'thitirat.c@irpct.ac.th','นางสาวฐิติรัตน์','คำศิริรักษ์',1,18],
            [102,'suthathip.p@irpct.ac.th','',$password_hash,'suthathip.p@irpct.ac.th','นางสาวสุธาทิพย์','คะหาญ',1,18],
            [103,'nattinan.n@irpct.ac.th','',$password_hash,'nattinan.n@irpct.ac.th','นางสาวณัฐฐินันท์','นวลปิด',1,18],
            [104,'wanna.k@irpct.ac.th','',$password_hash,'wanna.k@irpct.ac.th','นางสาววรรณา','คิดละเอียด',1,19],
            [105,'pinyapat.l@irpct.ac.th','',$password_hash,'pinyapat.l@irpct.ac.th','นางสาวภิญญาพัชญ์','ไล่เสีย',1,19],
            [106,'tipwan.s@irpct.ac.th','',$password_hash,'tipwan.s@irpct.ac.th','น..ส .ทิพวรรณ','สืบแสง',1,19],
            [107,'ornipa.s@irpct.ac.th','',$password_hash,'ornipa.s@irpct.ac.th','ว่าที่ร.ต.หญิงอรนิภา','ศิริบุตร',1,19],
            [108,'paruemol.i@irpct.ac.th','',$password_hash,'paruemol.i@irpct.ac.th','นางสาวปฤมล','อินทวงศ์',1,19],
            [109,'nilobon.n@irpct.ac.th','',$password_hash,'nilobon.n@irpct.ac.th','นางสาวนิโลบล','นนทะโคตร',1,19],
            [110,'orsuda.s@irpct.ac.th','',$password_hash,'orsuda.s@irpct.ac.th','นางสาวอรสุดา','สุขสวัสดิ์',1,19],
            [111,'wadee.m@irpct.ac.th','',$password_hash,'wadee.m@irpct.ac.th','นางสาววดี','มณีอรุณ',1,19],
            [112,'jittima.k@irpct.ac.th','',$password_hash,'jittima.k@irpct.ac.th','นางจิตติมา','แก้วทันคำ',1,20],
            [113,'natda.p@irpct.ac.th','',$password_hash,'natda.p@irpct.ac.th','นางนัดดา','พาสนาโสภณ',1,20],
            [114,'pramreudee.a@irpct.ac.th','',$password_hash,'pramreudee.a@irpct.ac.th','นางสาวเปรมฤดี','อุปลับ',1,20],
            [115,'gutarin.g@irpct.ac.th','',$password_hash,'gutarin.g@irpct.ac.th','นางสาวกุลธารินท์','เกิดมณี',1,20],
            [116,'yaowarat.n@irpct.ac.th','',$password_hash,'yaowarat.n@irpct.ac.th','นางเยาวรัตน์','นพศิริ',1,20],
            [117,'pikanate.s@irpct.ac.th','',$password_hash,'pikanate.s@irpct.ac.th','นายพิฆเณศวร์','สนกนกพรชัย',1,20],
            [118,'sujitra.t@irpct.ac.th','',$password_hash,'sujitra.t@irpct.ac.th','นางสาวสุจิตรา','ทาขุลี',1,20],
            [119,'chatree.t@irpct.ac.th','',$password_hash,'chatree.t@irpct.ac.th','นายชาตรี','ต่างสมปอง',1,21],
            [120,'siyamon.s@irpct.ac.th','',$password_hash,'siyamon.s@irpct.ac.th','นางสาวสิยามล','ศักดิ์เจริญ',1,21],
            [121,'preutiton.s@irpct.ac.th','',$password_hash,'preutiton.s@irpct.ac.th','นายพฤฒินทร','ศุภดิเรกกุล',1,21],
            [122,'nong.q@irpct.ac.th','',$password_hash,'nong.q@irpct.ac.th','MissNong','Qiumei',1,21],
            [123,'nichapat.k@irpct.ac.th','',$password_hash,'nichapat.k@irpct.ac.th','นางสาวณิชาภัทร','คำอินตา',1,21],
            [124,'chinebeth.b@irpct.ac.th','',$password_hash,'chinebeth.b@irpct.ac.th','MissChinebeth','Borja',1,21],
            [125,'wirat.s@irpct.ac.th','',$password_hash,'wirat.s@irpct.ac.th','นายวิรัตน์','สนศิริ',1,22],
            [126,'suksan.w@irpct.ac.th','',$password_hash,'suksan.w@irpct.ac.th','นายสุขสันต','วัฒนสุนทร',1,22],
            [127,'pataya.p@irpct.ac.th','',$password_hash,'pataya.p@irpct.ac.th','นางสาวพัทยา','ภาเรือง',1,22],
            [128,'tongyun.j@irpct.ac.th','',$password_hash,'tongyun.j@irpct.ac.th','นางทองยุ่น','จัดแจง',1,22],
            [129,'vithoon_n@irpct.ac.th','',$password_hash,'vithoon_n@irpct.ac.th','นายวิฑูรย์','ง่วนสน',1,23],
            [130,'phetcharat.t@irpct.ac.th','',$password_hash,'phetcharat.t@irpct.ac.th','นางเพ็ชรรัตน์','ทับแสงสี',1,23],
            [131,'pramuk.m@irpct.ac.th','',$password_hash,'pramuk.m@irpct.ac.th','นายประมุข','ม่วงจีบ',1,23],
            [132,'orapan.s@irpct.ac.th','',$password_hash,'orapan.s@irpct.ac.th','นางสาวอรพรรณ','สารทอง',1,23],
            [133,'utumpon.s@irpct.ac.th','',$password_hash,'utumpon.s@irpct.ac.th','นางสาวอุทุมพร','สุภัคกุล',1,24],
            [134,'wadsana.a@irpct.ac.th','',$password_hash,'wadsana.a@irpct.ac.th','นางสาววาสนา','อุปแก้ว',1,24],
            [135,'patthanan.w@irpct.ac.th','',$password_hash,'patthanan.w@irpct.ac.th','นางพัทธนันท์','วาสะสิริ',1,24],
            [136,'pimon.b@irpct.ac.th','',$password_hash,'pimon.b@irpct.ac.th','นายพิมล','บังเกิดสุข',1,25],
            [137,'nuchanart.u@irpct.ac.th','',$password_hash,'nuchanart.u@irpct.ac.th','นางนุชนาจ','อุปชิน',1,25],
            [138,'wanwisa.s@irpct.ac.th','',$password_hash,'wanwisa.s@irpct.ac.th','นางวันวิสา','แสงสุด',1,25],
            [139,'sudarat.d@irpct.ac.th','',$password_hash,'sudarat.d@irpct.ac.th','นางสาวสุดารัตน์','ดวงผุยทอง',1,25],
            [140,'jantra.s@irpct.ac.th','',$password_hash,'jantra.s@irpct.ac.th','น.ส จันทรา','สุขสมกิจ',1,25],
            [141,'gulisara.k@irpct.ac.th','',$password_hash,'gulisara.k@irpct.ac.th','นางสาวกุลิสรา','ใคร่ครวญ',1,25],
            [142,'praweena.s@irpct.ac.th','',$password_hash,'praweena.s@irpct.ac.th','นางประวีณา','สุภาสืบ',1,26],
            [143,'pimgaew.p@irpct.ac.th','',$password_hash,'pimgaew.p@irpct.ac.th','นางสาวพิมพ์แก้ว','ปราศราคี',1,26],
            [144,'patigon.s@irpct.ac.th','',$password_hash,'patigon.s@irpct.ac.th','นายผาติกร','สิงห์สูง',1,26],
            [145,'panida.d@irpct.ac.th','',$password_hash,'panida.d@irpct.ac.th','นางพนิดา','ดอนเมฆ',1,27],
            [146,'damrongrat.k@irpct.ac.th','',$password_hash,'damrongrat.k@irpct.ac.th','นายดำรงค์รัตน์','ขันธ์คู่',1,27],
            [147,'tanapun.f@irpct.ac.th','',$password_hash,'tanapun.f@irpct.ac.th','นายธนาพันธ์','ฟ้าประทานชัย',1,28],
            [148,'apichart.k@irpct.ac.th','',$password_hash,'apichart.k@irpct.ac.th','นายอภิชาติ','เคนบุปผา',1,28],
            [149,'santi.k@irpct.ac.th','',$password_hash,'santi.k@irpct.ac.th','นายสันติ','คำสุข',1,28],
            [150,'pakkamon.r@irpct.ac.th','',$password_hash,'pakkamon.r@irpct.ac.th','นางสาวภคมณ','รุ่งโรจน์',1,34],
            [151,'mananchaya.j@irpct.ac.th','',$password_hash,'mananchaya.j@irpct.ac.th','นางสาว มนัญชยา','จันทรังษี',1,29],
            [152,'tachinee.c@irpct.ac.th','',$password_hash,'tachinee.c@irpct.ac.th','นางสาวเตชินี','ชัยงาม',1,29],
            [153,'phatra.w@irpct.ac.th','',$password_hash,'phatra.w@irpct.ac.th','นางสาวภัทรา','วราพุฒ',1,29],
            [154,'sarayut.a@irpct.ac.th','',$password_hash,'sarayut.a@irpct.ac.th','นายศรายุทธ','เอกพจน์',1,29],
            [155,'chawalit.r@irpct.ac.th','',$password_hash,'chawalit.r@irpct.ac.th','นายชวลิต','โรจนพงศ์ธาดา',1,29],
            [156,'phatcharapon.s@irpct.ac.th','',$password_hash,'phatcharapon.s@irpct.ac.th','นางสาวพัชราภรณ์','สุริยา',1,29],
            [157,'benyapa.p@irpct.ac.th','',$password_hash,'benyapa.p@irpct.ac.th','นางสาวเบญญาภา','พันมาลา',1,30],
            [158,'nong.d@irpct.ac.th','',$password_hash,'nong.d@irpct.ac.th','นางสาวนงค์','ดาศรี',1,30],
            [159,'somkuan.n@irpct.ac.th','',$password_hash,'somkuan.n@irpct.ac.th','นายสมควร','นาคเสน่ห์',1,30],
            [160,'supanee.l@irpct.ac.th','',$password_hash,'supanee.l@irpct.ac.th','นางสุภานี','ลาชโรจน์',1,31],
            [161,'yuttapong.t@irpct.ac.th','',$password_hash,'yuttapong.t@irpct.ac.th','นายยุทธพงษ์','ทิพย์เครือ',1,31],
            [162,'tippawan.s@irpct.ac.th','',$password_hash,'tippawan.s@irpct.ac.th','นางสาวทิพวัลย์','สงกล่ำ',1,31],
            [163,'sayan.j@irpct.ac.th','',$password_hash,'sayan.j@irpct.ac.th','นางสายัณห์','จริญวัย',1,31],
            [164,'anong.m@irpct.ac.th','',$password_hash,'anong.m@irpct.ac.th','นางอนงค์','มีบุญสบาย',1,31],
            [165,'sudarat.p@irpct.ac.th','',$password_hash,'sudarat.p@irpct.ac.th','นางสาวสุดารัตน์','ปรางศรี',1,31],
            [166,'tanyapat.n@irpct.ac.th','',$password_hash,'tanyapat.n@irpct.ac.th','น.ส. ธันยพัฒน์','นิธิชนกนันท์',1,31],
            [167,'pakjira.w@irpct.ac.th','',$password_hash,'pakjira.w@irpct.ac.th','นางสาวภัคจิรา','หวังเศษกลาง',1,31],
            [168,'kunanya.y@irpct.ac.th','',$password_hash,'kunanya.y@irpct.ac.th','นางคุณัญญา','ฟ้าประทานชัย',1,32],
            [169,'yaowalak.m@irpct.ac.th','',$password_hash,'yaowalak.m@irpct.ac.th','นางสาวเยาวลักษณ์','มณีเลิศ',1,32],
            [170,'admin@admin.com','',$password_hash,'admin@admin.com','Mr.Admin','IRPCTGROUP',1,1],
        ]);

      $this->createTable('res_users_group_rel',[
            'user_id' => $this->integer()->comment("comment res_users"),
            'group_id'=> $this->integer()->comment('res_groups')
        ]);
        $this->addForeignKey('fk-res_users_group_rel-user_id','res_users_group_rel','user_id','res_users','id','CASCADE');
        $this->addForeignKey('fk-res_users_group_rel-group_id','res_users_group_rel','group_id','res_group','id','CASCADE');
        $this->batchInsert('res_users_group_rel', ['user_id','group_id'], [
            [1,5],
            [1,6],
            [1,18],
            [11,6], //วิศวกรรม
            [12,5], // employee4,จัดซื้อ
            [12,6], // employee4,วิศวกรรม
        ]);
        
    }

    public function safeDown()
    {
      $this->dropForeignKey('fk-res_users_group_rel-user_id', 'res_users_group_rel');
      $this->dropForeignKey('fk-res_users_group_rel-group_id', 'res_users_group_rel');
      $this->dropForeignKey('fk-res_users-group_id', 'res_users');
      $this->dropTable('res_users');      
      $this->dropTable('res_group');
      $this->dropTable('res_users_group_rel');
    }
}