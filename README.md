# battleship-api

## Documentation

### Setup
When you have docker installed, just run
```shell script
docker-compose up
```

On first run, you will need to run the queries from `database.sql` to initialize the database.

### Gameplay

#### Start a new game
To start a new game, send an empty request to:

`POST /new-game`

You will then receive a token. You need to keep this token in order to access and play your game. There is no further authentication required.

After you started the game, the computer player will automatically place all his ships on his battleground and you will need to do this, too.

#### Place s ship on your own battleground
To place one of your own ships on your battleground, send a request to:

`POST /game/{token}/ship`

In the body you need to define which ship you want to place. Available ships are listed in the `ShipFactory` class. You only have one of each. You define the position of the ship by giving the coordinate for its upper left corner and the direction in which it should point.
```json
{
    "ship": "submarine",
    "field": "D2",
    "direction": "horizontal"
}
```
In this example, your `submarine` ship (length 3) would be placed horizontally on the fields D2, D3 and D4.

#### Fire a shot at your opponent's battleground
After you have place all of your available ships, it is randomly selected if you or the computer player starts. If the computer starts, he will automatically place his shot.
When it's your turn, you can place your shot on the opponents battleground by making a request to:

`POST /game/{token}/shot`

In the body, just define the field you want to shoot at:
```json
{
    "field": "A3"
}
```

#### Win the game
When the game has winner, you will see this in the response to your last shot. Additionally you won't be able to do any actions on the finished game. When trying to do that, you will get an appropriate message.

### Computer player
The `ComputerPlayer` can do two things:
 
He can place all of his available ships on the battleground. You can control how he does that by using different implementations of the `ShipPlacementStrategy`.
 
He can also shoot his opponent's ships once (or more, as long as the last shot was a hit). The used implementation of `ShootingStrategy` determines how he calculates the next target position.


## TODO
- Add more intelligent computer player strategies
- API response formatting wrapper
- Improve performance
- Validation of inputs
- Switch from handling received shots to handle fired shots, should be more logically then. 
