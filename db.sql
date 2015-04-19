ALTER TABLE marathon_events_users add column event_id int references marathon_events(id);
ALTER TABLE marathon_events_users ADD CONSTRAINT event_id_user_id UNIQUE (event_id, user_id);
alter table `marathon_events_users` modify event_id int not null;
alter table marathon_events add column ordering int not null UNIQUE;

create table marathon_messages
(
    id int not null auto_increment PRIMARY KEY,
    code varchar(255) not null,
    lang varchar(2) not null,
    title text,
    data text not null,
    UNIQUE (code, lang)
    );

insert into `marathon_messages` (code, lang, title, data) values ('registration_email_confirmation', 'en', 'Successfull Registration', 'Your registration for {EVENTNAMES} is succesful.');
insert into `marathon_messages` (code, lang, title, data) values ('registration_email_confirmation', 'bg', 'Успешна регистрация', 'Вие се регистрирахте успешно за {EVENTNAMES}.');
insert into `marathon_messages` (code, lang, title, data) values ('registration_confirmation', 'en', 'Successfull Registration', 'Your registration for {EVENTNAMES} is succesful.');
insert into `marathon_messages` (code, lang, title, data) values ('registration_confirmation', 'bg', 'Успешна регистрация', 'Вие се регистрирахте успешно за {EVENTNAMES}.');
insert into marathon_messages (code, lang, title, data) values ('register_account_email', 'bg', 'Данни за акаунт', 'Потребителско име: {EMAIL}\nПарола: {PASS}\nАко искате да смените паролата си натиснете тук: {PASS_RESET_LINK}');
insert into marathon_messages (code, lang, title, data) values ('register_account_email', 'en', 'Information for account', 'User: {EMAIL}\nPassword: {PASS}\nIf you want to change you password click here: {PASS_RESET_LINK}');
alter table `marathon_events_users` change online_payment payment bool not null default false;

alter table `marathon_events_distances` modify event_id varchar(100);

ALTER table `marathon_events_distances` add foreign key (event_id) references marathon_events(unique_id);
ALTER table `marathon_events_distances` add column name_en text not null default '';
ALTER table `marathon_events_distances` change name name_bg text not null default ''; 
ALTER table `marathon_events` change name name_bg text not null;
ALTER table `marathon_events` add column name_en text ;
ALTER table `marathon_events` modify column name_en text not null;
alter table marathon_events_distances add column ordering int not null;

create table marathon_events_prices
(
    id int not null AUTO_INCREMENT PRIMARY KEY,
    count int not null UNIQUE,
    price numeric not null 
);

alter table marathon_events_users add column online_payment bool not null default false;


create table marathon_events_users_group_seq
(
    id int AUTO_INCREMENT NOT NULL PRIMARY KEY    
);

alter table `marathon_events_users` add column group_id int not null;

create table organizations
(
    id int not null AUTO_INCREMENT PRIMARY KEY,
 lter table marathon_events_prices add column event_discipline_id int , add foreign key event_distances_fk(event_discipline_id) references marathon_event_distances(id);
   name varchar(100) not null UNIQUE
);

alter table marathon_events add column organization_id int;
ALTER table `marathon_events` add foreign key (organization_id) references organizations(id);
update marathon_events set organization_id = 1 where organization_id is null;
alter table marathon_events modify column organization_id int not null;
alter table marathon_events_prices add column organization_id int not null default 1, add foreign key organization_id_fk(organization_id) references organizations(id);
alter table marathon_events_prices add column event_discipline_id int , add foreign key event_distances_fk(event_discipline_id) references marathon_events_distances(id);

create table payments
(
    id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    payment_id int not null,
    organization_id int not null,
    value numeric,
    created_at timestamp not null default CURRENT_TIMESTAMP,
    foreign key (organization_id) references organizations(id)
);
