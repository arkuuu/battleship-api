create database `battleship-api` collate utf8mb4_unicode_ci;

create table game (
    id    int unsigned auto_increment
        primary key,
    token varchar(255) not null,
    state varchar(255) not null,
    constraint game_token_uindex
        unique (token)
);

create table received_shot (
    game_id  int unsigned                not null,
    player   enum ('player', 'computer') not null,
    row      smallint unsigned           not null,
    `column` smallint unsigned           not null,
    constraint received_shot_game_id_fk
        foreign key (game_id) references game(id)
);

create index received_shot_game_id_player_index
    on received_shot(game_id, player);

create table ship_position (
    game_id   int unsigned                    not null,
    player    enum ('player', 'computer')     not null,
    ship      varchar(255)                    not null,
    row       smallint unsigned               not null,
    `column`  smallint unsigned               not null,
    direction enum ('horizontal', 'vertical') not null,
    primary key (game_id, player, ship),
    constraint ship_position_game_id_fk
        foreign key (game_id) references game(id)
);

