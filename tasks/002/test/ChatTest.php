<?php
require_once(dirname(__FILE__).'/../lib/Chat.php');
class ChatTest extends PHPUnit_Framework_TestCase {
  public function testSimpleMessaging(){
    $chat = new Chat();
    $client1 = $chat->createClient("bob");
    $client2 = $chat->createClient("susan");

    $room = $chat->createChatroom('Bob & Susan');
    $room->addClient($client1);
    $room->addClient($client2);


    $listener = $this->getMock("Listener", array('receive'));
    $listener->expects($this->once())
      ->method("receive")
      ->with($this->equalTo($client1), $this->equalTo($room), $this->equalTo("Hey Susan"));
    $client2->addListener($listener);

    $room->send($client1, 'Hey Susan');
  }

  public function testRoomOccupants(){
    $chat = new Chat();
    $room = $chat->createChatroom("Starcraft II");
    $this->assertEquals($room->getOccupants(), array());
    $client1 = $chat->createClient("bob");
    $client2 = $chat->createClient("susan");
    $client3 = $chat->createClient("dillan");
    $room->addClient($client1);
    $room->addClient($client2);
    $room->addClient($client3);
    $this->assertEquals($room->getOccupants(), array($client1, $client2, $client3));
  }

  public function testGroupChat(){
    $chat = new Chat();
    $client1 = $chat->createClient("bob");
    $client2 = $chat->createClient("susan");
    $client3 = $chat->createClient("dillan");
    $client4 = $chat->createClient("gordon");

    $starcraft = $chat->createChatroom("Startcraft II");
    $hots = $chat->createChatroom("Heart of the Swarm");

    $starcraft->addClient($client1);
    $starcraft->addClient($client2);
    $starcraft->addClient($client3);

    $hots->addClient($client2);
    $hots->addClient($client3);
    $hots->addClient($client4);

    $listener = $this->getMock("Listener", array("receive"));
    $listener->expects($this->once())
      ->method("receive")
      ->with($this->equalTo($client1), $this->equalTo($starcraft), $this->equalTo('Starcraft II is amazing.'));
    $client2->addListener($listener);

    $starcraft->send($client1, 'Starcraft II is amazing.');

    $listener = $this->getMock("Listener", array('receive'));
    $listener->expects($this->once())
        ->method("receive")
        ->with($this->equalTo($client2), $this->equalTo($hots), $this->equalTo('HotS is better than the original.'));
    $client4->addListener($listener);

    $hots->send($client2, 'HotS is better than the original.');
  }
}
