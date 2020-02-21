#DB定義
drop database if exists bbsabiru;
create database bbsabiru default charset utf8;

use bbsabiru;

#TABLE定義
create table messages (
    id int primary key auto_increment,
    user_name varchar(100),
    user_email varchar(100),
    main TEXT,
    created_at datetime
);

#初期データ投入
insert into messages(user_name, user_email, main, created_at) values('貞夫','sadao@gmail.com','投稿テスト１',now());
insert into messages(user_name, user_email, main, created_at) values('貞夫','sadao@gmail.com','投稿テスト２',now());

#アクセス権設定
grant all on bbsabiru.* to 'abiru'@'localhost' identified by 'root';