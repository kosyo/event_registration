ALTER TABLE marathon_events_users add column event_id int references marathon_events(id);
ALTER TABLE marathon_events_users ADD CONSTRAINT event_id_user_id UNIQUE (event_id, user_id);
alter table `marathon_events_users` modify event_id int not null;
